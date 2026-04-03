<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-section">
                    <h3 class="mb-4"><i class="bi bi-handbag text-success"></i> Edit Found Item</h3>
                    <form method="POST" action="{{ route('found-items.update', $foundItem) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label for="item_name" class="form-label">Item Name *</label>
                            <input type="text" class="form-control @error('item_name') is-invalid @enderror" id="item_name" name="item_name" value="{{ old('item_name', $foundItem->item_name) }}" required>
                            @error('item_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4" required>{{ old('description', $foundItem->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label">Category *</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                                    @foreach(['Electronics', 'Books', 'Bags', 'Clothing', 'Accessories', 'Documents', 'Jewelry', 'Others'] as $cat)
                                        <option value="{{ $cat }}" {{ $foundItem->category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_found" class="form-label">Date Found *</label>
                                <input type="date" class="form-control @error('date_found') is-invalid @enderror" id="date_found" name="date_found" value="{{ old('date_found', $foundItem->date_found->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                @error('date_found')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="location" class="form-label">Location Found *</label>
                            <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $foundItem->location) }}" required>
                            @error('location')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image (Optional)</label>
                            @if($foundItem->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $foundItem->image) }}" alt="" class="img-thumbnail" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Update</button>
                            <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
