<?php

namespace App\Http\Controllers;

use App\Models\KycDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the KYC form based on user role
     */
    public function index()
    {
        $user = Auth::user();
        $kycDocument = KycDocument::where('user_id', $user->id)->first();
        
        return view('kyc.index', compact('kycDocument', 'user'));
    }

    /**
     * Show the form for creating a new KYC document
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $kycType = $request->get('type', 'user');
        
        // Check if user already has a KYC document
        $existingKyc = KycDocument::where('user_id', $user->id)->first();
        if ($existingKyc) {
            return redirect()->route('kyc.index')->with('info', 'You already have a KYC document. You can edit it from your profile.');
        }

        return view('kyc.create', compact('kycType', 'user'));
    }

    /**
     * Store a newly created KYC document
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user already has a KYC document
        $existingKyc = KycDocument::where('user_id', $user->id)->first();
        if ($existingKyc) {
            return redirect()->route('kyc.index')->with('error', 'You already have a KYC document.');
        }

        $validator = $this->getValidationRules($request->kyc_type, $request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $kycData = $request->all();
        $kycData['user_id'] = $user->id;
        $kycData['status'] = 'pending';

        $kycDocument = KycDocument::create($kycData);

        return redirect()->route('kyc.index')
            ->with('success', 'KYC document submitted successfully! It will be reviewed by our team.');
    }

    /**
     * Display the specified KYC document
     */
    public function show(KycDocument $kycDocument)
    {
        // Ensure user can only view their own KYC document
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to KYC document.');
        }

        return view('kyc.show', compact('kycDocument'));
    }

    /**
     * Show the form for editing the specified KYC document
     */
    public function edit(KycDocument $kycDocument)
    {
        // Ensure user can only edit their own KYC document
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to KYC document.');
        }

        // Only allow editing if status is pending or rejected
        if (!in_array($kycDocument->status, ['pending', 'rejected'])) {
            return redirect()->route('kyc.index')->with('error', 'You cannot edit an approved KYC document.');
        }

        return view('kyc.edit', compact('kycDocument'));
    }

    /**
     * Update the specified KYC document
     */
    public function update(Request $request, KycDocument $kycDocument)
    {
        // Ensure user can only update their own KYC document
        if ($kycDocument->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to KYC document.');
        }

        // Only allow updating if status is pending or rejected
        if (!in_array($kycDocument->status, ['pending', 'rejected'])) {
            return redirect()->route('kyc.index')->with('error', 'You cannot edit an approved KYC document.');
        }

        $validator = $this->getValidationRules($kycDocument->kyc_type, $request->all());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Reset status to pending when user updates
        $updateData = $request->all();
        $updateData['status'] = 'pending';
        $updateData['rejection_reason'] = null; // Clear rejection reason

        $kycDocument->update($updateData);

        return redirect()->route('kyc.index')
            ->with('success', 'KYC document updated successfully! It will be reviewed again.');
    }

    /**
     * Upload document for KYC
     */
    public function uploadDocument(Request $request, KycDocument $kycDocument)
    {
        // Ensure user can only upload to their own KYC document
        if ($kycDocument->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

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
     * Remove document from KYC
     */
    public function removeDocument(Request $request, KycDocument $kycDocument)
    {
        // Ensure user can only remove from their own KYC document
        if ($kycDocument->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access.'
            ], 403);
        }

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
     * Get validation rules based on KYC type
     */
    private function getValidationRules($kycType, $data)
    {
        $rules = [
            'kyc_type' => 'required|in:user,vendor,property,vehicle,auction',
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
        ];

        // Add vendor-specific validation rules
        if ($kycType === 'vendor') {
            $rules = array_merge($rules, [
                'business_name' => 'required|string|max:255',
                'ntn_number' => 'required|string|max:50',
                'business_address' => 'required|string|max:500',
                'business_type' => 'nullable|string|max:100',
                'tax_number' => 'nullable|string|max:50',
                'business_registration_number' => 'nullable|string|max:100',
            ]);
        }

        // Add property-specific validation rules
        if ($kycType === 'property') {
            $rules = array_merge($rules, [
                'property_type' => 'nullable|string|max:100',
                'property_location' => 'nullable|string|max:255',
                'property_size' => 'nullable|string|max:100',
                'property_ownership' => 'nullable|string|max:100',
                'property_description' => 'nullable|string|max:1000',
            ]);
        }

        // Add vehicle-specific validation rules
        if ($kycType === 'vehicle') {
            $rules = array_merge($rules, [
                'vehicle_type' => 'nullable|string|max:100',
                'vehicle_make' => 'nullable|string|max:100',
                'vehicle_model' => 'nullable|string|max:100',
                'vehicle_year' => 'nullable|string|max:4',
                'vehicle_vin' => 'nullable|string|max:17',
                'vehicle_registration_number' => 'nullable|string|max:50',
            ]);
        }

        // Add auction-specific validation rules
        if ($kycType === 'auction') {
            $rules = array_merge($rules, [
                'auction_type' => 'nullable|string|max:100',
                'item_category' => 'nullable|string|max:100',
                'item_description' => 'nullable|string|max:1000',
                'item_condition' => 'nullable|string|max:100',
                'estimated_value' => 'nullable|string|max:100',
            ]);
        }

        return Validator::make($data, $rules);
    }

    /**
     * Get KYC types for selection
     */
    public function getKycTypes()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'name' => 'E-commerce User',
                    'description' => 'For users who want to buy products',
                    'required_fields' => ['full_name', 'email', 'phone_number'],
                    'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code']
                ],
                'vendor' => [
                    'name' => 'Vendor/Business',
                    'description' => 'For businesses who want to sell products',
                    'required_fields' => ['full_name', 'email', 'phone_number', 'business_name', 'ntn_number', 'business_address'],
                    'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'business_type', 'tax_number', 'business_registration_number']
                ],
                'property' => [
                    'name' => 'Property Seller',
                    'description' => 'For users who want to list properties',
                    'required_fields' => ['full_name', 'email', 'phone_number'],
                    'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'property_type', 'property_location', 'property_size', 'property_ownership', 'property_description']
                ],
                'vehicle' => [
                    'name' => 'Vehicle Seller',
                    'description' => 'For users who want to sell vehicles',
                    'required_fields' => ['full_name', 'email', 'phone_number'],
                    'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'vehicle_type', 'vehicle_make', 'vehicle_model', 'vehicle_year', 'vehicle_vin', 'vehicle_registration_number']
                ],
                'auction' => [
                    'name' => 'Auction Seller',
                    'description' => 'For users who want to list auction items',
                    'required_fields' => ['full_name', 'email', 'phone_number'],
                    'optional_fields' => ['address', 'city', 'state', 'country', 'postal_code', 'auction_type', 'item_category', 'item_description', 'item_condition', 'estimated_value']
                ]
            ]
        ]);
    }
}