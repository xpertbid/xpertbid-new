<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    /**
     * Display a listing of KYC documents.
     */
    public function index(Request $request)
    {
        $query = KycDocument::with(['user', 'reviewer']);

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('kyc_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by user name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $kycDocuments = $query->orderBy('created_at', 'desc')->paginate(15);

        $stats = [
            'total' => KycDocument::count(),
            'pending' => KycDocument::where('status', 'pending')->count(),
            'approved' => KycDocument::where('status', 'approved')->count(),
            'rejected' => KycDocument::where('status', 'rejected')->count(),
            'under_review' => KycDocument::where('status', 'under_review')->count(),
        ];

        $types = ['user', 'vendor', 'property', 'vehicle', 'auction'];
        $statuses = ['pending', 'approved', 'rejected', 'under_review'];

        return view('admin.kyc.index', compact('kycDocuments', 'stats', 'types', 'statuses'));
    }

    /**
     * Show the form for creating a new KYC document.
     */
    public function create()
    {
        $users = User::all();
        $types = ['user', 'vendor', 'property', 'vehicle', 'auction'];
        
        return view('admin.kyc.create', compact('users', 'types'));
    }

    /**
     * Store a newly created KYC document.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'kyc_type' => 'required|in:user,vendor,property,vehicle,auction',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kycDocument = KycDocument::create($request->all());

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC document created successfully.');
    }

    /**
     * Display the specified KYC document.
     */
    public function show(KycDocument $kycDocument)
    {
        $kycDocument->load(['user', 'reviewer']);
        
        return view('admin.kyc.show', compact('kycDocument'));
    }

    /**
     * Show the form for editing the specified KYC document.
     */
    public function edit(KycDocument $kycDocument)
    {
        $users = User::all();
        $types = ['user', 'vendor', 'property', 'vehicle', 'auction'];
        
        return view('admin.kyc.edit', compact('kycDocument', 'users', 'types'));
    }

    /**
     * Update the specified KYC document.
     */
    public function update(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'kyc_type' => 'required|in:user,vendor,property,vehicle,auction',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kycDocument->update($request->all());

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC document updated successfully.');
    }

    /**
     * Remove the specified KYC document.
     */
    public function destroy(KycDocument $kycDocument)
    {
        // Delete associated documents from storage
        if ($kycDocument->documents) {
            foreach ($kycDocument->documents as $document) {
                if (isset($document['path']) && Storage::exists($document['path'])) {
                    Storage::delete($document['path']);
                }
            }
        }

        $kycDocument->delete();

        return redirect()->route('admin.kyc.index')
            ->with('success', 'KYC document deleted successfully.');
    }

    /**
     * Approve a KYC document.
     */
    public function approve(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $kycDocument->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KYC document approved successfully.'
        ]);
    }

    /**
     * Reject a KYC document.
     */
    public function reject(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $kycDocument->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KYC document rejected successfully.'
        ]);
    }

    /**
     * Set KYC document under review.
     */
    public function underReview(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $kycDocument->update([
            'status' => 'under_review',
            'admin_notes' => $request->admin_notes,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'KYC document set under review successfully.'
        ]);
    }

    /**
     * Upload document for KYC.
     */
    public function uploadDocument(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'document' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $file = $request->file('document');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('kyc_documents', $fileName, 'public');

        $kycDocument->addDocument($filePath, $file->getClientOriginalName(), $file->getMimeType());

        return response()->json([
            'success' => true,
            'message' => 'Document uploaded successfully.',
            'file_path' => $filePath
        ]);
    }

    /**
     * Remove document from KYC.
     */
    public function removeDocument(Request $request, KycDocument $kycDocument)
    {
        $validator = Validator::make($request->all(), [
            'document_index' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $documents = $kycDocument->documents ?? [];
        $index = $request->document_index;

        if (isset($documents[$index])) {
            // Delete file from storage
            if (isset($documents[$index]['path']) && Storage::exists($documents[$index]['path'])) {
                Storage::delete($documents[$index]['path']);
            }

            // Remove from array
            unset($documents[$index]);
            $kycDocument->update(['documents' => array_values($documents)]);

            return response()->json([
                'success' => true,
                'message' => 'Document removed successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Document not found.'
        ], 404);
    }

    /**
     * Get KYC statistics.
     */
    public function getStats()
    {
        $stats = [
            'total' => KycDocument::count(),
            'pending' => KycDocument::where('status', 'pending')->count(),
            'approved' => KycDocument::where('status', 'approved')->count(),
            'rejected' => KycDocument::where('status', 'rejected')->count(),
            'under_review' => KycDocument::where('status', 'under_review')->count(),
            'by_type' => [
                'user' => KycDocument::where('kyc_type', 'user')->count(),
                'vendor' => KycDocument::where('kyc_type', 'vendor')->count(),
                'property' => KycDocument::where('kyc_type', 'property')->count(),
                'vehicle' => KycDocument::where('kyc_type', 'vehicle')->count(),
                'auction' => KycDocument::where('kyc_type', 'auction')->count(),
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
}