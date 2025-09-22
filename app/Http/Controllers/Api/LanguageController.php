<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LanguageController extends Controller
{
    /**
     * Display a listing of languages
     */
    public function index(Request $request): JsonResponse
    {
        $query = Language::query();

        // Filter by active status
        if ($request->has('active_only') && $request->active_only) {
            $query->active();
        }

        // Search by name or code
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('native_name', 'like', "%{$search}%");
            });
        }

        $languages = $query->orderBy('sort_order')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $languages->map(function($language) {
                return [
                    'id' => $language->id,
                    'name' => $language->name,
                    'code' => $language->code,
                    'native_name' => $language->native_name,
                    'flag_url' => $language->flag_url,
                    'direction' => $language->direction,
                    'is_rtl' => $language->direction === 'rtl',
                    'is_active' => $language->is_active,
                    'is_default' => $language->is_default,
                    'sort_order' => $language->sort_order,
                    'created_at' => $language->created_at,
                    'updated_at' => $language->updated_at,
                ];
            })
        ]);
    }

    /**
     * Store a newly created language
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'native_name' => 'required|string|max:255',
            'flag' => 'nullable|string|max:500',
            'is_rtl' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $language = Language::create($request->all());

            // If this is set as default, remove default from others
            if ($request->is_default) {
                $language->setAsDefault();
            }

            return response()->json([
                'success' => true,
                'message' => 'Language created successfully',
                'data' => $language
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create language',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified language
     */
    public function show(Language $language): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $language->id,
                'name' => $language->name,
                'code' => $language->code,
                'native_name' => $language->native_name,
                'flag_url' => $language->flag_url,
                'direction' => $language->direction,
                'is_rtl' => $language->direction === 'rtl',
                'is_active' => $language->is_active,
                'is_default' => $language->is_default,
                'sort_order' => $language->sort_order,
                'created_at' => $language->created_at,
                'updated_at' => $language->updated_at,
            ]
        ]);
    }

    /**
     * Update the specified language
     */
    public function update(Request $request, Language $language): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:10|unique:languages,code,' . $language->id,
            'native_name' => 'sometimes|required|string|max:255',
            'flag' => 'nullable|string|max:500',
            'is_rtl' => 'boolean',
            'is_active' => 'boolean',
            'is_default' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $language->update($request->all());

            // If this is set as default, remove default from others
            if ($request->has('is_default') && $request->is_default) {
                $language->setAsDefault();
            }

            return response()->json([
                'success' => true,
                'message' => 'Language updated successfully',
                'data' => $language
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update language',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified language
     */
    public function destroy(Language $language): JsonResponse
    {
        try {
            // Don't allow deleting default language
            if ($language->is_default) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete default language'
                ], 400);
            }

            $language->delete();

            return response()->json([
                'success' => true,
                'message' => 'Language deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete language',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update language status
     */
    public function updateStatus(Request $request, $id): JsonResponse
    {
        try {
            $language = Language::findOrFail($id);
            $language->update(['is_active' => !$language->is_active]);

            return response()->json([
                'success' => true,
                'message' => 'Language status updated successfully',
                'data' => $language
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update language status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set language as default
     */
    public function setDefault(Request $request, $id): JsonResponse
    {
        try {
            $language = Language::findOrFail($id);
            $language->setAsDefault();

            return response()->json([
                'success' => true,
                'message' => 'Default language updated successfully',
                'data' => $language
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default language',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active languages for frontend
     */
    public function getActive(): JsonResponse
    {
        $languages = Language::active()->orderBy('sort_order')->get();

        return response()->json([
            'success' => true,
            'data' => $languages->map(function($language) {
                return [
                    'id' => $language->id,
                    'name' => $language->name,
                    'code' => $language->code,
                    'native_name' => $language->native_name,
                    'flag_url' => $language->flag_url,
                    'is_rtl' => $language->direction === 'rtl',
                    'is_default' => $language->is_default,
                ];
            })
        ]);
    }
}
