<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;

class TransactionController extends Controller
{
    // LIST TRANSAKSI
    public function index()
    {
        $transactions = Transaction::with('user')
            ->latest()
            ->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    // APPROVE TRANSAKSI
    public function approve($id)
    {
        $transaction = Transaction::with('user')->findOrFail($id);

        // Cegah double approve
        if ($transaction->status === 'approved') {
            return back()->with('error', 'Transaksi sudah disetujui.');
        }

        // Tambahkan poin ke user
        $transaction->user->increment('poin', $transaction->poin);

        // Update status transaksi
        $transaction->update([
            'status' => 'approved'
        ]);

        return back()->with('success', 'Transaksi berhasil disetujui.');
    }

    // REJECT TRANSAKSI
    public function reject($id)
    {
        $transaction = Transaction::findOrFail($id);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Transaksi tidak bisa ditolak.');
        }

        $transaction->update([
            'status' => 'rejected'
        ]);

        return back()->with('success', 'Transaksi ditolak.');
    }

    // RIWAYAT TRANSAKSI DENGAN FILTER STATUS
    public function riwayat()
    {
        $approved = Transaction::with('user')
            ->where('status', 'approved')
            ->latest()
            ->get();

        $rejected = Transaction::with('user')
            ->where('status', 'rejected')
            ->latest()
            ->get();

        $pendingTransactions = Transaction::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingScans = \App\Models\Scan::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        $rejectedScans = \App\Models\Scan::with('user')
            ->where('status', 'rejected')
            ->latest()
            ->get();

        // Gabungkan transaction dan scans untuk view, atau kirim terpisah

        return view('admin.transactions.riwayat', compact('approved', 'rejected', 'pendingTransactions', 'pendingScans', 'rejectedScans'));
    }
}
