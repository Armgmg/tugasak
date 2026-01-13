<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        $transactionCount = \App\Models\Transaction::where('user_id', $user->id)->count();

        $totalWeight = \App\Models\Transaction::where('user_id', $user->id)
            ->where('status', 'approved')
            ->where('tipe_transaksi', 'setor')
            ->sum('berat');

        $carbonSaved = number_format($totalWeight * 2.35, 1, ',', '.');
        $totalWeight = number_format($totalWeight, 1, ',', '.');

        return view('profile', compact('user', 'transactionCount', 'totalWeight', 'carbonSaved'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20'],
            'location' => ['nullable', 'string', 'max:255'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->phone_number = $validated['phone'];
        $user->address = $validated['location'];

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }
}
