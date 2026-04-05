<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\FoundItemController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LostItemController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/lost-items', [LostItemController::class, 'index'])->name('lost-items.index');
    Route::get('/lost-items/my', [LostItemController::class, 'myItems'])->name('lost-items.my-items');
    Route::get('/lost-items/create', [LostItemController::class, 'create'])->name('lost-items.create');
    Route::post('/lost-items', [LostItemController::class, 'store'])->name('lost-items.store');
    Route::get('/lost-items/{lostItem}', [LostItemController::class, 'show'])->name('lost-items.show');
    Route::get('/lost-items/{lostItem}/edit', [LostItemController::class, 'edit'])->name('lost-items.edit');
    Route::patch('/lost-items/{lostItem}', [LostItemController::class, 'update'])->name('lost-items.update');
    Route::delete('/lost-items/{lostItem}', [LostItemController::class, 'destroy'])->name('lost-items.destroy');

    Route::get('/found-items', [FoundItemController::class, 'index'])->name('found-items.index');
    Route::get('/found-items/my', [FoundItemController::class, 'myItems'])->name('found-items.my-items');
    Route::get('/found-items/create', [FoundItemController::class, 'create'])->name('found-items.create');
    Route::post('/found-items', [FoundItemController::class, 'store'])->name('found-items.store');
    Route::get('/found-items/{foundItem}', [FoundItemController::class, 'show'])->name('found-items.show');
    Route::get('/found-items/{foundItem}/edit', [FoundItemController::class, 'edit'])->name('found-items.edit');
    Route::patch('/found-items/{foundItem}', [FoundItemController::class, 'update'])->name('found-items.update');
    Route::delete('/found-items/{foundItem}', [FoundItemController::class, 'destroy'])->name('found-items.destroy');

    Route::get('/claims/create/{foundItem}', [ClaimController::class, 'create'])->name('claims.create');
    Route::post('/claims/{foundItem}', [ClaimController::class, 'store'])->name('claims.store');
    Route::get('/my-claims', [ClaimController::class, 'myClaims'])->name('claims.my-claims');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/lost-items', [AdminController::class, 'lostItems'])->name('lost-items');
    Route::patch('/lost-items/{lostItem}/status', [AdminController::class, 'updateLostItemStatus'])->name('lost-items.status');
    Route::get('/found-items', [AdminController::class, 'foundItems'])->name('found-items');
    Route::patch('/found-items/{foundItem}/status', [AdminController::class, 'updateFoundItemStatus'])->name('found-items.status');
    Route::get('/claims', [AdminController::class, 'claims'])->name('claims');
    Route::get('/claims/{claim}/review', [AdminController::class, 'reviewClaim'])->name('claims.review');
    Route::post('/claims/{claim}/approve', [AdminController::class, 'approveClaim'])->name('claims.approve');
    Route::post('/claims/{claim}/reject', [AdminController::class, 'rejectClaim'])->name('claims.reject');
    Route::get('/matches', [AdminController::class, 'matches'])->name('matches');
    Route::post('/matches/{match}/reviewed', [AdminController::class, 'markMatchReviewed'])->name('matches.reviewed');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('users.toggle-admin');
});

require __DIR__.'/auth.php';
