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

        $success = FileStorage::overwrite($folder, $cleanName, $file->getTempName(), $file->getMimeType());

        if ($success) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'File overwritten successfully',
                'url' => ($folder === 'drafts') ? FileStorage::getCloudDraftUrl($cleanName) : FileStorage::getCloudArchivedUrl($cleanName)
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to overwrite file'
        ])->setStatusCode(500);
    }
}
