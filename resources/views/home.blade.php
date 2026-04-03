<x-app-layout>
    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-12">
                <h1 class="mb-3">Welcome to Campus Lost & Found</h1>
                <p class="text-muted">Help reunite lost items with their owners. Report lost items or help identify found items.</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card primary">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Lost</h6>
                            <h2 class="mb-0">{{ $stats['totalLost'] }}</h2>
                        </div>
                        <i class="bi bi-search" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Total Found</h6>
                            <h2 class="mb-0">{{ $stats['totalFound'] }}</h2>
                        </div>
                        <i class="bi bi-handbag" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card warning">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Unclaimed</h6>
                            <h2 class="mb-0">{{ $stats['unclaimed'] }}</h2>
                        </div>
                        <i class="bi bi-clock" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card danger">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0 opacity-75">Pending Claims</h6>
                            <h2 class="mb-0">{{ $stats['pendingClaims'] }}</h2>
                        </div>
                        <i class="bi bi-file-text" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="mb-3"><i class="bi bi-search text-danger"></i> Recent Lost Items</h5>
                    @forelse($recentLostItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-search text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item->item_name }}</h6>
                                <small class="text-muted">{{ $item->location }} - {{ $item->date_lost->format('M d, Y') }}</small>
                            </div>
                            <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-primary">View</a>
                        </div>
                    @empty
                        <p class="text-muted">No lost items reported yet.</p>
                    @endforelse
                    <a href="{{ route('lost-items.index') }}" class="btn btn-outline-primary btn-sm">View All Lost Items</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card p-3">
                    <h5 class="mb-3"><i class="bi bi-handbag text-success"></i> Recent Found Items</h5>
                    @forelse($recentFoundItems as $item)
                        <div class="d-flex align-items-center mb-3 p-2 border rounded">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi bi-handbag text-muted"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-0">{{ $item->item_name }}</h6>
                                <small class="text-muted">{{ $item->location }} - {{ $item->date_found->format('M d, Y') }}</small>
                            </div>
                            <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-success">View</a>
                        </div>
                    @empty
                        <p class="text-muted">No found items reported yet.</p>
                    @endforelse
                    <a href="{{ route('found-items.index') }}" class="btn btn-outline-success btn-sm">View All Found Items</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <h4 class="mb-3">Need to report something?</h4>
                <div class="d-flex justify-content-center gap-3">
                    <a href="{{ route('lost-items.create') }}" class="btn btn-danger btn-lg">
                        <i class="bi bi-search"></i> Report Lost Item
                    </a>
                    <a href="{{ route('found-items.create') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-handbag"></i> Report Found Item
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
