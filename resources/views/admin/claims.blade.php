<x-app-layout>
    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-file-text"></i> Manage Claims</h2>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Item</th>
                            <th>Claimant</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($claims as $claim)
                            <tr>
                                <td>
                                    <a href="{{ route('found-items.show', $claim->foundItem) }}">{{ $claim->foundItem->item_name }}</a>
                                </td>
                                <td>{{ $claim->user->name }}</td>
                                <td>{{ $claim->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($claim->status === 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($claim->status === 'approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.claims.review', $claim) }}" class="btn btn-sm btn-warning">Review</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $claims->links() }}
        </div>
    </div>
</x-app-layout>
