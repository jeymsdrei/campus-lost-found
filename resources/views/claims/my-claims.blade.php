<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-file-text"></i> My Claims</h2>
        </div>

        @if($claims->isEmpty())
            <div class="empty-state card">
                <i class="bi bi-file-text" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Claims Submitted</h4>
                <p class="text-muted">You haven't submitted any claims yet.</p>
                <a href="{{ route('found-items.index') }}" class="btn btn-success mt-3">Browse Found Items</a>
            </div>
        @else
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th>Submitted</th>
                                <th>Status</th>
                                <th>Admin Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($claims as $claim)
                                <tr>
                                    <td>
                                        <a href="{{ route('found-items.show', $claim->foundItem) }}" class="text-decoration-none">
                                            <strong>{{ $claim->foundItem->item_name }}</strong>
                                        </a>
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
                                        @if($claim->admin_notes)
                                            <small class="text-muted">{{ Str::limit($claim->admin_notes, 50) }}</small>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
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
        @endif
    </div>
</x-app-layout>
