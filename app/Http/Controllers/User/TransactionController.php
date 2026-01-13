<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function create()
    {
        return view('user.transaksi.create');
    }

    public function store(Request $request)
    {

    }

    public function tukar(Request $request)
    {
        $request->validate([
            'reward_id' => 'required|exists:rewards,id',
            'contact_info' => 'required|string|max:255',
        ]);

        $user = \Illuminate\Support\Facades\Auth::user();
        $reward = \App\Models\Reward::findOrFail($request->reward_id);

        if ($user->poin < $reward->poin_required) {
            return back()->with('error', 'Poin tidak cukup!');
        }


        $user->poin -= $reward->poin_required;
        $user->save();


        \App\Models\Transaction::create([
            'user_id' => $user->id,
            'tipe_transaksi' => 'tukar',
            'jenis_sampah' => $reward->name . ' (' . $request->contact_info . ')',
            'berat' => 0,
            'reward' => $reward->name,
            'poin' => -$reward->poin_required,
            'status' => 'approved',
        ]);

        return redirect()->route('riwayat-transaksi')->with('success', 'Berhasil menukar ' . $reward->name . '. Kami akan menghubungi Anda di ' . $request->contact_info);
    }
}
