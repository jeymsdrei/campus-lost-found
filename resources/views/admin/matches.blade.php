<x-app-layout>
    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-link"></i> Item Matches</h2>
        
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
                        @foreach($matches as $match)
                            <tr>
                                <td>
                                    <a href="{{ route('lost-items.show', $match->lostItem) }}">{{ $match->lostItem->item_name }}</a>
                                </td>
                                <td>
                                    <a href="{{ route('found-items.show', $match->foundItem) }}">{{ $match->foundItem->item_name }}</a>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $match->match_score >= 70 ? 'success' : ($match->match_score >= 50 ? 'warning' : 'secondary') }}">
                                        {{ $match->match_score }}%
                                    </span>
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
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Mark Reviewed</button>
                                        </form>
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
            {{ $matches->links() }}
        </div>
    </div>
</x-app-layout>
