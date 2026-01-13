<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanConfirmationController extends Controller
{
    public function index()
    {
        $scans = Scan::where('status', 'pending')
            ->with('user')
            ->latest()
            ->paginate(10);

        $stats = [
            'pending' => Scan::where('status', 'pending')->count(),
            'approved' => Scan::where('status', 'approved')->count(),
            'rejected' => Scan::where('status', 'rejected')->count(),
        ];

        return view('admin.scans.index', compact('scans', 'stats'));
    }

    public function show(Scan $scan)
    {
        $scan->load('user', 'confirmedBy');
        return view('admin.scans.show', compact('scan'));
    }

    public function approve(Request $request, Scan $scan)
    {
        $validated = $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'poin' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $scan->update([
            'status' => 'approved',
            'total_weight' => $validated['weight'],
            'poin_earned' => $validated['poin'],
            'admin_notes' => $validated['notes'],
            'confirmed_by' => Auth::id(),
        ]);

        // Add poin to user using increment method (more safe)
        $scan->user->increment('poin', $validated['poin']);

        // Create Transaction Record
        \App\Models\Transaction::create([
            'user_id' => $scan->user_id,
            'tipe_transaksi' => 'setor',
            'jenis_sampah' => 'Scan Sampah AI', // Or specific type if available in detected_items
            'berat' => $validated['weight'],
            'poin' => $validated['poin'],
            'status' => 'approved',
            'created_at' => $scan->created_at, // Preserve scan timestamp or use now()
            'updated_at' => now(),
        ]);

        // Send Notification
        $scan->user->notify(new \App\Notifications\ScanStatusUpdated($scan, 'approved'));

        return redirect()->route('admin.scans.index')
            ->with('success', 'Scan berhasil dikonfirmasi dan poin telah ditambahkan!');
    }

    public function reject(Request $request, Scan $scan)
    {
        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $scan->update([
            'status' => 'rejected',
            'admin_notes' => $validated['reason'],
            'confirmed_by' => Auth::id(),
        ]);

        // Send Notification
        $scan->user->notify(new \App\Notifications\ScanStatusUpdated($scan, 'rejected'));

        return redirect()->route('admin.scans.index')
            ->with('success', 'Scan berhasil ditolak!');
    }
}
