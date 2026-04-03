<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-shield-lock"></i> Admin Dashboard</h2>
        </div>

        <div class="row mb-4">
            <div class="col-md-2">
                <div class="stat-card primary">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['totalLost'] }}</h2>
                        <small>Total Lost</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card success">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['totalFound'] }}</h2>
                        <small>Total Found</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card warning">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['pendingClaims'] }}</h2>
                        <small>Pending Claims</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card danger">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['totalMatches'] }}</h2>
                        <small>Total Matches</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card" style="background-color: #8B5CF6;">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['unreviewedMatches'] }}</h2>
                        <small>Unreviewed</small>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <div class="stat-card" style="background-color: #06B6D4;">
                    <div class="text-center">
                        <h2 class="mb-0">{{ $stats['claimedItems'] }}</h2>
                        <small>Claimed</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pending Claims</h5>
                        <a href="{{ route('admin.claims') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($pendingClaims as $claim)
                            <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <strong>{{ $claim->foundItem->item_name }}</strong>
                                    <br><small class="text-muted">Claimed by {{ $claim->user->name }}</small>
                                </div>
                                <a href="{{ route('admin.claims.review', $claim) }}" class="btn btn-sm btn-warning">Review</a>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No pending claims.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Matches</h5>
                        <a href="{{ route('admin.matches') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentMatches as $match)
                            <div class="d-flex align-items-center mb-3 p-2 border rounded">
                                <div class="flex-grow-1">
                                    <small class="text-muted">Lost: {{ $match->lostItem->item_name }}</small>
                                    <br><small class="text-muted">Found: {{ $match->foundItem->item_name }}</small>
                                </div>
                                <span class="badge bg-{{ $match->match_score >= 70 ? 'success' : ($match->match_score >= 50 ? 'warning' : 'secondary') }}">
                                    {{ $match->match_score }}%
                                </span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No matches found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Lost Items</h5>
                        <a href="{{ route('admin.lost-items') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentLostItems as $item)
                            <div class="d-flex align-items-center mb-2">
                                <span class="flex-grow-1">{{ $item->item_name }}</span>
                                <span class="badge badge-status status-{{ $item->status }}">{{ $item->status }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No lost items.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Recent Found Items</h5>
                        <a href="{{ route('admin.found-items') }}" class="btn btn-sm btn-outline-primary">View All</a>
                    </div>
                    <div class="card-body">
                        @forelse($recentFoundItems as $item)
                            <div class="d-flex align-items-center mb-2">
                                <span class="flex-grow-1">{{ $item->item_name }}</span>
                                <span class="badge badge-status status-{{ $item->status }}">{{ $item->status }}</span>
                            </div>
                        @empty
                            <p class="text-muted mb-0">No found items.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
