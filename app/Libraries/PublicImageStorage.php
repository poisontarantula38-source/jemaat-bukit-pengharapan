<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use RuntimeException;

class PublicImageStorage
{
    private const COLLECTIONS = ['gallery', 'site', 'warta'];

    public function store(UploadedFile $file, string $collection): string
    {
        if (! in_array($collection, self::COLLECTIONS, true)) {
            throw new RuntimeException('Invalid image collection.');
        }

        if (! $file->isValid() || $file->hasMoved()) {
            throw new RuntimeException('The uploaded image is invalid.');
        }

        $uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $collection;
        if (! is_dir($uploadPath) && ! mkdir($uploadPath, 0755, true) && ! is_dir($uploadPath)) {
            throw new RuntimeException('Unable to create the image upload directory.');
        }

        if (! is_writable($uploadPath)) {
            throw new RuntimeException('The image upload directory is not writable.');
        }

        $filename = $file->getRandomName();
        if (basename($filename) !== $filename || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
            throw new RuntimeException('The generated image filename is invalid.');
        }

        try {
            $file->move($uploadPath, $filename);
        } catch (RuntimeException $exception) {
            log_message('error', 'Image upload failed: {message}', ['message' => $exception->getMessage()]);

            throw new RuntimeException('The uploaded image could not be saved.', previous: $exception);
        }

        return $filename;
    }

    public function delete(string $collection, string $filename): void
    {
        if (! in_array($collection, self::COLLECTIONS, true)
            || basename($filename) !== $filename
            || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
            throw new RuntimeException('The stored image filename is invalid.');
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $collection . DIRECTORY_SEPARATOR . $filename;
        if (is_file($path) && ! unlink($path)) {
            throw new RuntimeException('The stored image could not be removed.');
        }
    }
}
