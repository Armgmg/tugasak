<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\Request;

class ScanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'ai_result' => 'nullable|json',
            'detected_items' => 'nullable|json',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('scans', 'public');
        }

        $aiResult = $request->ai_result ? json_decode($request->ai_result, true) : null;
        $status = 'pending';
        $adminNotes = null;
        $detectedItems = $request->detected_items ? json_decode($request->detected_items, true) : null;

        // Auto-verify if confidence > 90%
        if ($aiResult && isset($aiResult['confidence']) && $aiResult['confidence'] > 0.90) {
            $adminNotes = 'SYSTEM_VERIFIED (Confidence: ' . ($aiResult['confidence'] * 100) . '%)';
            // Auto-populate detected items if empty
            if (!$detectedItems) {
                $detectedItems = [
                    [
                        'name' => $aiResult['label'],
                        'confidence' => $aiResult['confidence'] * 100 // Convert to percentage for view
                    ]
                ];
            }
        }
        // Handle array format if needed
        else if ($aiResult && isset($aiResult[0]['confidence']) && $aiResult[0]['confidence'] > 0.90) {
            $adminNotes = 'SYSTEM_VERIFIED';
            if (!$detectedItems) {
                $detectedItems = [
                    [
                        'name' => $aiResult[0]['label'],
                        'confidence' => $aiResult[0]['confidence'] * 100
                    ]
                ];
            }
        }

        $scan = Scan::create([
            'user_id' => $request->user()->id,
            'image_path' => $imagePath,
            'ai_result' => $aiResult,
            'detected_items' => $detectedItems,
            'status' => $status,
            'admin_notes' => $adminNotes,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Scan berhasil diupload. Menunggu konfirmasi admin.',
            'data' => [
                'id' => $scan->id,
                'status' => $scan->status,
            ]
        ], 201);
    }

    public function getScanStatus(Scan $scan)
    {
        if ($scan->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $scan->id,
                'image_path' => $scan->image_path ? asset('storage/' . $scan->image_path) : null,
                'status' => $scan->status,
                'total_weight' => $scan->total_weight,
                'poin_earned' => $scan->poin_earned,
                'detected_items' => $scan->detected_items,
                'admin_notes' => $scan->admin_notes,
                'created_at' => $scan->created_at,
                'confirmed_at' => $scan->updated_at,
            ]
        ]);
    }


    public function userScans(Request $request)
    {
        $scans = Scan::where('user_id', auth()->id())
            ->latest()
            ->get()
            ->map(function ($scan) {
                return [
                    'id' => $scan->id,
                    'image_path' => $scan->image_path ? asset('storage/' . $scan->image_path) : null,
                    'status' => $scan->status,
                    'total_weight' => $scan->total_weight,
                    'poin_earned' => $scan->poin_earned,
                    'detected_items' => $scan->detected_items,
                    'admin_notes' => $scan->admin_notes,
                    'created_at' => $scan->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $scans
        ]);
    }
}