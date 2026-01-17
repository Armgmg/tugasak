<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use App\Models\Transaction;
use Illuminate\Http\Request;

class RewardController extends Controller
{
    public function index()
    {
        $rewards = Reward::where('status', true)
            ->orderBy('poin_required')
            ->get()
            ->map(function ($reward) {
                return [
                    'id' => $reward->id,
                    'name' => $reward->name,
                    'description' => $reward->description,
                    'poin_required' => $reward->poin_required,
                    'image' => $reward->image,
                    'image_url' => $reward->image ? url('img/' . $reward->image) : null,
                    'status' => $reward->status,
                    'created_at' => $reward->created_at,
                    'updated_at' => $reward->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $rewards
        ]);
    }

    public function redeem(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
        ]);

        $user = $request->user();
        $reward = Reward::findOrFail($request->reward_id);

        // Check if user has enough poin
        if ($user->poin < $reward->poin_required) {
            return response()->json([
                'success' => false,
                'message' => 'Poin tidak cukup untuk menukar reward ini',
                'data' => [
                    'current_poin' => $user->poin,
                    'required_poin' => $reward->poin_required,
                ]
            ], 400);
        }

        // Create redeem transaction
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'tipe_transaksi' => 'tukar',
            'jenis_sampah' => $reward->name,
            'berat' => 0,
            'reward' => $reward->name,
            'poin' => -$reward->poin_required,
            'status' => 'approved',
        ]);

        // Update user poin
        $user->poin -= $reward->poin_required;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Reward berhasil ditukar',
            'data' => [
                'transaction' => $transaction,
                'reward' => $reward,
                'remaining_poin' => $user->poin,
            ]
        ], 201);
    }
}
