<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Aws\S3\S3Client;

class StorageController extends ResourceController
{
    public function getUploadTicket()
    {
        $filename = $this->request->getVar('filename');
        if (!$filename) {
            return $this->fail('Filename is required');
        }

        $s3 = new S3Client([
            'version'     => 'latest',
            'region'      => 'auto', 
            'endpoint'    => getenv('R2_ENDPOINT'),
            'credentials' => [
                'key'    => getenv('R2_ACCESS_KEY'),
                'secret' => getenv('R2_SECRET_KEY'),
            ],
        ]);

        $bucket = getenv('R2_BUCKET');
        
        $cleanName = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $filename);
        $objectKey = 'uploads/' . time() . '_' . $cleanName;

        $cmd = $s3->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key'    => $objectKey
        ]);

        $request = $s3->createPresignedRequest($cmd, '+15 minutes');

        return $this->respond([
            'upload_url' => (string) $request->getUri(),
            'file_path'  => $objectKey
        ]);
    }
}