<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Config\Database;
use App\Models\ActivityLogModel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use ZipArchive;

class BackupController extends ResourceController
{
    protected $format = 'json';

    private const DRIVE_API = 'https://www.googleapis.com/drive/v3';
    private const UPLOAD_API = 'https://www.googleapis.com/upload/drive/v3/files';
    private const PROTECTED_TABLES = ['activity_logs', 'backups'];

    public function index()
    {
        try {
            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $query = sprintf(
                "'%s' in parents and trashed = false and name contains 'GAD-AMS-'",
                addslashes($folderId)
            );
            $result = $this->driveRequest('/files', $token, [
                'q' => $query,
                'orderBy' => 'createdTime desc',
                'pageSize' => 50,
                'fields' => 'files(id,name,size,createdTime,webViewLink,mimeType)',
            ]);

            $files = array_values(array_filter(
                $result['files'] ?? [],
                static fn (array $file): bool => self::isBackupName($file['name'] ?? '')
            ));
            $metadata = $this->getBackupMetadata($files);
            foreach ($files as &$file) {
                $details = $metadata[$file['id'] ?? ''] ?? null;
                $file['created_by'] = $details['created_by'] ?? null;
                $file['created_by_email'] = $details['created_by_email'] ?? null;
                $file['created_by_role'] = $details['created_by_role'] ?? null;
            }
            unset($file);
            $this->logBackupAction('View Backups', sprintf('Viewed backup list (%d backup(s)).', count($files)));
            return $this->respond(['success' => true, 'data' => $files]);
        } catch (\Throwable $exception) {
            log_message('error', 'Backup listing failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError('Unable to connect to Google Drive.');
        }
    }

    public function create()
    {
        try {
            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $archivePath = $this->createBackupArchive();
            $name = 'GAD-AMS-backup-' . date('Y-m-d-H-i-s') . '.zip';
            $archiveContents = file_get_contents($archivePath);
            if ($archiveContents === false) {
                throw new RuntimeException('Unable to read the temporary backup archive.');
            }
            $file = $this->uploadSnapshot($token, $folderId, $name, $archiveContents);
            $this->recordBackupMetadata($file, 'backup');
            if (!@unlink($archivePath)) {
                log_message('warning', 'Unable to remove temporary backup archive: {path}', ['path' => $archivePath]);
            }

            $this->logBackupAction('Create Backup', 'Created and uploaded ' . ($file['name'] ?? $name) . ' to Google Drive.');
            return $this->respondCreated(['success' => true, 'data' => $file]);
        } catch (\Throwable $exception) {
            log_message('error', 'Backup creation failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError('Unable to create the Google Drive backup.');
        }
    }

    public function delete($id = null)
    {
        try {
            $fileId = (string) $id;
            if ($fileId === '') {
                return $this->failNotFound('Backup not found.');
            }

            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $file = $this->driveRequest('/files/' . rawurlencode($fileId), $token, [
                'fields' => 'id,name,parents,trashed',
            ]);
            if (
                empty($file['id']) ||
                ($file['name'] ?? '') === '' ||
                !self::isBackupName($file['name']) ||
                !in_array($folderId, $file['parents'] ?? [], true) ||
                !empty($file['trashed'])
            ) {
                return $this->failNotFound('Backup not found.');
            }

            $this->driveRequest('/files/' . rawurlencode($fileId), $token, [], 'DELETE');

            $this->logBackupAction('Delete Backup', 'Deleted ' . ($file['name'] ?? $fileId) . ' from Google Drive.');
            return $this->respond(['success' => true]);
        } catch (\Throwable $exception) {
            log_message('error', 'Backup deletion failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError('Unable to delete the Google Drive backup.');
        }
    }

    public function download($id = null)
    {
        try {
            $fileId = (string) $id;
            if ($fileId === '') {
                return $this->failNotFound('Backup not found.');
            }
            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $file = $this->driveRequest('/files/' . rawurlencode($fileId), $token, [
                'fields' => 'id,name,parents,trashed,mimeType',
            ]);
            if (
                empty($file['id']) ||
                !self::isBackupName($file['name'] ?? '') ||
                !in_array($folderId, $file['parents'] ?? [], true) ||
                !empty($file['trashed'])
            ) {
                return $this->failNotFound('Backup not found.');
            }

            $contents = $this->downloadDriveFile($token, $fileId);
            $this->logBackupAction('Download Backup', 'Downloaded ' . ($file['name'] ?? $fileId) . ' locally.');
            return $this->response
                ->setHeader('Content-Type', $file['mimeType'] ?? 'application/octet-stream')
                ->setHeader('Content-Disposition', 'attachment; filename="' . basename($file['name']) . '"')
                ->setBody($contents);
        } catch (\Throwable $exception) {
            log_message('error', 'Backup download failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError('Unable to download the backup.');
        }
    }

    public function preview($id = null)
    {
        try {
            $archivePath = $this->downloadArchiveToTemp($id);
            $zip = new ZipArchive();
            if ($zip->open($archivePath) !== true) {
                throw new RuntimeException('The selected backup is not a valid ZIP archive.');
            }
            $databaseSize = 0;
            $fileCount = 0;
            for ($index = 0; $index < $zip->numFiles; $index++) {
                $entry = $zip->statIndex($index);
                if (!$entry || str_ends_with($entry['name'], '/')) {
                    continue;
                }
                if ($entry['name'] === 'database.sql') {
                    $databaseSize = (int) $entry['size'];
                } elseif ($entry['name'] !== 'manifest.json') {
                    $fileCount++;
                }
            }
            $zip->close();
            @unlink($archivePath);
            $this->logBackupAction('Preview Backup', sprintf(
                'Previewed %s (%d database bytes, %d files).',
                (string) $id,
                $databaseSize,
                $fileCount
            ));
            return $this->respond(['success' => true, 'data' => [
                'created_at' => 'Selected backup',
                'database_size' => $databaseSize,
                'file_count' => $fileCount,
            ]]);
        } catch (\Throwable $exception) {
            log_message('error', 'Backup preview failed: {message}', ['message' => $exception->getMessage()]);
            if (env('CI_ENVIRONMENT') !== 'production') {
                return $this->failServerError('Preview failed: ' . $exception->getMessage());
            }
            return $this->failServerError('Unable to preview this backup. Legacy JSON backups cannot be restored.');
        }
    }

    public function restore($id = null)
    {
        $payload = $this->request->getJSON(true) ?? [];
        $mode = $payload['mode'] ?? 'strict-both';
        $confirmation = $payload['confirmation'] ?? '';
        if ($mode !== 'strict-both') {
            return $this->failValidationErrors('Only strict database and file replacement is available.');
        }
        if (trim((string) $confirmation) !== 'STRICT RESTORE') {
            return $this->failValidationErrors('Type STRICT RESTORE to confirm complete database and file replacement.');
        }

        $archivePath = null;
        $safetyPath = null;
        $restoreStage = 'starting';
        try {
            $restoreStage = 'creating safety backup';
            $safetyPath = $this->createBackupArchive();
            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $safetyContents = file_get_contents($safetyPath);
            if ($safetyContents === false) {
                throw new RuntimeException('Unable to read the safety backup.');
            }
            $safety = $this->uploadSnapshot($token, $folderId, 'GAD-AMS-before-restore-' . date('Y-m-d-H-i-s') . '.zip', $safetyContents);
            $this->recordBackupMetadata($safety, 'before-restore');
            @unlink($safetyPath);
            $safetyPath = null;
            $restoreStage = 'downloading selected Google Drive backup';
            $archivePath = $this->downloadArchiveToTemp($id);
            $restoreStage = 'restoring ' . $mode . ' data';
            $this->restoreArchive($archivePath, $mode);
            @unlink($archivePath);
            $archivePath = null;
            $this->logBackupAction('Restore Backup', sprintf('Restored %s from backup ID %s. Safety backup: %s.', $mode, $id, $safety['name'] ?? 'created'));
            return $this->respond(['success' => true, 'message' => 'Backup restored successfully.', 'safety_backup' => $safety]);
        } catch (\Throwable $exception) {
            if ($archivePath) @unlink($archivePath);
            if ($safetyPath) @unlink($safetyPath);
            log_message('error', 'Backup restore failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError(sprintf(
                'Restore failed during %s: %s',
                $restoreStage,
                $exception->getMessage()
            ));
        }
    }

    public function restoreLocal()
    {
        $confirmation = (string) $this->request->getPost('confirmation');
        if (trim($confirmation) !== 'STRICT RESTORE') {
            return $this->failValidationErrors('Type STRICT RESTORE to confirm complete database and file replacement.');
        }

        $uploadedFile = $this->request->getFile('backup');
        if (!$uploadedFile || !$uploadedFile->isValid()) {
            return $this->failValidationErrors('Select a valid ZIP backup file.');
        }
        if (strtolower($uploadedFile->getExtension()) !== 'zip') {
            return $this->failValidationErrors('Only ZIP backup files can be restored.');
        }

        $archivePath = null;
        $safetyPath = null;
        $restoreStage = 'starting';
        try {
            $archivePath = WRITEPATH . 'GAD-AMS-local-restore-' . bin2hex(random_bytes(8)) . '.zip';
            if (!$uploadedFile->move(dirname($archivePath), basename($archivePath))) {
                throw new RuntimeException('Unable to save the uploaded backup temporarily.');
            }

            $restoreStage = 'creating safety backup';
            $safetyPath = $this->createBackupArchive();
            $token = $this->getAccessToken();
            $folderId = $this->getFolderId($token);
            $safetyContents = file_get_contents($safetyPath);
            if ($safetyContents === false) {
                throw new RuntimeException('Unable to read the safety backup.');
            }
            $safety = $this->uploadSnapshot($token, $folderId, 'GAD-AMS-before-restore-' . date('Y-m-d-H-i-s') . '.zip', $safetyContents);
            $this->recordBackupMetadata($safety, 'before-restore');
            @unlink($safetyPath);
            $safetyPath = null;

            $restoreStage = 'restoring local backup data';
            $this->restoreArchive($archivePath, 'strict-both');
            @unlink($archivePath);
            $archivePath = null;
            $this->logBackupAction('Restore Local Backup', 'Strictly restored local backup ' . $uploadedFile->getClientName() . '. Safety backup: ' . ($safety['name'] ?? 'created') . '.');
            return $this->respond(['success' => true, 'message' => 'Local backup restored successfully.', 'safety_backup' => $safety]);
        } catch (\Throwable $exception) {
            if ($archivePath) @unlink($archivePath);
            if ($safetyPath) @unlink($safetyPath);
            log_message('error', 'Local backup restore failed: {message}', ['message' => $exception->getMessage()]);
            return $this->failServerError(sprintf('Local restore failed during %s: %s', $restoreStage, $exception->getMessage()));
        }
    }

    private function createBackupArchive(): string
    {
        if (!class_exists(ZipArchive::class)) {
            throw new RuntimeException('The PHP ZIP extension is required to create backups.');
        }

        $archivePath = WRITEPATH . 'GAD-AMS-backup-' . bin2hex(random_bytes(8)) . '.zip';
        $archive = new ZipArchive();
        if ($archive->open($archivePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException('Unable to create the temporary backup archive.');
        }

        $archive->addFromString('database.sql', $this->createSqlDump());
        $directories = [
            WRITEPATH . 'uploads' => 'uploads',
        ];
        foreach ($this->getPublicUploadDirectories() as $directory) {
            $directories[$directory] = 'public-uploads';
        }
        foreach ($directories as $directory => $archiveRoot) {
            $this->addDirectoryToArchive($archive, $directory, $archiveRoot);
        }

        $archive->close();
        return $archivePath;
    }

    private function downloadArchiveToTemp($id): string
    {
        $fileId = (string) $id;
        if ($fileId === '') {
            throw new RuntimeException('Backup not found.');
        }
        $token = $this->getAccessToken();
        $folderId = $this->getFolderId($token);
        $file = $this->driveRequest('/files/' . rawurlencode($fileId), $token, [
            'fields' => 'id,name,parents,trashed,mimeType',
        ]);
        if (
            empty($file['id']) ||
            !self::isBackupName($file['name'] ?? '') ||
            !str_ends_with(strtolower($file['name'] ?? ''), '.zip') ||
            !in_array($folderId, $file['parents'] ?? [], true) ||
            !empty($file['trashed'])
        ) {
            throw new RuntimeException('Only ZIP backups can be restored.');
        }

        $path = WRITEPATH . 'GAD-AMS-restore-' . bin2hex(random_bytes(8)) . '.zip';
        $contents = $this->downloadDriveFile($token, $fileId);
        if (file_put_contents($path, $contents) === false) {
            throw new RuntimeException('Unable to save the backup temporarily.');
        }
        return $path;
    }

    private function restoreArchive(string $archivePath, string $mode): void
    {
        $zip = new ZipArchive();
        if ($zip->open($archivePath) !== true) {
            throw new RuntimeException('The selected backup is not a valid ZIP archive.');
        }
        $databaseSql = $zip->getFromName('database.sql');
        if ($databaseSql === false || trim($databaseSql) === '') {
            $zip->close();
            throw new RuntimeException('The selected backup does not contain database.sql.');
        }
        if ($mode === 'strict-both') {
            $connection = Database::connect()->getConnection();
            $this->dropAllDatabaseTables($connection);
            $databaseSql = $this->removeProtectedTableStatements($databaseSql);
            if (!method_exists($connection, 'multi_query') || !$connection->multi_query($databaseSql)) {
                $error = $connection->error ?? 'Unknown database error.';
                $zip->close();
                throw new RuntimeException('Database restore failed: ' . $error);
            }
            while (method_exists($connection, 'more_results') && $connection->more_results()) {
                if (!$connection->next_result()) {
                    $error = $connection->error ?? 'Unknown database error.';
                    $zip->close();
                    throw new RuntimeException('Database restore failed while executing a later statement: ' . $error);
                }
            }
        }
        $this->clearManagedUploadDirectories();
        $this->restoreFilesFromArchive($zip);
        $zip->close();
    }

    private function restoreFilesFromArchive(ZipArchive $zip): void
    {
        $roots = [
            'uploads/' => [WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR],
            'public-uploads/' => array_map(
                static fn (string $directory): string => $directory . DIRECTORY_SEPARATOR,
                $this->getPublicUploadDirectories()
            ),
        ];
        for ($index = 0; $index < $zip->numFiles; $index++) {
            $entry = $zip->statIndex($index);
            $name = $entry['name'] ?? '';
            if (!$entry || str_ends_with($name, '/') || $name === 'database.sql') {
                continue;
            }

            foreach ($roots as $prefix => $targetRoots) {
                if (!str_starts_with($name, $prefix)) {
                    continue;
                }
                $relative = substr($name, strlen($prefix));
                if ($relative === '' || str_contains($relative, '..')) {
                    throw new RuntimeException('Unsafe file path in backup archive.');
                }
                $contents = $zip->getFromIndex($index);
                if ($contents === false) {
                    throw new RuntimeException('Unable to read uploaded file from backup.');
                }
                foreach ($targetRoots as $targetRoot) {
                    $target = $targetRoot . str_replace('/', DIRECTORY_SEPARATOR, $relative);
                    $parent = dirname($target);
                    if (!is_dir($parent) && !mkdir($parent, 0775, true) && !is_dir($parent)) {
                        throw new RuntimeException('Unable to create restore directory.');
                    }
                    if (file_put_contents($target, $contents) === false) {
                        throw new RuntimeException('Unable to restore uploaded file.');
                    }
                }
                break;
            }
        }
    }

    private function dropAllDatabaseTables(\mysqli $connection): void
    {
        $database = Database::connect();
        if (!$connection->query('SET FOREIGN_KEY_CHECKS=0')) {
            throw new RuntimeException('Unable to prepare the database for strict restore: ' . $connection->error);
        }

        try {
            $tables = $database->query('SHOW FULL TABLES')->getResultArray();
            foreach ($tables as $table) {
                $values = array_values($table);
                $tableName = $values[0] ?? null;
                $tableType = strtoupper((string) ($values[1] ?? 'BASE TABLE'));
                if ($tableName && !in_array($tableName, self::PROTECTED_TABLES, true)) {
                    $escapedName = $database->escapeIdentifiers($tableName);
                    $dropCommand = $tableType === 'VIEW' ? 'DROP VIEW IF EXISTS ' : 'DROP TABLE IF EXISTS ';
                    if (!$connection->query($dropCommand . $escapedName)) {
                        throw new RuntimeException('Unable to remove existing database object: ' . $connection->error);
                    }
                }
            }
        } finally {
            $connection->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function clearManagedUploadDirectories(): void
    {
        $directories = array_merge(
            [WRITEPATH . 'uploads'],
            $this->getPublicUploadDirectories()
        );

        foreach (array_unique($directories) as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $entry) {
                if ($entry->isDir()) {
                    if (!@rmdir($entry->getPathname())) {
                        throw new RuntimeException('Unable to remove an existing upload directory.');
                    }
                } elseif (!@unlink($entry->getPathname())) {
                    throw new RuntimeException('Unable to remove an existing uploaded file.');
                }
            }

        }
    }

    private function getPublicUploadDirectories(): array
    {
        $publicRoot = rtrim(FCPATH, DIRECTORY_SEPARATOR);
        $directories = [$publicRoot . DIRECTORY_SEPARATOR . 'uploads'];

        if (strtolower(basename($publicRoot)) !== 'api') {
            $directories[] = $publicRoot . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'uploads';
        }

        return array_values(array_unique($directories));
    }

    private function backupDateFromId($id): string
    {
        return 'Selected backup';
    }

    private static function isBackupName(string $name): bool
    {
        return str_starts_with($name, 'GAD-AMS-backup-')
            || str_starts_with($name, 'GAD-AMS-before-restore-');
    }

    private function logBackupAction(string $action, string $description): void
    {
        $payload = $this->request->jwtPayload ?? [];
        $userId = $payload['sub'] ?? $payload['id'] ?? null;
        if ($userId) {
            ActivityLogModel::log($userId, $action, $description);
        }
    }

    private function currentUserId(): ?int
    {
        $payload = $this->request->jwtPayload ?? [];
        $userId = $payload['sub'] ?? $payload['id'] ?? null;
        return is_numeric($userId) ? (int) $userId : null;
    }

    private function recordBackupMetadata(array $file, string $backupType): void
    {
        $fileId = $file['id'] ?? null;
        if (!$fileId) {
            throw new RuntimeException('Google Drive did not return a backup file ID.');
        }

        Database::connect()->table('backups')->insert([
            'drive_file_id' => $fileId,
            'name' => $file['name'] ?? 'Unnamed backup',
            'size_bytes' => (int) ($file['size'] ?? 0),
            'backup_type' => $backupType,
            'created_by' => $this->currentUserId(),
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    private function getBackupMetadata(array $files): array
    {
        $fileIds = array_values(array_filter(array_column($files, 'id')));
        if (!$fileIds) {
            return [];
        }

        $rows = Database::connect()->table('backups')
            ->select('backups.drive_file_id, backups.created_by, users.email AS created_by_email, users.role AS created_by_role')
            ->join('users', 'users.id = backups.created_by', 'left')
            ->whereIn('backups.drive_file_id', $fileIds)
            ->get()
            ->getResultArray();

        $metadata = [];
        foreach ($rows as $row) {
            $metadata[$row['drive_file_id']] = $row;
        }
        return $metadata;
    }

    private function createSqlDump(): string
    {
        $database = Database::connect();
        $lines = [
            '-- GAD-AMS MySQL backup',
            '-- Created: ' . date(DATE_ATOM),
            'SET FOREIGN_KEY_CHECKS=0;',
            '',
        ];

        foreach ($database->listTables() as $table) {
            if (in_array($table, self::PROTECTED_TABLES, true)) {
                continue;
            }
            $tableName = $database->escapeIdentifiers($table);
            $create = $database->query('SHOW CREATE TABLE ' . $tableName)->getRowArray();
            $createSql = $create['Create Table'] ?? $create['Create View'] ?? null;
            if (!$createSql) {
                continue;
            }

            $lines[] = 'DROP TABLE IF EXISTS ' . $tableName . ';';
            $lines[] = $createSql . ';';
            $rows = $database->table($table)->get()->getResultArray();
            if ($rows) {
                $columns = array_map([$database, 'escapeIdentifiers'], array_keys($rows[0]));
                foreach ($rows as $row) {
                    $values = array_map(
                        fn ($value) => $value === null ? 'NULL' : $database->escape((string) $value),
                        array_values($row)
                    );
                    $lines[] = 'INSERT INTO ' . $tableName . ' (' . implode(', ', $columns) . ') VALUES (' . implode(', ', $values) . ');';
                }
            }
            $lines[] = '';
        }

        $lines[] = 'SET FOREIGN_KEY_CHECKS=1;';
        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    private function removeProtectedTableStatements(string $sql): string
    {
        foreach (self::PROTECTED_TABLES as $table) {
            $identifier = preg_quote($table, '/');
            $sql = preg_replace(
                '/^DROP TABLE IF EXISTS [`"]?' . $identifier . '[`"]?\\s*;\\s*$/mi',
                '',
                $sql
            );
            $sql = preg_replace(
                '/^CREATE TABLE [`"]?' . $identifier . '[`"]?.*?;\\s*$/mis',
                '',
                $sql
            );
            $sql = preg_replace(
                '/^INSERT INTO [`"]?' . $identifier . '[`"]?.*?;\\s*$/mi',
                '',
                $sql
            );
        }

        return $sql;
    }

    private function addDirectoryToArchive(ZipArchive $archive, string $directory, string $archiveRoot): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $directory = rtrim($directory, DIRECTORY_SEPARATOR);
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($iterator as $file) {
            if (!$file->isFile()) {
                continue;
            }

            $relativePath = substr($file->getPathname(), strlen($directory) + 1);
            if (str_starts_with($relativePath, 'tmp_')) {
                continue;
            }
            $archive->addFile($file->getPathname(), $archiveRoot . '/' . str_replace(DIRECTORY_SEPARATOR, '/', $relativePath));
        }
    }

    private function getAccessToken(): string
    {
        $clientId = env('GOOGLE_CLIENT_ID');
        $clientSecret = env('GOOGLE_CLIENT_SECRET');
        $refreshToken = env('GOOGLE_REFRESH_TOKEN');

        if (!$clientId || !$clientSecret || !$refreshToken) {
            throw new RuntimeException('Google Drive credentials are not configured.');
        }

        $response = $this->requestJson(
            'https://oauth2.googleapis.com/token',
            [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'refresh_token' => $refreshToken,
                'grant_type' => 'refresh_token',
            ],
            'POST',
            ['Content-Type: application/x-www-form-urlencoded']
        );

        if (empty($response['access_token'])) {
            throw new RuntimeException('Google did not return an access token.');
        }

        return $response['access_token'];
    }

    private function getFolderId(string $token): string
    {
        $configuredFolder = env('GOOGLE_DRIVE_BACKUP_FOLDER_ID');
        if ($configuredFolder) {
            return $configuredFolder;
        }

        $result = $this->driveRequest('/files', $token, [
            'q' => "name = 'GAD-AMS Backups' and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
            'pageSize' => 1,
            'fields' => 'files(id)',
        ]);

        if (!empty($result['files'][0]['id'])) {
            return $result['files'][0]['id'];
        }

        $folder = $this->driveRequest('/files', $token, [], 'POST', [
            'name' => 'GAD-AMS Backups',
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        if (empty($folder['id'])) {
            throw new RuntimeException('Unable to create the Google Drive backup folder.');
        }

        return $folder['id'];
    }

    private function uploadSnapshot(string $token, string $folderId, string $name, string $contents): array
    {
        $boundary = 'gadams-' . bin2hex(random_bytes(12));
        $metadata = json_encode([
            'name' => $name,
            'parents' => [$folderId],
            'mimeType' => 'application/json',
        ], JSON_THROW_ON_ERROR);
        $body = "--{$boundary}\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n{$metadata}\r\n"
            . "--{$boundary}\r\nContent-Type: application/json\r\n\r\n{$contents}\r\n--{$boundary}--";

        return $this->requestJson(
            self::UPLOAD_API . '?uploadType=multipart&fields=id,name,size,createdTime,webViewLink,mimeType',
            $body,
            'POST',
            [
                'Authorization: Bearer ' . $token,
                'Content-Type: multipart/related; boundary=' . $boundary,
            ]
        );
    }

    private function downloadDriveFile(string $token, string $fileId): string
    {
        $curl = curl_init(self::DRIVE_API . '/files/' . rawurlencode($fileId) . '?alt=media');
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
            CURLOPT_TIMEOUT => 120,
        ]);
        $contents = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        if ($contents === false || $error || $status < 200 || $status >= 300) {
            throw new RuntimeException('Google Drive download failed: ' . ($error ?: 'HTTP ' . $status));
        }
        return $contents;
    }

    private function driveRequest(string $path, string $token, array $query = [], string $method = 'GET', ?array $body = null): array
    {
        $url = self::DRIVE_API . $path . ($query ? '?' . http_build_query($query) : '');
        return $this->requestJson($url, $body, $method, ['Authorization: Bearer ' . $token, 'Content-Type: application/json']);
    }

    private function requestJson(string $url, $body, string $method, array $headers): array
    {
        $isJson = false;
        foreach ($headers as $header) {
            if (stripos($header, 'Content-Type: application/json') === 0) {
                $isJson = true;
                break;
            }
        }

        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => is_array($body)
                ? ($isJson ? json_encode($body, JSON_THROW_ON_ERROR) : http_build_query($body))
                : $body,
            CURLOPT_TIMEOUT => 60,
        ]);
        $raw = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($raw === false || $error || $status < 200 || $status >= 300) {
            throw new RuntimeException('Google Drive request failed: ' . ($error ?: 'HTTP ' . $status));
        }

        if ($raw === '' || $status === 204) {
            return [];
        }

        return json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }
}
