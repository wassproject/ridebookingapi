<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\RechargeHistory;
use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Str;

class DriverWalletController extends Controller
{
     public function balance()
    {
        $driver = auth()->user(); // Assuming driver is logged in via Sanctum
        return response()->json([
            'status' => true,
            'wallet_balance' => $driver->wallet_balance
        ]);
    }

    // 📌 Add money to wallet
    public function addMoney(Request $request)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:1',
            'preset' => 'nullable|in:100,200,500,1000'
        ]);

        if (!$request->amount && !$request->preset) {
            return response()->json([
                'status' => false,
                'message' => 'Please provide an amount or select a preset value'
            ], 400);
        }

        // Final amount
        $finalAmount = $request->preset ?? $request->amount;

        $driver = auth('driver-api')->user();

        // Update wallet
        $driver->wallet_balance += $finalAmount;
        $driver->save();

        // Create recharge record
        $transactionNo = Str::upper(Str::random(16));
        RechargeHistory::create([
            'driver_id'      => $driver->id,
            'amount'         => $finalAmount,
            'status'         => 'Success',
            'transaction_no' => $transactionNo,
            'payment_method' => 'Online'
        ]);

        // Fetch all history for the driver
        $history = RechargeHistory::where('driver_id', $driver->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Money added successfully',
            'added_amount' => $finalAmount,
            'wallet_balance' => $driver->wallet_balance,
            'recharge_history' => $history
        ]);
    }
}
