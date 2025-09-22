<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    /**
     * Upload media files for any model
     */
    public function upload(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'model_type' => 'required|string|in:product,brand,tag,property,vehicle,auction',
            'model_id' => 'required|integer',
            'collection' => 'required|string',
            'files' => 'required|array|min:1|max:10',
            'files.*' => 'file|max:102400', // 100MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $modelClass = $this->getModelClass($request->model_type);
            $model = $modelClass::findOrFail($request->model_id);

            $uploadedMedia = [];
            $errors = [];

            foreach ($request->file('files') as $file) {
                try {
                    $media = $model->addMediaFromRequest('files')
                        ->toMediaCollection($request->collection);

                    $uploadedMedia[] = [
                        'id' => $media->id,
                        'name' => $media->name,
                        'file_name' => $media->file_name,
                        'mime_type' => $media->mime_type,
                        'size' => $media->size,
                        'url' => $media->getUrl(),
                        'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : null,
                        'medium_url' => $media->hasGeneratedConversion('medium') ? $media->getUrl('medium') : null,
                        'large_url' => $media->hasGeneratedConversion('large') ? $media->getUrl('large') : null,
                    ];
                } catch (\Exception $e) {
                    $errors[] = [
                        'file' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ];
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Files uploaded successfully',
                'data' => $uploadedMedia,
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
     * Get media files for a model
     */
    public function getMedia(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'model_type' => 'required|string|in:product,brand,tag,property,vehicle,auction',
            'model_id' => 'required|integer',
            'collection' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $modelClass = $this->getModelClass($request->model_type);
            $model = $modelClass::findOrFail($request->model_id);

            $query = $model->media();
            if ($request->collection) {
                $query->where('collection_name', $request->collection);
            }

            $media = $query->get()->map(function ($media) {
                return [
                    'id' => $media->id,
                    'name' => $media->name,
                    'file_name' => $media->file_name,
                    'mime_type' => $media->mime_type,
                    'size' => $media->size,
                    'collection_name' => $media->collection_name,
                    'url' => $media->getUrl(),
                    'thumb_url' => $media->hasGeneratedConversion('thumb') ? $media->getUrl('thumb') : null,
                    'medium_url' => $media->hasGeneratedConversion('medium') ? $media->getUrl('medium') : null,
                    'large_url' => $media->hasGeneratedConversion('large') ? $media->getUrl('large') : null,
                    'created_at' => $media->created_at,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $media
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve media',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a media file
     */
    public function deleteMedia(Request $request, $mediaId): JsonResponse
    {
        try {
            $media = Media::findOrFail($mediaId);
            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update media order
     */
    public function updateOrder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'media_ids' => 'required|array',
            'media_ids.*' => 'integer|exists:media,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            foreach ($request->media_ids as $order => $mediaId) {
                Media::where('id', $mediaId)->update(['order_column' => $order + 1]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Media order updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update media order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available collections for a model
     */
    public function getCollections(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'model_type' => 'required|string|in:product,brand,tag,property,vehicle,auction',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $collections = $this->getModelCollections($request->model_type);

        return response()->json([
            'success' => true,
            'data' => $collections
        ]);
    }

    /**
     * Get model class from type
     */
    private function getModelClass(string $type): string
    {
        $models = [
            'product' => \App\Models\Product::class,
            'brand' => \App\Models\Brand::class,
            'tag' => \App\Models\Tag::class,
            'property' => \App\Models\Property::class,
            'vehicle' => \App\Models\Vehicle::class,
            'auction' => \App\Models\Auction::class,
        ];

        return $models[$type] ?? throw new \InvalidArgumentException("Invalid model type: {$type}");
    }

    /**
     * Get available collections for model type
     */
    private function getModelCollections(string $type): array
    {
        $collections = [
            'product' => ['thumbnail', 'gallery', 'videos', 'documents', 'digital_files'],
            'brand' => ['logo', 'banner'],
            'tag' => ['icon'],
            'property' => ['images', 'floor_plans', 'virtual_tour'],
            'vehicle' => ['images', 'documents', 'videos'],
            'auction' => ['images', 'videos', 'documents'],
        ];

        return $collections[$type] ?? [];
    }
}
