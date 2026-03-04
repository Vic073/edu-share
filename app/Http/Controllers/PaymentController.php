<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display the pricing page.
     */
    public function pricing()
    {
        return view('payments.pricing');
    }

    /**
     * Initiate a payment via Paychangu Gateway.
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'plan_duration' => 'required|in:1_month,4_months,12_months',
        ]);

        $user = Auth::user();
        
        // Define pricing structure based on SRS
        $prices = [
            '1_month' => 2500,
            '4_months' => 8000,
            '12_months' => 20000,
        ];
        
        $amount = $prices[$request->plan_duration];
        $reference = 'EDUSHARE_' . strtoupper(uniqid());

        // Create a pending wallet transaction log
        WalletTransaction::create([
            'user_id' => $user->id,
            'amount' => $amount,
            'type' => 'subscription_' . $request->plan_duration,
            'reference' => $reference,
            'status' => 'pending'
        ]);

        // API Integration with Paychangu
        /* 
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('PAYCHANGU_SECRET_KEY'),
        ])->post('https://api.paychangu.com/payment', [
            'amount' => $amount,
            'currency' => 'MWK',
            'email' => $user->email,
            'tx_ref' => $reference,
            'callback_url' => route('payment.callback'),
            'return_url' => route('payment.success'),
            'customization' => [
                'title' => 'EduShare Premium Subscription',
            ]
        ]); 
        
        if ($response->successful()) {
            return redirect($response->json('data.checkout_url'));
        }
        */

        // MOCK PAYCHANGU REDIRECT FOR NOW (Since API keys are not provided yet)
        return redirect()->route('payment.mock_checkout', ['ref' => $reference, 'amount' => $amount, 'plan' => $request->plan_duration]);
    }

    /**
     * Mock view for when Paychangu is simulated
     */
    public function mockCheckout(Request $request)
    {
        $data = $request->only(['ref', 'amount', 'plan']);
        return view('payments.mock_checkout', $data);
    }

    /**
     * Simulated Webhook Endpoint to securely process payments
     */
    public function webhook(Request $request)
    {
        // 1. In production, verify Paychangu signature header here
        
        $payload = $request->all();
        $reference = $payload['data']['tx_ref'] ?? $request->input('ref');
        $status = $payload['data']['status'] ?? 'successful'; // simulated

        if ($status !== 'successful') {
            Log::warning("Paychangu payment failed for ref: {$reference}");
            return response()->json(['status' => 'acknowledged, but failed.']);
        }

        $transaction = WalletTransaction::where('reference', $reference)->where('status', 'pending')->first();

        if (!$transaction) {
            return response()->json(['status' => 'transaction already processed or not found.']);
        }

        // Mark Transaction success
        $transaction->update(['status' => 'success']);

        // Determine subscription length
        $durationMap = [
            'subscription_1_month' => 30,
            'subscription_4_months' => 120,
            'subscription_12_months' => 365,
        ];

        $days = $durationMap[$transaction->type] ?? 30;

        // Create or Extend Subscription for the user
        $subscription = Subscription::firstOrNew(['user_id' => $transaction->user_id]);
        
        $newExpiry = now()->addDays($days);
        if ($subscription->expires_at && $subscription->expires_at->isFuture()) {
            $newExpiry = $subscription->expires_at->addDays($days);
        }

        $subscription->plan = 'premium';
        $subscription->starts_at = now();
        $subscription->expires_at = $newExpiry;
        $subscription->status = 'active';
        $subscription->payment_ref = $reference;
        $subscription->save();

        // Update User Tier
        $transaction->user->update(['subscription_tier' => 'premium']);

        return response()->json(['status' => 'success']);
    }

    /**
     * User redirected here upon successful payment return
     */
    public function success(Request $request)
    {
        // Call webhook directly since we are mocking it via frontend button
        if ($request->has('simulate_webhook')) {
            $this->webhook($request);
        }
        
        return redirect()->route('student.dashboard')->with('success', 'Payment successful! Welcome to EduShare Premium.');
    }
}
