<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-link"></i> Item Matches</h2>
                <p class="text-muted mb-0">Automated matches between lost and found items</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $matches->total() }} Total Matches</span>
        </div>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Lost Item</th>
                            <th>Found Item</th>
                            <th>Match Score</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($matches as $match)
                            <tr class="{{ !$match->is_reviewed ? 'table-info' : '' }}">
                                <td>
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}">{{ $match->lostItem->item_name }}</a>
                                    <br><small class="text-muted">{{ $match->lostItem->category }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('found-items.show', $match->foundItem) }}">{{ $match->foundItem->item_name }}</a>
                                    <br><small class="text-muted">{{ $match->foundItem->category }}</small>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1 me-2" style="height: 8px; width: 80px;">
                                            <div class="progress-bar bg-{{ $match->match_score >= 70 ? 'success' : ($match->match_score >= 50 ? 'warning' : 'secondary') }}" 
                                                 style="width: {{ $match->match_score }}%"></div>
                                        </div>
                                        <span class="badge bg-{{ $match->match_score >= 70 ? 'success' : ($match->match_score >= 50 ? 'warning' : 'secondary') }}">
                                            {{ $match->match_score }}%
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    @if($match->is_reviewed)
                                        <span class="badge bg-success">Reviewed</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$match->is_reviewed)
                                        <form method="POST" action="{{ route('admin.matches.reviewed', $match) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-check"></i> Mark Reviewed
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No matches found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $matches->links() }}
        </div>
    </div>
</x-app-layout>
