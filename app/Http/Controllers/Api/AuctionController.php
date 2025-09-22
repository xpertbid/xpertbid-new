<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $auctions = DB::table('auctions')
                ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                ->join('products', 'auctions.product_id', '=', 'products.id')
                ->select('auctions.*', 'vendors.business_name', 'products.name as product_name')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $auctions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching auctions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'vendor_id' => 'required|exists:vendors,id',
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:english,reserve,buy_now,private',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'buy_now_price' => 'nullable|numeric|min:0',
            'bid_increment' => 'required|numeric|min:0.01',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'auto_extend' => 'boolean',
            'extend_minutes' => 'nullable|integer|min:1|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $auction = DB::table('auctions')->insertGetId([
                'tenant_id' => $request->tenant_id,
                'vendor_id' => $request->vendor_id,
                'product_id' => $request->product_id,
                'title' => $request->title,
                'description' => $request->description,
                'type' => $request->type,
                'starting_price' => $request->starting_price,
                'reserve_price' => $request->reserve_price,
                'buy_now_price' => $request->buy_now_price,
                'current_bid' => $request->starting_price,
                'bid_increment' => $request->bid_increment,
                'bid_count' => 0,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'scheduled',
                'is_featured' => $request->is_featured ?? false,
                'auto_extend' => $request->auto_extend ?? false,
                'extend_minutes' => $request->extend_minutes ?? 5,
                'images' => $request->images ? json_encode($request->images) : null,
                'terms_conditions' => $request->terms_conditions ? json_encode($request->terms_conditions) : null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Auction created successfully',
                'data' => ['id' => $auction]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating auction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $auction = DB::table('auctions')
                ->join('vendors', 'auctions.vendor_id', '=', 'vendors.id')
                ->join('products', 'auctions.product_id', '=', 'products.id')
                ->select('auctions.*', 'vendors.business_name', 'products.name as product_name')
                ->where('auctions.id', $id)
                ->first();
            
            if (!$auction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $auction
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching auction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|in:english,reserve,buy_now,private',
            'starting_price' => 'sometimes|required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'buy_now_price' => 'nullable|numeric|min:0',
            'bid_increment' => 'sometimes|required|numeric|min:0.01',
            'start_time' => 'sometimes|required|date',
            'end_time' => 'sometimes|required|date|after:start_time',
            'status' => 'sometimes|required|in:scheduled,active,ended,cancelled',
            'is_featured' => 'boolean',
            'auto_extend' => 'boolean',
            'extend_minutes' => 'nullable|integer|min:1|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = $request->only([
                'title', 'description', 'type', 'starting_price', 'reserve_price',
                'buy_now_price', 'bid_increment', 'start_time', 'end_time',
                'status', 'is_featured', 'auto_extend', 'extend_minutes'
            ]);
            
            if ($request->has('images')) {
                $updateData['images'] = json_encode($request->images);
            }
            
            if ($request->has('terms_conditions')) {
                $updateData['terms_conditions'] = json_encode($request->terms_conditions);
            }
            
            $updateData['updated_at'] = now();

            $updated = DB::table('auctions')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Auction updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating auction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $deleted = DB::table('auctions')->where('id', $id)->delete();

            if ($deleted) {
                return response()->json([
                    'success' => true,
                    'message' => 'Auction deleted successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting auction: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update auction status
     */
    public function updateStatus(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:scheduled,active,ended,cancelled'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $updateData = [
                'status' => $request->status,
                'updated_at' => now()
            ];

            // If ending auction, set end time to now
            if ($request->status === 'ended') {
                $updateData['end_time'] = now();
            }

            $updated = DB::table('auctions')->where('id', $id)->update($updateData);

            if ($updated) {
                return response()->json([
                    'success' => true,
                    'message' => 'Auction status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating auction status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get auction bids
     */
    public function getBids(string $id)
    {
        try {
            $bids = DB::table('bids')
                ->join('users', 'bids.user_id', '=', 'users.id')
                ->select('bids.*', 'users.name as bidder_name', 'users.email as bidder_email')
                ->where('bids.auction_id', $id)
                ->orderBy('bids.amount', 'desc')
                ->orderBy('bids.created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $bids
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching auction bids: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Place a bid
     */
    public function placeBid(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'is_proxy_bid' => 'boolean',
            'max_amount' => 'nullable|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get auction details
            $auction = DB::table('auctions')->where('id', $id)->first();
            
            if (!$auction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction not found'
                ], 404);
            }

            if ($auction->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction is not active'
                ], 400);
            }

            if (now() > $auction->end_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Auction has ended'
                ], 400);
            }

            // Check if bid amount is valid
            $minBid = $auction->current_bid + $auction->bid_increment;
            if ($request->amount < $minBid) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimum bid amount is {$minBid}"
                ], 400);
            }

            // Place the bid
            $bidId = DB::table('bids')->insertGetId([
                'tenant_id' => $auction->tenant_id,
                'auction_id' => $id,
                'user_id' => $request->user_id,
                'amount' => $request->amount,
                'is_proxy_bid' => $request->is_proxy_bid ?? false,
                'max_amount' => $request->max_amount,
                'bid_time' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update auction current bid and bid count
            DB::table('auctions')->where('id', $id)->update([
                'current_bid' => $request->amount,
                'bid_count' => DB::raw('bid_count + 1'),
                'updated_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully',
                'data' => ['bid_id' => $bidId]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error placing bid: ' . $e->getMessage()
            ], 500);
        }
    }
}
