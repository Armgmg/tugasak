<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Reward;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index()
    {
        $rewards = Reward::where('status', true)
            ->orderBy('poin_required')
            ->get();

        return view('marketplace', compact('rewards'));
    }
}
