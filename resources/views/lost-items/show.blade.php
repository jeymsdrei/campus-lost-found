<x-app-layout>
    <div class="container py-4">
        <div class="card">
            <div class="row g-0">
                <div class="col-md-4">
                    @if($lostItem->image)
                        <img src="{{ asset('storage/' . $lostItem->image) }}" alt="{{ $lostItem->item_name }}" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center h-100 min-vh-300">
                            <img src="{{ asset('images/logo.png') }}" alt="Lost & Found" style="width: 130px; height: 130px;">
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h3 class="card-title">{{ $lostItem->item_name }}</h3>
                                <span class="badge badge-status status-{{ $lostItem->status }}">{{ ucfirst(str_replace('_', ' ', $lostItem->status)) }}</span>
                            </div>
                            @can('update', $lostItem)
                                <div>
                                    <a href="{{ route('lost-items.edit', $lostItem) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                </div>
                            @endcan
                        </div>

                        <div class="mb-4">
                            <h5>Description</h5>
                            <p class="card-text">{{ $lostItem->description }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-tag"></i> Category:</strong> {{ $lostItem->category }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-geo-alt"></i> Location:</strong> {{ $lostItem->location }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-calendar"></i> Date Lost:</strong> {{ $lostItem->date_lost->format('M d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong><i class="bi bi-person"></i> Reported By:</strong> {{ $lostItem->user->name }}</p>
                            </div>
                        </div>

                        @if($matches->isNotEmpty())
                            <div class="alert alert-info">
                                <h5><i class="bi bi-lightbulb"></i> Possible Matches Found</h5>
                                <p class="mb-2">Based on your report, we found {{ $matches->count() }} possible matching found item(s):</p>
                                @foreach($matches as $match)
                                    <div class="d-flex align-items-center p-2 border rounded mb-2">
                                        <div class="flex-grow-1">
                                            <a href="{{ route('found-items.show', $match->foundItem) }}" class="text-decoration-none">
                                                <strong>{{ $match->foundItem->item_name }}</strong>
                                            </a>
                                            <br><small class="text-muted">{{ $match->foundItem->location }}</small>
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

                        <div class="d-flex gap-2">
                            <a href="{{ route('lost-items.index') }}" class="btn btn-outline-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
