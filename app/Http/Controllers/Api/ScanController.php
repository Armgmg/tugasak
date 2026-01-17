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
        $status = 'pending'; // Default
        $adminNotes = null;
        $detectedItems = $request->detected_items ? json_decode($request->detected_items, true) : null;

        // Auto-verify/reject logic
        if ($aiResult && isset($aiResult['confidence'])) {
            $conf = $aiResult['confidence'];
            // Normalize detected items
            if (!$detectedItems) {
                $detectedItems = [
                    [
                        'name' => $aiResult['label'],
                        'confidence' => $conf * 100
                    ]
                ];
            }

            if ($conf > 0.90) {
                $adminNotes = 'SYSTEM_VERIFIED (Confidence: ' . ($conf * 100) . '%)';
                // Status remains pending but marked as verified for admin input of weight
            } else {
                $status = 'rejected';
                $adminNotes = 'AUTO_REJECTED_LOW_CONFIDENCE (Confidence: ' . ($conf * 100) . '%)';
            }
        }
        // Handle array format if needed
        else if ($aiResult && isset($aiResult[0]['confidence'])) {
            $conf = $aiResult[0]['confidence'];
            if (!$detectedItems) {
                $detectedItems = [
                    [
                        'name' => $aiResult[0]['label'],
                        'confidence' => $conf * 100
                    ]
                ];
            }

            if ($conf > 0.90) {
                $adminNotes = 'SYSTEM_VERIFIED';
            } else {
                $status = 'rejected';
                $adminNotes = 'AUTO_REJECTED_LOW_CONFIDENCE';
            }
        }

        // Store relative path 'scans/filename.jpg' but ensure usage handles it
        // We will FORCE 'storage/' prefix here to ensure asset() works in blade if simpler
        if ($imagePath && !str_starts_with($imagePath, 'storage/')) {
            $imagePath = 'storage/' . $imagePath;
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