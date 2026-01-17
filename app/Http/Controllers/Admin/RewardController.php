<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rewards = Reward::latest()->paginate(10);
        return view('admin.rewards.index', compact('rewards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.rewards.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:1',
            'image' => 'required|image|max:2048', // Max 2MB
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Use storage disk (persistent capable if configured, and safe for Direct Route)
            $path = $request->file('image')->store('rewards', 'public');
            // Store relative path 'rewards/filename' (OR prefix 'storage/' if consistent with others)
            // Let's check API controller... it expects 'img/' prefix currently. We will change that too.
            // For now let's store 'storage/rewards/filename' to be consistent with Scans.
            $validated['image'] = 'storage/' . $path;
        }

        // Default status to true if not present
        $validated['status'] = $request->has('status');

        Reward::create($validated);

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Reward berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reward $reward)
    {
        return view('admin.rewards.edit', compact('reward'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reward $reward)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'poin_required' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
            'status' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists in storage (handling legacy public/img or new storage/)
            if ($reward->image) {
                if (file_exists(public_path('img/' . $reward->image))) {
                    unlink(public_path('img/' . $reward->image));
                } elseif (str_starts_with($reward->image, 'storage/')) {
                    $oldPath = str_replace('storage/', '', $reward->image);
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $path = $request->file('image')->store('rewards', 'public');
            $validated['image'] = 'storage/' . $path;
        }

        // Handle checkbox for status
        $validated['status'] = $request->has('status');

        $reward->update($validated);

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Reward berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reward $reward)
    {
        if ($reward->image && str_starts_with($reward->image, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $reward->image);
            Storage::disk('public')->delete($oldPath);
        }

        $reward->delete();

        return redirect()->route('admin.rewards.index')
            ->with('success', 'Reward berhasil dihapus!');
    }
}
