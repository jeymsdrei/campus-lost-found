<x-app-layout>
    <div class="container py-4">
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4">
                    @if($foundItem->image)
                        <img src="{{ asset('storage/' . $foundItem->image) }}" alt="{{ $foundItem->item_name }}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center h-100 min-vh-300">
                            <i class="bi bi-handbag text-muted" style="font-size: 5rem;"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="card-title">{{ $foundItem->item_name }}</h3>
                                <span class="badge badge-status status-{{ $foundItem->status }}">{{ ucfirst(str_replace('_', ' ', $foundItem->status)) }}</span>
                            </div>
                            @can('update', $foundItem)
                                <div>
                                    <a href="{{ route('found-items.edit', $foundItem) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>
                            @endcan
                        </div>

                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="card-text">{{ $foundItem->description }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-tag"></i> Category:</strong> {{ $foundItem->category }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-geo-alt"></i> Location:</strong> {{ $foundItem->location }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-calendar"></i> Date Found:</strong> {{ $foundItem->date_found->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-person"></i> Reported By:</strong> {{ $foundItem->user->name }}</p>
                            </div>
                        </div>

                        @if($matches->isNotEmpty())
                            <div class="alert alert-info mb-4">
                                <h5><i class="bi bi-lightbulb"></i> Possible Matches with Lost Reports</h5>
                                <p class="mb-2">This item matches {{ $matches->count() }} reported lost item(s):</p>
                                @foreach($matches as $match)
                                    <div class="d-flex align-items-center p-2 border rounded mb-2">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('lost-items.show', $match->lostItem) }}" class="text-decoration-none">
                                                <strong>{{ $match->lostItem->item_name }}</strong>
                                            </a>
                                            <br><small class="text-muted">{{ $match->lostItem->location }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-{{ $match->match_score >= 70 ? 'success' : ($match->match_score >= 50 ? 'warning' : 'secondary') }}">
                                                {{ $match->match_score }}% Match
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @auth
                            @if($foundItem->status !== 'claimed' && !$hasClaimed)
                                <div class="d-grid gap-2 mb-3">
                                    <a href="{{ route('claims.create', $foundItem) }}" class="btn btn-success btn-lg">
                                        <i class="bi bi-hand-thumbs-up"></i> Claim This Item
                                    </a>
                                </div>
                            @elseif($hasClaimed)
                                <div class="alert alert-warning mb-3">
                                    <i class="bi bi-info-circle"></i> You have already submitted a claim for this item.
                                </div>
                            @elseif($foundItem->status === 'claimed')
                                <div class="alert alert-secondary mb-3">
                                    <i class="bi bi-check-circle"></i> This item has been claimed.
                                </div>
                            @endif
                        @endauth

                        <div class="d-flex gap-2">
                            <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
