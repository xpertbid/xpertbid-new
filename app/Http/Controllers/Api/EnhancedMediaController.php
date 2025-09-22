<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EnhancedMediaController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    /**
     * Upload files directly to storage
     */
    public function uploadFiles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'file|max:102400', // 100MB max per file
            'directory' => 'nullable|string|max:255',
            'resize_images' => 'nullable|boolean',
            'image_sizes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $directory = $request->get('directory', 'uploads/' . date('Y/m/d'));
            $resizeImages = $request->get('resize_images', false);
            $imageSizes = $request->get('image_sizes', []);
            
            $uploadedFiles = [];
            $errors = [];

            foreach ($request->file('files') as $file) {
                try {
                    if ($resizeImages && $this->isImageFile($file)) {
                        $result = $this->fileUploadService->uploadImage($file, $directory, $imageSizes);
                    } else {
                        $result = $this->fileUploadService->uploadFile($file, $directory);
                    }

                    if ($result['success']) {
                        $uploadedFiles[] = $result;
                    } else {
                        $errors[] = [
                            'file' => $file->getClientOriginalName(),
                            'error' => $result['error'] ?? 'Unknown error'
                        ];
                    }
                } catch (\Exception $e) {
                    $errors[] = [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => count($uploadedFiles) . ' file(s) uploaded successfully',
                'data' => $uploadedFiles,
                'errors' => $errors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Upload failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete files from storage
     */
    public function deleteFiles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file_paths' => 'required|array|min:1',
            'file_paths.*' => 'string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filePaths = $request->file_paths;
            $results = $this->fileUploadService->deleteFiles($filePaths);

            $deletedCount = count(array_filter($results, function($result) {
                return $result === true;
            }));

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' file(s) deleted successfully',
                'data' => $results
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get file information
     */
    public function getFileInfo(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file_path' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $fileInfo = $this->fileUploadService->getFileInfo($request->file_path);

            if (!$fileInfo['exists']) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $fileInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting file info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats(): JsonResponse
    {
        try {
            $stats = $this->fileUploadService->getStorageStats();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting storage stats',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clean up temporary files
     */
    public function cleanupTempFiles(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'older_than_hours' => 'nullable|integer|min:1|max:168', // Max 1 week
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $olderThanHours = $request->get('older_than_hours', 24);
            $deletedCount = $this->fileUploadService->cleanupTempFiles($olderThanHours);

            return response()->json([
                'success' => true,
                'message' => $deletedCount . ' temporary file(s) cleaned up',
                'data' => ['deleted_count' => $deletedCount]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cleaning up files',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate signed URL for file access
     */
    public function generateSignedUrl(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'file_path' => 'required|string|max:500',
            'expires_in_minutes' => 'nullable|integer|min:1|max:1440', // Max 24 hours
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $filePath = $request->file_path;
            $expiresInMinutes = $request->get('expires_in_minutes', 60);

            if (!Storage::disk('public')->exists($filePath)) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            $signedUrl = Storage::disk('public')->temporaryUrl(
                $filePath,
                now()->addMinutes($expiresInMinutes)
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'signed_url' => $signedUrl,
                    'expires_at' => now()->addMinutes($expiresInMinutes)->toISOString(),
                    'file_path' => $filePath
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error generating signed URL',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check if file is an image
     */
    private function isImageFile($file): bool
    {
        $imageMimeTypes = [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'image/svg+xml',
            'image/bmp',
            'image/tiff'
        ];

        return in_array($file->getMimeType(), $imageMimeTypes);
    }
}
