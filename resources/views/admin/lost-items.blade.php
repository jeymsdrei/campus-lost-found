<x-app-layout>
    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-search"></i> Manage Lost Items</h2>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Category</th>
                            <th>Location</th>
                            <th>User</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lostItems as $item)
                            <tr>
                                <td>{{ $item->item_name }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->location }}</td>
                                <td>{{ $item->user->name }}</td>
                                <td>{{ $item->date_lost->format('M d, Y') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.lost-items.status', $item) }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto;">
                                            <option value="unclaimed" {{ $item->status == 'unclaimed' ? 'selected' : '' }}>Unclaimed</option>
                                            <option value="matched" {{ $item->status == 'matched' ? 'selected' : '' }}>Matched</option>
                                            <option value="resolved" {{ $item->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                                        </select>
                                    </form>
                                </td>
                                <td>
                                    <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
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
    </div>
</x-app-layout>
