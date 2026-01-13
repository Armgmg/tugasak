<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class RewardController extends Controller
{
    // GET /api/admin/rewards
    public function index()
    {
        $rewards = Reward::latest()->get();
        return response()->json([
            'success' => true,
            'data' => $rewards
        ]);
    }

    // POST /api/admin/rewards
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:1',
            'image' => 'required|image|max:2048', // Max 2MB
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Upload Image
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('rewards', 'public');
        }

        $reward = Reward::create([
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'poin_required' => $request->poin_required,
            'image' => $imagePath ? '/storage/' . $imagePath : null,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reward created successfully',
            'data' => $reward
        ], 201);
    }

    // GET /api/admin/rewards/{id}
    public function show($id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found'], 404);
        }

        return response()->json(['success' => true, 'data' => $reward]);
    }

    // POST /api/admin/rewards/{id}/update (Using POST for file upload ease)
    public function update(Request $request, $id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = [
            'name' => $request->name,
            'category' => $request->category,
            'description' => $request->description,
            'poin_required' => $request->poin_required,
            'status' => $request->status,
        ];

        // Handle Image Upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($reward->image) {
                $oldPath = str_replace('/storage/', '', $reward->image);
                Storage::disk('public')->delete($oldPath);
            }

            $imagePath = $request->file('image')->store('rewards', 'public');
            $data['image'] = '/storage/' . $imagePath;
        }

        $reward->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Reward updated successfully',
            'data' => $reward
        ]);
    }

    // DELETE /api/admin/rewards/{id}
    public function destroy($id)
    {
        $reward = Reward::find($id);

        if (!$reward) {
            return response()->json(['success' => false, 'message' => 'Reward not found'], 404);
        }

        // Delete image
        if ($reward->image) {
            $oldPath = str_replace('/storage/', '', $reward->image);
            Storage::disk('public')->delete($oldPath);
        }

        $reward->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reward deleted successfully'
        ]);
    }
}
