<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductTranslation;
use App\Models\PropertyTranslation;
use App\Models\VehicleTranslation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class TranslationController extends Controller
{
    /**
     * Get translations for a specific model and ID
     */
    public function getTranslations(Request $request, $model, $id): JsonResponse
    {
        $validator = Validator::make(['model' => $model, 'id' => $id], [
            'model' => 'required|in:product,property,vehicle',
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $translationModel = $this->getTranslationModel($model);
            $translations = $translationModel::where($model . '_id', $id)->get();

            return response()->json([
                'success' => true,
                'data' => $translations
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get translations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store or update translation
     */
    public function storeTranslation(Request $request, $model, $id): JsonResponse
    {
        $validator = Validator::make(array_merge($request->all(), ['model' => $model, 'id' => $id]), [
            'model' => 'required|in:product,property,vehicle',
            'id' => 'required|integer|min:1',
            'locale' => 'required|string|max:10',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string',
            'meta_title' => 'nullable|string',
            'meta_description' => 'nullable|string',
            'meta_keywords' => 'nullable|string',
            'features' => 'nullable|array',
            'specifications' => 'nullable|array',
            'amenities' => 'nullable|array',
            'warranty_info' => 'nullable|string',
            'shipping_info' => 'nullable|string',
            'return_policy' => 'nullable|string',
            'neighborhood_info' => 'nullable|string',
            'school_district' => 'nullable|string',
            'transportation' => 'nullable|string',
            'nearby_attractions' => 'nullable|string',
            'service_history' => 'nullable|string',
            'accident_history' => 'nullable|string',
            'maintenance_records' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $translationModel = $this->getTranslationModel($model);
            $foreignKey = $model . '_id';

            // Check if translation already exists
            $existingTranslation = $translationModel::where($foreignKey, $id)
                ->where('locale', $request->locale)
                ->first();

            if ($existingTranslation) {
                // Update existing translation
                $existingTranslation->update($request->all());
                $translation = $existingTranslation;
            } else {
                // Create new translation
                $translation = $translationModel::create(array_merge($request->all(), [$foreignKey => $id]));
            }

            return response()->json([
                'success' => true,
                'message' => 'Translation saved successfully',
                'data' => $translation
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save translation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete translation
     */
    public function deleteTranslation(Request $request, $model, $id, $locale): JsonResponse
    {
        $validator = Validator::make(['model' => $model, 'id' => $id, 'locale' => $locale], [
            'model' => 'required|in:product,property,vehicle',
            'id' => 'required|integer|min:1',
            'locale' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $translationModel = $this->getTranslationModel($model);
            $foreignKey = $model . '_id';

            $translation = $translationModel::where($foreignKey, $id)
                ->where('locale', $locale)
                ->first();

            if (!$translation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Translation not found'
                ], 404);
            }

            $translation->delete();

            return response()->json([
                'success' => true,
                'message' => 'Translation deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete translation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available locales for a specific model and ID
     */
    public function getAvailableLocales(Request $request, $model, $id): JsonResponse
    {
        $validator = Validator::make(['model' => $model, 'id' => $id], [
            'model' => 'required|in:product,property,vehicle',
            'id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $translationModel = $this->getTranslationModel($model);
            $foreignKey = $model . '_id';

            $availableLocales = $translationModel::where($foreignKey, $id)
                ->pluck('locale')
                ->toArray();

            // Get all available languages from the languages table
            $allLanguages = \App\Models\Language::active()->get(['code', 'name', 'native_name']);

            $result = $allLanguages->map(function($language) use ($availableLocales) {
                return [
                    'code' => $language->code,
                    'name' => $language->name,
                    'native_name' => $language->native_name,
                    'is_translated' => in_array($language->code, $availableLocales),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $result
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get available locales',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk update translations
     */
    public function bulkUpdateTranslations(Request $request, $model, $id): JsonResponse
    {
        $validator = Validator::make(array_merge($request->all(), ['model' => $model, 'id' => $id]), [
            'model' => 'required|in:product,property,vehicle',
            'id' => 'required|integer|min:1',
            'translations' => 'required|array',
            'translations.*.locale' => 'required|string|max:10',
            'translations.*.name' => 'required|string|max:255',
            'translations.*.description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $translationModel = $this->getTranslationModel($model);
            $foreignKey = $model . '_id';

            foreach ($request->translations as $translationData) {
                $existingTranslation = $translationModel::where($foreignKey, $id)
                    ->where('locale', $translationData['locale'])
                    ->first();

                if ($existingTranslation) {
                    $existingTranslation->update($translationData);
                } else {
                    $translationModel::create(array_merge($translationData, [$foreignKey => $id]));
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Translations updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update translations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get translation model class
     */
    private function getTranslationModel($model)
    {
        switch ($model) {
            case 'product':
                return ProductTranslation::class;
            case 'property':
                return PropertyTranslation::class;
            case 'vehicle':
                return VehicleTranslation::class;
            default:
                throw new \InvalidArgumentException('Invalid model type');
        }
    }
}
