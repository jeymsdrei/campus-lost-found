<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-file-text"></i> Claims Management</h2>
                <p class="text-muted mb-0">Review and process claim requests from users</p>
            </div>
            @php $pendingCount = $claims->where('status', 'pending')->count(); @endphp
            @if($pendingCount > 0)
                <span class="badge bg-warning fs-6">{{ $pendingCount }} Pending</span>
            @endif
        </div>
        
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
                        @forelse($claims as $claim)
                            <tr class="{{ $claim->status === 'pending' ? 'table-warning' : '' }}">
                                <td>
                                    <strong>{{ $claim->foundItem->item_name }}</strong>
                                    <br><small class="text-muted">{{ $claim->foundItem->category }}</small>
                                </td>
                                <td>
                                    {{ $claim->user->name }}
                                    <br><small class="text-muted">{{ $claim->user->email }}</small>
                                </td>
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
                                    <a href="{{ route('admin.claims.review', $claim) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No claims found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $claims->links() }}
        </div>
    </div>
</x-app-layout>
