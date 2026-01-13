<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function create()
    {
        return view('user.scan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'detected_items' => 'nullable|json',
            'ai_confidence' => 'nullable|numeric|min:0|max:100',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('scans', 'public');
        }

        $detectedItems = null;
        if ($request->detected_items) {
            $detectedItems = json_decode($request->detected_items, true);
        }

        $confidence = $validated['ai_confidence'] ?? 0;
        $status = 'pending';
        $adminNotes = null;

        if ($confidence < 90) {
            $status = 'rejected';
            $adminNotes = 'Ditolak otomatis oleh sistem: Akurasi AI terlalu rendah (< 90%). Silakan upload foto yang lebih jelas.';
        } elseif ($confidence >= 90) {
            $adminNotes = 'SYSTEM_VERIFIED: Akurasi AI tinggi (> 90%). Menunggu input berat dari admin.';
        }

        $scan = Scan::create([
            'user_id' => Auth::id(),
            'image_path' => $imagePath,
            'detected_items' => $detectedItems,
            'ai_result' => [
                'confidence' => $confidence,
                'timestamp' => now()->toIso8601String(),
            ],
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);

        if ($status === 'rejected') {
            return redirect()->route('user.scan.show', $scan)
                ->with('error', 'Scan ditolak otomatis karena akurasi AI rendah.');
        }

        if ($confidence >= 90) {
            return redirect()->route('user.scan.show', $scan)
                ->with('success', 'Scan terverifikasi otomatis oleh AI! Menunggu input berat dari admin.');
        }

        return redirect()->route('user.scan.show', $scan)
            ->with('success', 'Scan berhasil diupload! Menunggu konfirmasi admin.');
    }

    public function show(Scan $scan)
    {
        // Pastikan user hanya bisa melihat scan miliknya
        if ($scan->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $scan->load('confirmedBy');
        return view('user.scan.show', compact('scan'));
    }
}
