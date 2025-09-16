<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PayPalController extends Controller
{
    public function paypalSuccess(Request $request, PayPalService $paypal)
    {
        $orderId = $request->query('token');
        $result = $paypal->captureOrder($orderId);

        if ($result['status'] === 'COMPLETED') {
            $payment = Payment::where('paypal_order_id', $orderId)
                            ->where('status', 'pending')
                            ->first();

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Update payment
            $payment->update(['status' => 'paid']);

            // Update related booking
            $payment->payable->update(['payment_status' => 'paid']);

            return view('payments.success', [
                'booking' => $payment->payable
            ]);
            // return response()->json([
            //     'message' => 'Payment successful',
            //     'booking' => $payment->payable  // could be TourBooking, PackageBooking, etc.
            // ]);
        }
        Log::info('PayPal success error:', $result);
        return view('payments.failed', ['message' => 'Payment failed']);
        // return response()->json(['message' => 'Payment failed'], 500);
    }

    public function paypalCancel(Request $request)
    {
        $orderId = $request->query('token'); // PayPal sends token = orderId

        if ($orderId) {
            $payment = Payment::where('paypal_order_id', $orderId)
                ->where('status', 'pending') // only update if not already processed
                ->first();

            if ($payment) {
                // Update payment status
                $payment->update(['status' => 'cancelled']);

                // Update related booking
                $payment->payable->update(['payment_status' => 'cancelled']);
            }
        }

        return view('payments.failed', [
            'message' => 'Payment failed'
        ]);
    }


}
