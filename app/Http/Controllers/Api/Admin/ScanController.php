<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $query = Scan::with('user')->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $scans = $query->paginate(10);

        $stats = [
            'pending' => Scan::where('status', 'pending')->count(),
            'approved' => Scan::where('status', 'approved')->count(),
            'rejected' => Scan::where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $scans,
            'stats' => $stats
        ]);
    }

    public function show($id)
    {
        $scan = Scan::with(['user', 'confirmedBy'])->find($id);

        if (!$scan) {
            return response()->json([
                'success' => false,
                'message' => 'Scan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $scan
        ]);
    }

    public function approve(Request $request, $id)
    {
        $scan = Scan::with('user')->find($id);

        if (!$scan) {
            return response()->json([
                'success' => false,
                'message' => 'Scan not found'
            ], 404);
        }

        if ($scan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya scan dengan status pending yang bisa disetujui.'
            ], 400);
        }

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
            'confirmed_by' => Auth::id() ?? $request->user()->id, // Fallback if Auth::id not set in API context, though bearer token should set it.
        ]);

        // Add poin to user
        $scan->user->increment('poin', $validated['poin']);

        return response()->json([
            'success' => true,
            'message' => 'Scan berhasil dikonfirmasi dan poin ditambahkan',
            'data' => $scan
        ]);
    }

    public function reject(Request $request, $id)
    {
        $scan = Scan::find($id);

        if (!$scan) {
            return response()->json([
                'success' => false,
                'message' => 'Scan not found'
            ], 404);
        }

        if ($scan->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya scan dengan status pending yang bisa ditolak.'
            ], 400);
        }

        $validated = $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        $scan->update([
            'status' => 'rejected',
            'admin_notes' => $validated['reason'],
            'confirmed_by' => Auth::id() ?? $request->user()->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scan berhasil ditolak',
            'data' => $scan
        ]);
    }
}
