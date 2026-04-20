# Image Handling & File Storage Overview - Campus Lost & Found

## 1. Storage Configuration

### File: `config/filesystems.php`

**Default Disk:** `local` (defined via `FILESYSTEM_DISK` env variable)

**Configured Disks:**
- **`local` disk** (Private Storage)
  - Driver: Local filesystem
  - Root: `storage/app/private`
  - Used for private/non-public files
  
- **`public` disk** (Public Storage) ⭐ *Used for images*
  - Driver: Local filesystem
  - Root: `storage/app/public`
  - URL: `{APP_URL}/storage` (e.g., `http://localhost/storage`)
  - Visibility: `public`
  - This is where item images are stored and publicly accessible

- **`s3` disk** (Optional Cloud Storage)
  - AWS S3 configuration available but not currently used for images
  - Uses environment variables for credentials

**Symbolic Links Configuration:**
```php
'links' => [
    public_path('storage') => storage_path('app/public'),
]
```
- Creates a symlink from `public/storage` → `storage/app/public`
- **Required:** Run `php artisan storage:link` to create the symlink
- This allows direct web access to files in `storage/app/public`

---

## 2. Image Upload Handling

### Controllers Implementing Image Uploads

#### **LostItemController** (`app/Http/Controllers/LostItemController.php`)

**Create Method (store):**
```php
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
        $validated['image'] = $path;  // Stores: "lost-items/filename.ext"
    }

    $validated['user_id'] = Auth::id();
    $validated['status'] = 'unclaimed';

    $lostItem = LostItem::create($validated);
    return redirect()->route('lost-items.show', $lostItem)
        ->with('success', 'Lost item reported successfully.');
}
```

**Update Method:**
```php
public function update(Request $request, LostItem $lostItem): RedirectResponse
{
    // Same validation rules
    $validated = $request->validate([
        // ... same rules as store ...
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
```

#### **FoundItemController** (`app/Http/Controllers/FoundItemController.php`)

**Create Method (store):**
```php
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
        $validated['image'] = $path;  // Stores: "found-items/filename.ext"
    }

    $validated['user_id'] = Auth::id();
    $validated['status'] = 'unclaimed';

    $foundItem = FoundItem::create($validated);
    return redirect()->route('found-items.show', $foundItem)
        ->with('success', 'Found item reported successfully.');
}
```

**Update Method:** Similar to LostItemController

### Image Upload Validation Rules:
- **Nullable:** Images are optional
- **Type:** Must be an image file
- **Allowed Formats:** `jpg`, `jpeg`, `png`, `webp`
- **Max Size:** 2048 KB (2 MB)
- **Storage Location:** 
  - Lost items: `storage/app/public/lost-items/`
  - Found items: `storage/app/public/found-items/`

---

## 3. Database Schema

### Lost Items Table (`database/migrations/d_create_lost_items_table.php`)
```php
Schema::create('lost_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('item_name');
    $table->text('description');
    $table->string('category', 100);
    $table->date('date_lost');
    $table->string('location');
    $table->string('image')->nullable();  // ⭐ Image path stored here
    $table->enum('status', ['unclaimed', 'matched', 'resolved'])->default('unclaimed');
    $table->timestamps();
});
```

### Found Items Table (`database/migrations/e_create_found_items_table.php`)
```php
Schema::create('found_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('item_name');
    $table->text('description');
    $table->string('category', 100);
    $table->date('date_found');
    $table->string('location');
    $table->string('image')->nullable();  // ⭐ Image path stored here
    $table->enum('status', ['unclaimed', 'pending_claim', 'claimed'])->default('unclaimed');
    $table->timestamps();
});
```

**Column Details:**
- Stores only the relative path (e.g., `lost-items/uuid.jpg`)
- Can be NULL if no image was uploaded
- Full URL is constructed in views using `asset()` helper

---

## 4. Models

### LostItem Model (`app/Models/LostItem.php`)
```php
protected $fillable = [
    'user_id',
    'item_name',
    'description',
    'category',
    'date_lost',
    'location',
    'image',        // ⭐ Image field
    'status',
];

protected $casts = [
    'date_lost' => 'date',
];
```

### FoundItem Model (`app/Models/FoundItem.php`)
```php
protected $fillable = [
    'user_id',
    'item_name',
    'description',
    'category',
    'date_found',
    'location',
    'image',        // ⭐ Image field
    'status',
];

protected $casts = [
    'date_found' => 'date',
];
```

---

## 5. Image Display in Blade Templates

All image display uses the pattern:
```blade
@if($item->image)
    <img src="{{ asset('storage/' . $item->image) }}" alt="">
@else
    <!-- Fallback placeholder -->
@endif
```

### Templates Using Images:

#### **Lost Items - Index List** (`resources/views/lost-items/index.blade.php`)
```blade
@if($item->image)
    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="item-image">
@else
    <div class="bg-light d-flex align-items-center justify-content-center item-image">
        <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
    </div>
@endif
```
- Large card image display (full item preview)
- Fallback: Search icon placeholder

#### **Lost Items - My Items Table** (`resources/views/lost-items/my-items.blade.php`)
```blade
@if($item->image)
    <img src="{{ asset('storage/' . $item->image) }}" alt="" class="rounded me-2" 
         style="width: 40px; height: 40px; object-fit: cover;">
@endif
<span>{{ $item->item_name }}</span>
```
- Small thumbnail (40x40px) in table row
- No fallback displayed

#### **Lost Items - Detail View** (`resources/views/lost-items/show.blade.php`)
```blade
@if($lostItem->image)
    <img src="{{ asset('storage/' . $lostItem->image) }}" alt="{{ $lostItem->item_name }}" 
         class="img-fluid rounded-start h-100" style="object-fit: cover;">
@else
    <div class="bg-light d-flex align-items-center justify-content-center h-100 min-vh-300">
        <i class="bi bi-search text-muted" style="font-size: 5rem;"></i>
    </div>
@endif
```
- Large image preview on detail page (left side of card)
- Fallback: Large search icon placeholder

#### **Lost Items - Edit Form** (`resources/views/lost-items/edit.blade.php`)
```blade
<label for="image" class="form-label">Image (Optional)</label>
@if($lostItem->image)
    <div class="mb-2">
        <img src="{{ asset('storage/' . $lostItem->image) }}" alt="" class="img-thumbnail" 
             style="max-height: 150px;">
    </div>
@endif
<input type="file" class="form-control @error('image') is-invalid @enderror" 
       id="image" name="image" accept="image/*">
```
- Shows current image as thumbnail if exists
- Allows uploading replacement image

#### **Found Items - Index List** (`resources/views/found-items/index.blade.php`)
```blade
@if($item->image)
    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="item-image">
@else
    <div class="bg-light d-flex align-items-center justify-content-center item-image">
        <i class="bi bi-handbag text-muted" style="font-size: 3rem;"></i>
    </div>
@endif
```
- Card display (same as lost items)
- Fallback: Handbag icon placeholder

#### **Found Items - My Items Table** (`resources/views/found-items/my-items.blade.php`)
```blade
@if($item->image)
    <img src="{{ asset('storage/' . $item->image) }}" alt="" class="rounded me-2" 
         style="width: 40px; height: 40px; object-fit: cover;">
@endif
<span>{{ $item->item_name }}</span>
```
- Same as lost items table thumbnail

#### **Found Items - Detail View** (`resources/views/found-items/show.blade.php`)
```blade
@if($foundItem->image)
    <img src="{{ asset('storage/' . $foundItem->image) }}" alt="{{ $foundItem->item_name }}" 
         class="img-fluid rounded-start h-100" style="object-fit: cover;">
@else
    <div class="bg-light d-flex align-items-center justify-content-center h-100 min-vh-300">
        <i class="bi bi-handbag text-muted" style="font-size: 5rem;"></i>
    </div>
@endif
```
- Same as lost items detail view
- Fallback: Handbag icon placeholder

#### **Found Items - Edit Form** (`resources/views/found-items/edit.blade.php`)
```blade
<label for="image" class="form-label">Image (Optional)</label>
@if($foundItem->image)
    <div class="mb-2">
        <img src="{{ asset('storage/' . $foundItem->image) }}" alt="" class="img-thumbnail" 
             style="max-height: 150px;">
    </div>
@endif
<input type="file" class="form-control @error('image') is-invalid @enderror" 
       id="image" name="image" accept="image/*">
```
- Same as lost items edit form

#### **Create Forms** (`resources/views/lost-items/create.blade.php` & `resources/views/found-items/create.blade.php`)
```blade
<div class="mb-3">
    <label for="image" class="form-label">Image (Optional)</label>
    <input type="file" class="form-control @error('image') is-invalid @enderror" 
           id="image" name="image" accept="image/*">
    <small class="text-muted">Max file size: 2MB. Accepted formats: JPG, PNG, WEBP</small>
    @error('image')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```
- File upload input
- User guidance on constraints
- Validation error display

---

## 6. Routes

### Image-Related Routes (`routes/web.php`)

No explicit image serving routes are defined. Images are served through:
- **Static file serving** via the symlinked `public/storage` directory
- **Laravel's asset helper** constructs URLs: `asset('storage/path/to/image')`
- **Direct web access:** `http://localhost/storage/lost-items/filename.jpg`

#### Lost Items Routes (Require Authentication)
```php
Route::get('/lost-items', [LostItemController::class, 'index'])->name('lost-items.index');
Route::get('/lost-items/my', [LostItemController::class, 'myItems'])->name('lost-items.my-items');
Route::get('/lost-items/create', [LostItemController::class, 'create'])->name('lost-items.create');
Route::post('/lost-items', [LostItemController::class, 'store'])->name('lost-items.store');  // Upload happens here
Route::get('/lost-items/{lostItem}', [LostItemController::class, 'show'])->name('lost-items.show');
Route::get('/lost-items/{lostItem}/edit', [LostItemController::class, 'edit'])->name('lost-items.edit');
Route::patch('/lost-items/{lostItem}', [LostItemController::class, 'update'])->name('lost-items.update');  // Update happens here
Route::delete('/lost-items/{lostItem}', [LostItemController::class, 'destroy'])->name('lost-items.destroy');
```

#### Found Items Routes (Require Authentication)
```php
Route::get('/found-items', [FoundItemController::class, 'index'])->name('found-items.index');
Route::get('/found-items/my', [FoundItemController::class, 'myItems'])->name('found-items.my-items');
Route::get('/found-items/create', [FoundItemController::class, 'create'])->name('found-items.create');
Route::post('/found-items', [FoundItemController::class, 'store'])->name('found-items.store');  // Upload happens here
Route::get('/found-items/{foundItem}', [FoundItemController::class, 'show'])->name('found-items.show');
Route::get('/found-items/{foundItem}/edit', [FoundItemController::class, 'edit'])->name('found-items.edit');
Route::patch('/found-items/{foundItem}', [FoundItemController::class, 'update'])->name('found-items.update');  // Update happens here
Route::delete('/found-items/{foundItem}', [FoundItemController::class, 'destroy'])->name('found-items.destroy');
```

---

## 7. Image File System Structure

```
storage/
├── app/
│   ├── private/               (Private files)
│   └── public/
│       ├── lost-items/        ⭐ Lost item images
│       │   └── [uuid].jpg
│       │   └── [uuid].png
│       │   └── [uuid].webp
│       └── found-items/       ⭐ Found item images
│           └── [uuid].jpg
│           └── [uuid].png
│           └── [uuid].webp
│
public/
└── storage/                   ⭐ Symlink → storage/app/public
    ├── lost-items/
    ├── found-items/
    └── images/                (Static assets like logo.png)
```

---

## 8. Image URL Generation

### How Images Are Accessed:

1. **File Upload:**
   ```php
   $path = $request->file('image')->store('lost-items', 'public');
   // Returns: "lost-items/UUID.jpg"
   // Stored at: storage/app/public/lost-items/UUID.jpg
   ```

2. **Database Storage:**
   ```php
   $validated['image'] = $path;  // Stores relative path
   // Stored in DB: "lost-items/UUID.jpg"
   ```

3. **URL Generation in Views:**
   ```blade
   {{ asset('storage/' . $item->image) }}
   // Generates: /storage/lost-items/UUID.jpg
   // Full URL: http://localhost/storage/lost-items/UUID.jpg
   ```

4. **Web Access:**
   - Symlink redirects: `public/storage` → `storage/app/public`
   - Web server serves: `/storage/lost-items/UUID.jpg` from `storage/app/public/lost-items/UUID.jpg`

---

## 9. File Upload Flow Diagram

```
User Upload → Form Submission
                    ↓
            LostItemController::store()
            FoundItemController::store()
                    ↓
            Validation (image: nullable, image, mimes:..., max:2048)
                    ↓
            $request->file('image')->store('lost-items' or 'found-items', 'public')
                    ↓
            File saved to: storage/app/public/lost-items/ or storage/app/public/found-items/
            Relative path returned: e.g., "lost-items/UUID.jpg"
                    ↓
            Saved to database (lost_items.image or found_items.image column)
                    ↓
            Redirect to show page
                    ↓
            Display via: asset('storage/' . $item->image)
                    ↓
            Web browser accesses: /storage/lost-items/UUID.jpg
```

---

## 10. Key Features Summary

| Feature | Implementation |
|---------|-----------------|
| **Storage Location** | `storage/app/public/` (public disk) |
| **Upload Subdirectories** | `lost-items/` and `found-items/` |
| **File Types Allowed** | JPG, JPEG, PNG, WEBP |
| **Max File Size** | 2 MB (2048 KB) |
| **Required for Upload** | Image field optional for creating/updating items |
| **Database Storage** | Relative path only (e.g., `lost-items/UUID.jpg`) |
| **Public Accessibility** | Yes - via symlink `public/storage` |
| **URL Pattern** | `/storage/{subfolder}/{filename}` |
| **Update Handling** | Old images not deleted; new image replaces path in DB |
| **Authorization** | Only item owner or admin can upload/update |
| **Fallback Display** | Icon placeholders (search for lost, handbag for found) |

---

## 11. Potential Issues & Notes

1. **Old Images Not Deleted:** When updating an item with a new image, the old image file is not automatically deleted from storage. Consider implementing cleanup logic if storage is limited.

2. **Symlink Required:** The storage symlink must exist at `public/storage` for images to be accessible. Run:
   ```bash
   php artisan storage:link
   ```

3. **Missing Image Handling:** All templates properly check `@if($item->image)` before displaying, preventing broken image links.

4. **Static Assets:** Logo `images/logo.png` is stored in `public/images/`, not in storage.

5. **Admin Access:** Admins can view/edit images of items reported by other users.

6. **No Image Resizing:** Currently, images are stored at original size. Consider implementing thumbnail generation for better performance on list views.

7. **No CDN Integration:** Images served from local storage. For production, consider S3 integration (already configured but unused).

---

## Setup Checklist for Image Handling

- [ ] Run `php artisan storage:link` to create the public/storage symlink
- [ ] Ensure `storage/app/public/` directory has write permissions (755)
- [ ] Ensure `storage/app/public/lost-items/` and `storage/app/public/found-items/` directories exist
- [ ] Test image upload on both lost and found item creation
- [ ] Verify images display correctly in list, detail, and edit views
- [ ] Check that validation works (test file size and type restrictions)
