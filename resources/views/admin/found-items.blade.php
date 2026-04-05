<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-handbag"></i> Found Reports Management</h2>
                <p class="text-muted mb-0">Manage and track all found item reports</p>
            </div>
            <span class="badge bg-success fs-6">{{ $foundItems->total() }} Total</span>
        </div>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>Found By</th>
                            <th>Date Found</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($foundItems as $item)
                            <tr>
                                <td>
                                    <strong>{{ $item->item_name }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $item->category }}</span>
                                </td>
                                <td>{{ $item->location }}</td>
                                <td>
                                    {{ $item->user->name }}
                                    <br><small class="text-muted">{{ $item->user->department ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $item->date_found->format('M d, Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.found-items.status', $item) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                            <option value="unclaimed" {{ $item->status == 'unclaimed' ? 'selected' : '' }}>Unclaimed</option>
                                            <option value="pending_claim" {{ $item->status == 'pending_claim' ? 'selected' : '' }}>Pending Claim</option>
                                            <option value="claimed" {{ $item->status == 'claimed' ? 'selected' : '' }}>Claimed</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No found reports found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $foundItems->links() }}
        </div>
    </div>
</x-app-layout>
