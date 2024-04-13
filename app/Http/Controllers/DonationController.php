<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Midtrans\Snap;
use Illuminate\Support\Str;

class DonationController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey        = config("services.midtrans.serverKey");
        \Midtrans\Config::$isProduction     = config("services.midtrans.isProduction");
        \Midtrans\Config::$isSanitized      = config("services.midtrans.isSanitized");
        \Midtrans\Config::$is3ds            = config("services.midtrans.is#ds");
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $donations = Donation::latest()->paginate(10);
        return view("donations.index", compact("donations"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("donations.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "amount" => "required",
            "note" => "required",
        ]);

        $donation = Donation::create([
            'invoice'   => 'INV-' . Str::upper(Str::random(5)),
            'name'      => $request->name,
            'email'     => $request->email,
            'amount'    => $request->amount,
            'note'      => $request->note,
            'status'    => 'Pending',
        ]);

        $payload = [
            'transaction_details' => [
                'order_id' => $donation->invoice,
                'gross_amount' => $donation->amount,
            ],
            'customer_details' => [
                'firt_name' => $donation->name,
                'email' => $request->email,
            ]
        ];

        // create snap token 
        $snapToken = Snap::getSnapToken($payload);
        $donation->snap_token = $snapToken;
        $donation->save();

        if ($donation) {
            return redirect()->route('donations.index')->with('success','donation added');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
