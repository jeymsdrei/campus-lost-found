<x-app-layout>
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="form-section">
                    <h3 class="mb-4"><i class="bi bi-hand-thumbs-up text-success"></i> Claim Found Item</h3>
                    
                    <div class="alert alert-info mb-4">
                        <h5>{{ $foundItem->item_name }}</h5>
                        <p class="mb-0"><strong>Location:</strong> {{ $foundItem->location }}</p>
                        <p class="mb-0"><strong>Date Found:</strong> {{ $foundItem->date_found->format('M d, Y') }}</p>
                    </div>

                    <form method="POST" action="{{ route('claims.store', $foundItem) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="proof_description" class="form-label">Proof of Ownership *</label>
                            <textarea class="form-control @error('proof_description') is-invalid @enderror" id="proof_description" name="proof_description" rows="5" placeholder="Describe unique identifying features, serial numbers, marks, or any details that prove this item belongs to you (minimum 50 characters)" required>{{ old('proof_description') }}</textarea>
                            @error('proof_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_details" class="form-label">Additional Details (Optional)</label>
                            <textarea class="form-control @error('additional_details') is-invalid @enderror" id="additional_details" name="additional_details" rows="3" placeholder="Any other information that supports your claim">{{ old('additional_details') }}</textarea>
                            @error('additional_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Note:</strong> Submitting false claims may result in account suspension. Please only claim items that genuinely belong to you.
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">Submit Claim</button>
                            <a href="{{ route('found-items.show', $foundItem) }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
