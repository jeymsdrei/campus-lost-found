<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-search"></i> My Lost Items</h2>
            <a href="{{ route('lost-items.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Report New
            </a>
        </div>

        @if($lostItems->isEmpty())
            <div class="empty-state card">
                <i class="bi bi-search" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Lost Items Reported</h4>
                <p class="text-muted">You haven't reported any lost items yet.</p>
                <a href="{{ route('lost-items.create') }}" class="btn btn-primary mt-3">Report Lost Item</a>
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
                                <th>Date Lost</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lostItems as $item)
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
                                    <td>{{ $item->date_lost->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge badge-status status-{{ $item->status }}">
                                            {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('lost-items.edit', $item) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                        <form method="POST" action="{{ route('lost-items.destroy', $item) }}" class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                {{ $lostItems->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
