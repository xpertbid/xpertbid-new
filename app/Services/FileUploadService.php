<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class FileUploadService
{
    /**
     * Upload file to storage
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): array
    {
        try {
            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            
            // Get file path
            $path = $file->storeAs($directory, $filename, $disk);
            
            // Get file URL
            $url = Storage::disk($disk)->url($path);
            
            return [
                'success' => true,
                'filename' => $filename,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'url' => $url,
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Upload and resize image
     */
    public function uploadImage(UploadedFile $file, string $directory = 'images', array $sizes = []): array
    {
        try {
            $uploadResult = $this->uploadFile($file, $directory);
            
            if (!$uploadResult['success']) {
                return $uploadResult;
            }

            $image = Image::make($file);
            $resizedImages = [];

            // Default sizes if none provided
            if (empty($sizes)) {
                $sizes = [
                    'thumb' => [150, 150],
                    'medium' => [400, 400],
                    'large' => [800, 800],
                ];
            }

            foreach ($sizes as $sizeName => $dimensions) {
                $resizedPath = $directory . '/' . $sizeName . '_' . $uploadResult['filename'];
                
                $resizedImage = clone $image;
                $resizedImage->fit($dimensions[0], $dimensions[1]);
                
                Storage::disk('public')->put($resizedPath, $resizedImage->encode());
                
                $resizedImages[$sizeName] = [
                    'path' => $resizedPath,
                    'url' => Storage::disk('public')->url($resizedPath),
                    'width' => $dimensions[0],
                    'height' => $dimensions[1],
                ];
            }

            $uploadResult['resized_images'] = $resizedImages;
            
            return $uploadResult;
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(string $path, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Delete multiple files
     */
    public function deleteFiles(array $paths, string $disk = 'public'): array
    {
        $results = [];
        
        foreach ($paths as $path) {
            $results[$path] = $this->deleteFile($path, $disk);
        }
        
        return $results;
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        
        // Sanitize filename
        $name = Str::slug($name);
        
        // Generate unique filename
        return $name . '_' . time() . '_' . Str::random(10) . '.' . $extension;
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $path, string $disk = 'public'): array
    {
        try {
            if (!Storage::disk($disk)->exists($path)) {
                return ['exists' => false];
            }

            return [
                'exists' => true,
                'size' => Storage::disk($disk)->size($path),
                'mime_type' => Storage::disk($disk)->mimeType($path),
                'last_modified' => Storage::disk($disk)->lastModified($path),
                'url' => Storage::disk($disk)->url($path),
            ];
        } catch (\Exception $e) {
            return ['exists' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Move file to different directory
     */
    public function moveFile(string $fromPath, string $toPath, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($fromPath)) {
                Storage::disk($disk)->move($fromPath, $toPath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Copy file to different location
     */
    public function copyFile(string $fromPath, string $toPath, string $disk = 'public'): bool
    {
        try {
            if (Storage::disk($disk)->exists($fromPath)) {
                Storage::disk($disk)->copy($fromPath, $toPath);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats(string $disk = 'public'): array
    {
        try {
            $files = Storage::disk($disk)->allFiles();
            $totalSize = 0;
            $fileCount = count($files);
            
            foreach ($files as $file) {
                $totalSize += Storage::disk($disk)->size($file);
            }
            
            return [
                'total_files' => $fileCount,
                'total_size' => $totalSize,
                'total_size_mb' => round($totalSize / 1024 / 1024, 2),
                'disk' => $disk,
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Clean up old temporary files
     */
    public function cleanupTempFiles(int $olderThanHours = 24): int
    {
        try {
            $tempFiles = Storage::disk('public')->files('temp');
            $deletedCount = 0;
            $cutoffTime = time() - ($olderThanHours * 3600);
            
            foreach ($tempFiles as $file) {
                $lastModified = Storage::disk('public')->lastModified($file);
                
                if ($lastModified < $cutoffTime) {
                    Storage::disk('public')->delete($file);
                    $deletedCount++;
                }
            }
            
            return $deletedCount;
        } catch (\Exception $e) {
            return 0;
        }
    }
}
