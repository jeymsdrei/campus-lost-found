<x-app-layout>
    <div class="container py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Claim Review</h5>
                    </div>
                    <div class="card-body">
                        <h4>{{ $claim->foundItem->item_name }}</h4>
                        <hr>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong>Claimant:</strong> {{ $claim->user->name }}</p>
                                <p><strong>Email:</strong> {{ $claim->user->email }}</p>
                                <p><strong>Phone:</strong> {{ $claim->user->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Submitted:</strong> {{ $claim->created_at->format('M d, Y H:i') }}</p>
                                <p><strong>Status:</strong> 
                                    @if($claim->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($claim->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </p>
                            </div>
                        </div>

                        <h5>Proof of Ownership</h5>
                        <p class="card-text">{{ $claim->proof_description }}</p>

                        @if($claim->additional_details)
                            <h5 class="mt-4">Additional Details</h5>
                            <p class="card-text">{{ $claim->additional_details }}</p>
                        @endif

                        @if($claim->admin_notes)
                            <div class="alert alert-info mt-4">
                                <h6>Admin Notes:</h6>
                                <p class="mb-0">{{ $claim->admin_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">Item Details</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Item:</strong> {{ $claim->foundItem->item_name }}</p>
                        <p><strong>Category:</strong> {{ $claim->foundItem->category }}</p>
                        <p><strong>Location:</strong> {{ $claim->foundItem->location }}</p>
                        <p><strong>Date Found:</strong> {{ $claim->foundItem->date_found->format('M d, Y') }}</p>
                        <p><strong>Description:</strong> {{ $claim->foundItem->description }}</p>
                        <a href="{{ route('found-items.show', $claim->foundItem) }}" class="btn btn-sm btn-outline-success">View Item</a>
                    </div>
                </div>

                @if($claim->status === 'pending')
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Take Action</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" class="mb-3">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Notes (Optional)</label>
                                    <textarea name="admin_notes" class="form-control" rows="2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Approve Claim</button>
                            </form>
                            
                            <form method="POST" action="{{ route('admin.claims.reject', $claim) }}">
                                @csrf
                                <div class="mb-2">
                                    <label class="form-label">Rejection Reason</label>
                                    <textarea name="admin_notes" class="form-control" rows="2" placeholder="Explain why the claim was rejected"></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Reject Claim</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
