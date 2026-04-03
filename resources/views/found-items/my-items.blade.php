<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-handbag"></i> My Found Items</h2>
            <a href="{{ route('found-items.create') }}" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Report New
            </a>
        </div>

        @if($foundItems->isEmpty())
            <div class="empty-state card">
                <i class="bi bi-handbag" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Found Items Reported</h4>
                <p class="text-muted">You haven't reported any found items yet.</p>
                <a href="{{ route('found-items.create') }}" class="btn btn-success mt-3">Report Found Item</a>
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Date Found</th>
                                <th>Status</th>
                                <th>Claims</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($foundItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->image)
                                                <img src="{{ asset('storage/' . $item->image) }}" alt="" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <span>{{ $item->item_name }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $item->category }}</td>
                                    <td>{{ $item->location }}</td>
                                    <td>{{ $item->date_found->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-status status-{{ $item->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php $pendingClaims = $item->claimRequests()->where('status', 'pending')->count(); @endphp
                                        @if($pendingClaims > 0)
                                            <span class="badge bg-warning">{{ $pendingClaims }} pending</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-success">View</a>
                                        <a href="{{ route('found-items.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('found-items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $foundItems->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
