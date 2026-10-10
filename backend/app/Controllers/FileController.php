<?php

namespace App\Controllers;

use App\Libraries\FileStorage;

/**
 * FileController — serves files either locally or via Cloudflare R2 redirect.
 *
 * Routes:
 *   GET api/files/drafts/{filename}
 *   GET api/files/archived/{filename}
 */
class FileController extends BaseController
{
    public function serveDraft(string $filename)
    {
        if (FileStorage::isCloud()) {
            return $this->response->redirect(FileStorage::getCloudDraftUrl(basename($filename)));
        }
        return $this->serveFile(FileStorage::draftsPath(), $filename);
    }

    public function serveArchived(string $filename)
    {
        if (FileStorage::isCloud()) {
            return $this->response->redirect(FileStorage::getCloudArchivedUrl(basename($filename)));
        }
        return $this->serveFile(FileStorage::archivedPath(), $filename);
    }

    public function serveNewsIec(string $filename)
    {
        $filename = basename($filename);
        $publicPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'news_iec' . DIRECTORY_SEPARATOR . $filename;
        $writablePath = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'newsiec images' . DIRECTORY_SEPARATOR . $filename;

        if (file_exists($publicPath)) {
            $filepath = $publicPath;
        } elseif (file_exists($writablePath)) {
            $filepath = $writablePath;
        } else {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['success' => false, 'message' => 'File not found']);
        }

        $mime = 'application/octet-stream';
        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($filepath);
        }
        if (!$mime || $mime === 'application/octet-stream') {
            $ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
            $mimes = [
                'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
                'png' => 'image/png', 'gif' => 'image/gif',
                'webp' => 'image/webp', 'svg' => 'image/svg+xml'
            ];
            $mime = $mimes[$ext] ?? 'application/octet-stream';
        }

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'public, max-age=3600')
            ->setBody(file_get_contents($filepath));
    }

    private function serveFile(string $directory, string $filename)
    {
        // Sanitize filename — no path traversal
        $filename = basename($filename);
        $filepath = $directory . DIRECTORY_SEPARATOR . $filename;

        if (!file_exists($filepath)) {
            // Fallback: Check the other directory in case the file wasn't moved or was overwritten incorrectly
            $otherDirectory = (strpos($directory, 'archived') !== false) ? FileStorage::draftsPath() : FileStorage::archivedPath();
            $otherFilepath = $otherDirectory . DIRECTORY_SEPARATOR . $filename;
            
            if (file_exists($otherFilepath)) {
                $filepath = $otherFilepath;
            } else {
                return $this->response
                    ->setStatusCode(404)
                    ->setJSON(['success' => false, 'message' => 'File not found']);
            }
        }

        $mime = mime_content_type($filepath) ?: 'application/octet-stream';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"')
            ->setHeader('Cache-Control', 'private, max-age=3600')
            ->setBody(file_get_contents($filepath));
    }

    public function overwrite(string $folder, string $filename)
    {
        $payload = $this->request->jwtPayload ?? null;
        $role = strtolower($payload['role'] ?? '');
        $userId = $payload['sub'] ?? null;

        // Ensure user is authenticated
        if (!$userId) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Unauthorized: Please log in to overwrite files'
            ])->setStatusCode(401);
        }

        // Whitelist allowed folders
        $cleanFolder = strtolower(basename($folder));
        if (!in_array($cleanFolder, ['drafts', 'archived'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid folder specified'
            ])->setStatusCode(400);
        }

        // Only GAD Staff and Admin can overwrite archived official documents
        if ($cleanFolder === 'archived' && !in_array($role, ['admin', 'gad_staff', 'staff', 'superadmin'], true)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Forbidden: Only GAD Staff and Administrators can modify archived documents'
            ])->setStatusCode(403);
        }

        $file = $this->request->getFile('pdf_file');

        if (!$file || !$file->isValid()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid file uploaded'
            ])->setStatusCode(400);
        }

        // Only allow PDF
        if ($file->getMimeType() !== 'application/pdf' && $file->getClientExtension() !== 'pdf') {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Only PDF files are allowed'
            ])->setStatusCode(400);
        }

        // Sanitize filename to prevent directory traversal
        $cleanName = basename($filename);

        $success = FileStorage::overwrite($cleanFolder, $cleanName, $file->getTempName(), $file->getMimeType());

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'File overwritten successfully',
                'url' => ($cleanFolder === 'drafts') ? FileStorage::getCloudDraftUrl($cleanName) : FileStorage::getCloudArchivedUrl($cleanName)
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to overwrite file'
        ])->setStatusCode(500);
    }
}
