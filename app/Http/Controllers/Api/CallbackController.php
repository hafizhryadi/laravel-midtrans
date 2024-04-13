<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey        = config("services.midtrans.serverKey");
        \Midtrans\Config::$isProduction     = config("services.midtrans.isProduction");
        \Midtrans\Config::$isSanitized      = config("services.midtrans.isSanitized");
        \Midtrans\Config::$is3ds            = config("services.midtrans.is#ds");
    }

    public function index(Request $request)
    {
        $payload    = $request->getContent();
        $notification = json_decode($payload);

        $validSignatureKey = hash("sha512", $notification->order_id . $notification->status_code . $notification->gross_amount . config("services.midtrans.serverKey"));
        if ($notification->signature_key != $validSignatureKey) {
            return response(['message' => 'Invalid signature'],403);
        }

        $transaction = $notification->transaction_status;
        $type = $notification->payment_type;
        $orderId = $notification->order_id;
        $fraud = $notification->fraud_status;

        $data_donation = Donation::where('invoice', $orderId)->first();

        if ($transaction == 'capture') {
            if ($type == 'credit_card') {
                if ($fraud == 'challenge') {
                    $data_donation->update([
                        'status' => 'Pending'
                    ]);
                } else {
                    $data_donation->update([
                        'status' => 'Success'
                    ]);
                }
            }
        } elseif ($transaction == 'settlement') {
            $data_donation->update([
                'status' => 'Success'
            ]);
        } elseif ($transaction == 'pending') {
            $data_donation->update([
                'status' => 'Pending'
            ]);
        } elseif ($transaction == 'deny') {
            $data_donation->update([
                'status'=> 'Failed'
            ]);
        } elseif ($transaction == 'expire') {
            $data_donation->update([
                'status'=> 'Expired'
            ]);
        } elseif ($transaction == 'cancel') {
            $data_donation->update([
                'status'=> 'Failed'
            ]);
        }
    }
}
