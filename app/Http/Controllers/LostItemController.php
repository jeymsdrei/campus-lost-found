<?php

namespace App\Http\Controllers;

use App\Models\LostItem;
use App\Models\ItemMatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class LostItemController extends Controller
{
    public function index(Request $request): View
    {
        $query = LostItem::with('user')->where('status', '!=', 'resolved');

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

        $lostItems = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('lost-items.index', compact('lostItems'));
    }

    public function myItems(): View
    {
        $lostItems = LostItem::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('lost-items.my-items', compact('lostItems'));
    }

    public function create(): View
    {
        return view('lost-items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category' => ['required', 'string', 'max:100'],
            'date_lost' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('lost-items', 'public');
            $validated['image'] = $path;
        }

        $validated['user_id'] = Auth::id();
        $validated['status'] = 'unclaimed';

        $lostItem = LostItem::create($validated);

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item reported successfully.');
    }

    public function show(LostItem $lostItem): View
    {
        return view('lost-items.show', compact('lostItem'));
    }

    public function edit(LostItem $lostItem): View
    {
        if ($lostItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        return view('lost-items.edit', compact('lostItem'));
    }

    public function update(Request $request, LostItem $lostItem): RedirectResponse
    {
        if ($lostItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'min:20'],
            'category' => ['required', 'string', 'max:100'],
            'date_lost' => ['required', 'date', 'before_or_equal:today'],
            'location' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('lost-items', 'public');
            $validated['image'] = $path;
        }

        $lostItem->update($validated);

        return redirect()->route('lost-items.show', $lostItem)
            ->with('success', 'Lost item updated successfully.');
    }

    public function destroy(LostItem $lostItem): RedirectResponse
    {
        if ($lostItem->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $lostItem->delete();

        return redirect()->route('lost-items.my-items')
            ->with('success', 'Lost item deleted successfully.');
    }
}
