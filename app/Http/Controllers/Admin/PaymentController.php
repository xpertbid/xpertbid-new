<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of payment gateways.
     */
    public function index()
    {
        // Mock data for payment gateways
        $paymentGateways = [
            [
                'id' => 1,
                'name' => 'Stripe',
                'slug' => 'stripe',
                'description' => 'Accept payments online with Stripe',
                'is_active' => true,
                'is_test_mode' => true,
                'logo' => 'https://stripe.com/img/v3/home/social.png',
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD'],
                'fees' => '2.9% + 30¢ per transaction',
            ],
            [
                'id' => 2,
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'Accept payments via PayPal',
                'is_active' => false,
                'is_test_mode' => true,
                'logo' => 'https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_37x23.jpg',
                'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD'],
                'fees' => '2.9% + fixed fee per transaction',
            ],
            [
                'id' => 3,
                'name' => 'Razorpay',
                'slug' => 'razorpay',
                'description' => 'Accept payments in India with Razorpay',
                'is_active' => false,
                'is_test_mode' => true,
                'logo' => 'https://razorpay.com/assets/razorpay-logo.svg',
                'supported_currencies' => ['INR'],
                'fees' => '2% per transaction',
            ],
            [
                'id' => 4,
                'name' => 'Square',
                'slug' => 'square',
                'description' => 'Accept payments with Square',
                'is_active' => false,
                'is_test_mode' => true,
                'logo' => 'https://squareup.com/us/en/sites/default/files/square-logo.svg',
                'supported_currencies' => ['USD', 'CAD', 'GBP', 'AUD'],
                'fees' => '2.9% + 30¢ per transaction',
            ],
        ];

        return view('admin.payments.index', compact('paymentGateways'));
    }

    /**
     * Show the form for configuring a payment gateway.
     */
    public function configure($gateway)
    {
        $gatewayConfig = [
            'stripe' => [
                'name' => 'Stripe',
                'fields' => [
                    'publishable_key' => ['label' => 'Publishable Key', 'type' => 'text', 'required' => true],
                    'secret_key' => ['label' => 'Secret Key', 'type' => 'password', 'required' => true],
                    'webhook_secret' => ['label' => 'Webhook Secret', 'type' => 'password', 'required' => false],
                    'test_mode' => ['label' => 'Test Mode', 'type' => 'checkbox', 'required' => false],
                ]
            ],
            'paypal' => [
                'name' => 'PayPal',
                'fields' => [
                    'client_id' => ['label' => 'Client ID', 'type' => 'text', 'required' => true],
                    'client_secret' => ['label' => 'Client Secret', 'type' => 'password', 'required' => true],
                    'sandbox_mode' => ['label' => 'Sandbox Mode', 'type' => 'checkbox', 'required' => false],
                ]
            ],
            'razorpay' => [
                'name' => 'Razorpay',
                'fields' => [
                    'key_id' => ['label' => 'Key ID', 'type' => 'text', 'required' => true],
                    'key_secret' => ['label' => 'Key Secret', 'type' => 'password', 'required' => true],
                    'test_mode' => ['label' => 'Test Mode', 'type' => 'checkbox', 'required' => false],
                ]
            ],
            'square' => [
                'name' => 'Square',
                'fields' => [
                    'application_id' => ['label' => 'Application ID', 'type' => 'text', 'required' => true],
                    'access_token' => ['label' => 'Access Token', 'type' => 'password', 'required' => true],
                    'sandbox_mode' => ['label' => 'Sandbox Mode', 'type' => 'checkbox', 'required' => false],
                ]
            ],
        ];

        if (!isset($gatewayConfig[$gateway])) {
            abort(404, 'Payment gateway not found');
        }

        $config = $gatewayConfig[$gateway];
        
        return view('admin.payments.configure', compact('gateway', 'config'));
    }

    /**
     * Store payment gateway configuration.
     */
    public function storeConfig(Request $request, $gateway)
    {
        // Here you would typically save the configuration to database
        // For now, we'll just redirect back with success message
        
        return redirect()->route('admin.payments.index')
                        ->with('success', ucfirst($gateway) . ' configuration saved successfully.');
    }

    /**
     * Toggle payment gateway status.
     */
    public function toggleStatus($gateway)
    {
        // Here you would typically update the gateway status in database
        // For now, we'll just redirect back with success message
        
        return redirect()->route('admin.payments.index')
                        ->with('success', ucfirst($gateway) . ' status updated successfully.');
    }
}