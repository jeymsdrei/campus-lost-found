<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\LostItem;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $recentLostItems = LostItem::with('user')
            ->where('status', '!=', 'resolved')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $recentFoundItems = FoundItem::with('user')
            ->where('status', '!=', 'claimed')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $stats = [
            'totalLost' => LostItem::count(),
            'totalFound' => FoundItem::count(),
            'unclaimed' => FoundItem::where('status', 'unclaimed')->count(),
            'pendingClaims' => \App\Models\ClaimRequest::where('status', 'pending')->count(),
        ];

        return view('home', compact('recentLostItems', 'recentFoundItems', 'stats'));
    }
}
