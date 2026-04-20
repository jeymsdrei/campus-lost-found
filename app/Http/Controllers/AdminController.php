<?php

namespace App\Http\Controllers;

use App\Models\ClaimRequest;
use App\Models\FoundItem;
use App\Models\LostItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        $stats = [
            'totalLost' => LostItem::count(),
            'totalFound' => FoundItem::count(),
            'pendingClaims' => ClaimRequest::where('status', 'pending')->count(),
            'claimedItems' => FoundItem::where('status', 'claimed')->count(),
        ];

        $recentLostItems = LostItem::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentFoundItems = FoundItem::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $pendingClaims = ClaimRequest::with(['foundItem', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentLostItems',
            'recentFoundItems',
            'pendingClaims'
        ));
    }

    public function lostItems(): View
    {
        $lostItems = LostItem::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.lost-items', compact('lostItems'));
    }

    public function updateLostItemStatus(Request $request, LostItem $lostItem): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:unclaimed,matched,resolved'],
        ]);

        $lostItem->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function foundItems(): View
    {
        $foundItems = FoundItem::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.found-items', compact('foundItems'));
    }

    public function updateFoundItemStatus(Request $request, FoundItem $foundItem): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:unclaimed,pending_claim,claimed'],
        ]);

        $foundItem->update($validated);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

    public function claims(): View
    {
        $claims = ClaimRequest::with(['foundItem', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.claims', compact('claims'));
    }

    public function reviewClaim(ClaimRequest $claim): View
    {
        return view('admin.claims-review', compact('claim'));
    }

    public function approveClaim(Request $request, ClaimRequest $claim): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string'],
        ]);

        $claim->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'],
        ]);

        $claim->foundItem->update(['status' => 'claimed']);

        return redirect()->route('admin.claims')
            ->with('success', 'Claim approved. The item has been marked as claimed.');
    }

    public function rejectClaim(Request $request, ClaimRequest $claim): RedirectResponse
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string'],
        ]);

        $claim->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        $hasOtherPending = ClaimRequest::where('found_item_id', $claim->found_item_id)
            ->where('status', 'pending')
            ->where('id', '!=', $claim->id)
            ->exists();

        if (!$hasOtherPending) {
            $claim->foundItem->update(['status' => 'unclaimed']);
        }

        return redirect()->route('admin.claims')
            ->with('success', 'Claim rejected.');
    }

    public function users(Request $request): View
    {
        $query = User::where('role', '!=', 'admin');

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->has('department') && $request->department) {
            $query->where('department', $request->department);
        }

        if ($request->has('year_level') && $request->year_level) {
            $query->where('year_level', $request->year_level);
        }

        if ($request->has('semester') && $request->semester) {
            $query->where('semester', $request->semester);
        }

        $users = $query->orderBy('department', 'asc')->orderBy('year_level', 'asc')->orderBy('name', 'asc')->paginate(50);

        return view('admin.users', compact('users'));
    }

    public function toggleAdmin(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot change your own admin status.');
        }

        $newRole = $user->role === 'admin' ? 'user' : 'admin';
        $user->update(['role' => $newRole]);

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function destroyUser(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->back()->with('success', "User '{$userName}' deleted successfully.");
    }
}
