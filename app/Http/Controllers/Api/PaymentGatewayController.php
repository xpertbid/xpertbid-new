<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentGatewayController extends Controller
{
    /**
     * Get available payment gateways
     */
    public function getGateways(Request $request)
    {
        try {
            $gateways = PaymentGateway::where('tenant_id', $request->tenant_id)
                ->active()
                ->orderBy('sort_order')
                ->get();

            // Filter by supported currency and country if provided
            if ($request->currency) {
                $gateways = $gateways->filter(function ($gateway) use ($request) {
                    return $gateway->supportsCurrency($request->currency);
                });
            }

            if ($request->country) {
                $gateways = $gateways->filter(function ($gateway) use ($request) {
                    return $gateway->supportsCountry($request->country);
                });
            }

            return response()->json([
                'success' => true,
                'data' => $gateways->values()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching payment gateways: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Process payment
     */
    public function processPayment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tenant_id' => 'required|exists:tenants,id',
            'gateway_id' => 'required|exists:payment_gateways,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'required|string|size:3',
            'payment_data' => 'required|array',
            'order_id' => 'nullable|exists:orders,id',
            'customer_id' => 'nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $gateway = PaymentGateway::find($request->gateway_id);

            // Check if gateway supports the currency
            if (!$gateway->supportsCurrency($request->currency)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gateway does not support the specified currency'
                ], 400);
            }

            // Calculate gateway fee
            $fee = $gateway->calculateFee($request->amount);

            // Process payment through gateway
            $paymentResult = $gateway->processPayment(
                $request->amount,
                $request->currency,
                $request->payment_data
            );

            // Create payment record
            $payment = \App\Models\Payment::create([
                'tenant_id' => $request->tenant_id,
                'order_id' => $request->order_id,
                'user_id' => $request->customer_id,
                'gateway_id' => $gateway->id,
                'payment_id' => $paymentResult['transaction_id'],
                'gateway' => $gateway->code,
                'method' => $request->payment_data['method'] ?? 'card',
                'amount' => $request->amount,
                'currency' => $request->currency,
                'fee' => $fee,
                'status' => $paymentResult['success'] ? 'completed' : 'failed',
                'gateway_response' => $paymentResult,
                'transaction_id' => $paymentResult['transaction_id'],
            ]);

            return response()->json([
                'success' => $paymentResult['success'],
                'message' => $paymentResult['success'] ? 'Payment processed successfully' : 'Payment failed',
                'data' => [
                    'payment_id' => $payment->id,
                    'transaction_id' => $paymentResult['transaction_id'],
                    'amount' => $request->amount,
                    'currency' => $request->currency,
                    'fee' => $fee,
                    'gateway_response' => $paymentResult,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing payment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get payment gateway details
     */
    public function getGatewayDetails(Request $request, $gatewayId)
    {
        try {
            $gateway = PaymentGateway::where('id', $gatewayId)
                ->where('tenant_id', $request->tenant_id)
                ->first();

            if (!$gateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment gateway not found'
                ], 404);
            }

            // Remove sensitive information
            $gatewayData = $gateway->toArray();
            unset($gatewayData['settings']);

            return response()->json([
                'success' => true,
                'data' => $gatewayData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching gateway details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle payment webhook
     */
    public function handleWebhook(Request $request, $gatewayCode)
    {
        try {
            $gateway = PaymentGateway::where('code', $gatewayCode)
                ->where('tenant_id', $request->tenant_id)
                ->first();

            if (!$gateway) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gateway not found'
                ], 404);
            }

            // Process webhook based on gateway
            switch ($gatewayCode) {
                case 'paypal':
                    return $this->handlePayPalWebhook($request, $gateway);
                case 'stripe':
                    return $this->handleStripeWebhook($request, $gateway);
                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Webhook not supported for this gateway'
                    ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing webhook: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle PayPal webhook
     */
    private function handlePayPalWebhook(Request $request, PaymentGateway $gateway)
    {
        // Implement PayPal webhook processing
        return response()->json([
            'success' => true,
            'message' => 'PayPal webhook processed'
        ]);
    }

    /**
     * Handle Stripe webhook
     */
    private function handleStripeWebhook(Request $request, PaymentGateway $gateway)
    {
        // Implement Stripe webhook processing
        return response()->json([
            'success' => true,
            'message' => 'Stripe webhook processed'
        ]);
    }
}
