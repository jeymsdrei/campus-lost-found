<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use App\Models\FoundItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ClaimController extends Controller
{
    public function create(FoundItem $foundItem): View
    {
        if ($foundItem->status === 'claimed') {
            return redirect()->route('found-items.show', $foundItem)
                ->with('error', 'This item has already been claimed.');
        }

        $hasClaimed = ClaimRequest::where('found_item_id', $foundItem->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasClaimed) {
            return redirect()->route('found-items.show', $foundItem)
                ->with('error', 'You have already submitted a claim for this item.');
        }

        return view('claims.create', compact('foundItem'));
    }

    public function store(Request $request, FoundItem $foundItem): RedirectResponse
    {
        if ($foundItem->status === 'claimed') {
            return redirect()->route('found-items.show', $foundItem)
                ->with('error', 'This item has already been claimed.');
        }

        $hasClaimed = ClaimRequest::where('found_item_id', $foundItem->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($hasClaimed) {
            return redirect()->route('found-items.show', $foundItem)
                ->with('error', 'You have already submitted a claim for this item.');
        }

        $validated = $request->validate([
            'proof_description' => ['required', 'string', 'min:50'],
            'additional_details' => ['nullable', 'string'],
        ]);

        $validated['found_item_id'] = $foundItem->id;
        $validated['user_id'] = Auth::id();
        $validated['status'] = 'pending';

        ClaimRequest::create($validated);

        $foundItem->update(['status' => 'pending_claim']);

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Claim submitted successfully. You will be notified once reviewed.');
    }

    public function myClaims(): View
    {
        $claims = ClaimRequest::with('foundItem')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('claims.my-claims', compact('claims'));
    }
}
