<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ImageService
{
    protected string $disk;
    protected string $directory;
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    public function __construct(string $disk = 'public', string $directory = 'images')
    {
        $this->disk = $disk;
        $this->directory = $directory;
    }

    /**
     * Upload and process an image file
     */
    public function upload(UploadedFile $file, ?string $subdirectory = null, ?int $width = null, ?int $height = null): string
    {
        $this->validateFile($file);

        $filename = $this->generateFilename($file);
        $path = $this->buildPath($subdirectory, $filename);

        if ($width || $height) {
            $this->uploadWithResize($file, $path, $width, $height);
        } else {
            $file->storeAs($this->directory . ($subdirectory ? '/' . $subdirectory : ''), $filename, $this->disk);
        }

        // Clean up temporary file if it's a Livewire temporary file
        $this->cleanupTemporaryFile($file);

        return $path;
    }

    /**
     * Delete an image file
     */
    public function delete(string $path): bool
    {
        if (empty($path)) {
            return false;
        }

        return Storage::disk($this->disk)->delete($path);
    }

    /**
     * Get the full URL for an image
     */
    public function url(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Check if file exists
     */
    public function exists(string $path): bool
    {
        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get file size in bytes
     */
    public function size(string $path): int
    {
        return Storage::disk($this->disk)->size($path);
    }

    /**
     * Validate uploaded file
     */
    protected function validateFile(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new \InvalidArgumentException('Invalid file type. Only JPEG, PNG, GIF, and WebP images are allowed.');
        }

        if ($file->getSize() > 5 * 1024 * 1024) { // 5MB limit
            throw new \InvalidArgumentException('File size too large. Maximum size is 5MB.');
        }
    }

    /**
     * Generate unique filename
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::random(40);

        return "{$filename}.{$extension}";
    }

    /**
     * Build full path
     */
    protected function buildPath(?string $subdirectory, string $filename): string
    {
        $path = $this->directory;

        if ($subdirectory) {
            $path .= '/' . $subdirectory;
        }

        return $path . '/' . $filename;
    }

    /**
     * Upload and resize image using Intervention Image
     * Note: Requires intervention/image package to be installed
     */
    protected function uploadWithResize(UploadedFile $file, string $path, ?int $width, ?int $height): void
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            // Fallback: store file without resizing
            $file->storeAs(dirname($path), basename($path), $this->disk);
            return;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);

            if ($width && $height) {
                $image->resize($width, $height);
            } elseif ($width) {
                $image->resize($width, null);
            } elseif ($height) {
                $image->resize(null, $height);
            }

            $image->save(storage_path('app/' . $this->disk . '/' . $path));
        } catch (\Exception $e) {
            // Fallback: store file without resizing
            $file->storeAs(dirname($path), basename($path), $this->disk);
        }
    }

    /**
     * Create thumbnail
     * Note: Requires intervention/image package to be installed
     */
    public function createThumbnail(string $originalPath, int $width = 150, int $height = 150): string
    {
        // Check if GD extension is available
        if (!extension_loaded('gd')) {
            return $originalPath;
        }

        $pathInfo = pathinfo($originalPath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read(storage_path('app/' . $this->disk . '/' . $originalPath));
            $image->resize($width, $height);
            $image->save(storage_path('app/' . $this->disk . '/' . $thumbnailPath));

            return $thumbnailPath;
        } catch (\Exception $e) {
            // Fallback: return original path
            return $originalPath;
        }
    }

    /**
     * Get thumbnail URL
     */
    public function thumbnailUrl(string $originalPath): string
    {
        $pathInfo = pathinfo($originalPath);
        $thumbnailPath = $pathInfo['dirname'] . '/thumbnails/' . $pathInfo['filename'] . '_thumb.' . $pathInfo['extension'];

        if ($this->exists($thumbnailPath)) {
            return $this->url($thumbnailPath);
        }

        return $this->url($originalPath);
    }

    /**
     * Clean up temporary file if it's a Livewire temporary file
     */
    protected function cleanupTemporaryFile(UploadedFile $file): void
    {
        // Check if this is a Livewire temporary file
        if ($file instanceof TemporaryUploadedFile) {
            try {
                // Delete the temporary file
                $file->delete();
            } catch (\Exception $e) {
                // Silently fail if cleanup doesn't work
                // The file will be cleaned up by Livewire's automatic cleanup after 24 hours
            }
        }
    }
}
