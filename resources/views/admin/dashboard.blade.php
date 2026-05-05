<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
            <span class="badge bg-primary">Administrator</span>
        </div>

        <div class="row mb-4 gx-4">
            <div class="col-md-4">
                <a href="{{ route('admin.lost-items') }}" class="text-decoration-none">
                    <div class="card text-center p-3 border-danger h-100">
                        <i class="bi bi-search text-danger" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['totalLost'] }}</h3>
                        <small class="text-muted">Lost Reports</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.found-items') }}" class="text-decoration-none">
                    <div class="card text-center p-3 border-success h-100">
                        <i class="bi bi-handbag text-success" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['totalFound'] }}</h3>
                        <small class="text-muted">Found Reports</small>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="{{ route('admin.claims') }}" class="text-decoration-none">
                    <div class="card text-center p-3 border-warning h-100">
                        <i class="bi bi-file-text text-warning" style="font-size: 2rem;"></i>
                        <h3 class="mt-2 mb-0">{{ $stats['pendingClaims'] }}</h3>
                        <small class="text-muted">Pending Claims</small>
                    </div>
                </a>
            </div>
        </div>

        @if($stats['pendingClaims'] > 0)
            <div class="alert alert-warning d-flex align-items-center mb-4">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div>
                    <strong>{{ $stats['pendingClaims'] }} claim(s)</strong> waiting for review. 
                    <a href="{{ route('admin.claims') }}" class="alert-link">Review now</a>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-file-text"></i> Claims Pending Review</h5>
                        <a href="{{ route('admin.claims') }}" class="btn btn-sm btn-outline-warning">View All</a>
                    </div>
                    <div class="card-body p-0">
                        @forelse($pendingClaims as $claim)
                            <div class="d-flex align-items-center p-3 border-bottom">
                                <div class="flex-grow-1">
                                    <strong>{{ $claim->foundItem->item_name }}</strong>
                                    <br><small class="text-muted">Claimed by {{ $claim->user->name }}</small>
                                </div>
                                <a href="{{ route('admin.claims.review', $claim) }}" class="btn btn-sm btn-warning">Review</a>
                            </div>
                        @empty
                            <div class="p-3 text-center text-muted">No pending claims</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-search"></i> Recent Lost Reports</h5>
                        <a href="{{ route('admin.lost-items') }}" class="btn btn-sm btn-outline-danger">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($recentLostItems as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('lost-items.show', $item) }}">{{ $item->item_name }}</a>
                                            <br><small class="text-muted">by {{ $item->user->name }}</small>
                                        </td>
                                        <td>{{ $item->category }}</td>
                                        <td>
                                            <span class="badge badge-status status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No lost reports</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="bi bi-handbag"></i> Recent Found Reports</h5>
                        <a href="{{ route('admin.found-items') }}" class="btn btn-sm btn-outline-success">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <tbody>
                                @forelse($recentFoundItems as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('found-items.show', $item) }}">{{ $item->item_name }}</a>
                                            <br><small class="text-muted">by {{ $item->user->name }}</small>
                                        </td>
                                        <td>{{ $item->category }}</td>
                                        <td>
                                            <span class="badge badge-status status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted">No found reports</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
