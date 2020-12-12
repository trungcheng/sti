<?php

namespace App\Utilities;

use Illuminate\Support\Facades\Storage;

/**
 * Class S3
 * @package App\Utilities
 */
class S3
{
    /**
     * Get signed Urls in 10 minutes.
     *
     * @param string $fileName
     * @param string $method
     * @param string $contentType
     * @param string $diskName
     * @param string $bucket
     *
     * @return string
     */
    public static function makePresignedUrls(
        $fileName,
        $method = 'GetObject',
        $contentType = 'application/octet-stream',
        $diskName = 's3',
        $bucket = ''
    ) {
        //this code for generate new signed url of your file
        $disk = Storage::disk($diskName);

        $command = $disk->getDriver()->getAdapter()->getClient()->getCommand(
            $method,
            [
                'Bucket' => $bucket ? $bucket : config('main.s3_upload.aws_bucket'),
                'Key' => $fileName,
                'ResponseContentType' => $contentType
            ]
        );

        $request = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest(
            $command,
            config('main.s3_presigned_expiry')
        );

        return (string)$request->getUri();
    }

    /**
     * Move multi files
     * @param array $paths [oldPath => newPath, ...]
     */
    public static function moveObjects(array $paths)
    {
        $disk = Storage::disk('s3');

        foreach ($paths as $oldPath => $newPath) {
            $disk->move($oldPath, $newPath);
        }
    }
}
