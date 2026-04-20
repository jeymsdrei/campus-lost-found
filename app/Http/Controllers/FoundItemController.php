<?php

namespace App\Http\Controllers;

use App\Models\FoundItem;
use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class FoundItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = FoundItem::with('user')->where('status', '!=', 'claimed');

        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('item_name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $foundItems = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('found-items.index', compact('foundItems'));
    }

    public function myItems(): View
    {
        $foundItems = FoundItem::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('found-items.my-items', compact('foundItems'));
    }

    public function create(): View
    {
        return view('found-items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category' => ['required', 'string', 'max:100'],
            'date_found' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('found-items', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'unclaimed';

        $foundItem = FoundItem::create($validated);

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item reported successfully.');
    }

    public function show(FoundItem $foundItem): View
    {
        $hasClaimed = $foundItem->claimRequests()
            ->where('user_id', Auth::id())
            ->exists();

        return view('found-items.show', compact('foundItem', 'hasClaimed'));
    }

    public function edit(FoundItem $foundItem): View
    {
        if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('found-items.edit', compact('foundItem'));
    }

    public function update(Request $request, FoundItem $foundItem): RedirectResponse
    {
        if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category' => ['required', 'string', 'max:100'],
            'date_found' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('found-items', 'public');
            $validated['image'] = $path;
        }

        $foundItem->update($validated);

        return redirect()->route('found-items.show', $foundItem)
            ->with('success', 'Found item updated successfully.');
    }

    public function destroy(FoundItem $foundItem): RedirectResponse
    {
        if ($foundItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $foundItem->delete();

        return redirect()->route('found-items.my-items')
            ->with('success', 'Found item deleted successfully.');
    }
}
