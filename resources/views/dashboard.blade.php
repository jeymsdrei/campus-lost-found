<x-app-layout>
    @php
    use Illuminate\Support\Facades\Auth;
    @endphp
    <div class="container py-4">
        <h2 class="mb-4">Welcome, {{ Auth::user()->name }}</h2>

        <div class="row mb-4">
            <div class="col-md-3">
                <a href="{{ route('lost-items.my-items') }}" class="text-decoration-none">
                    <div class="card text-center p-3">
                        <i class="bi bi-search text-danger" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-0">{{ Auth::user()->lostItems()->count() }}</h4>
                        <small class="text-muted">My Lost Reports</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('found-items.my-items') }}" class="text-decoration-none">
                    <div class="card text-center p-3">
                        <i class="bi bi-handbag text-success" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-0">{{ Auth::user()->foundItems()->count() }}</h4>
                        <small class="text-muted">My Found Reports</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('claims.my-claims') }}" class="text-decoration-none">
                    <div class="card text-center p-3">
                        <i class="bi bi-file-text text-warning" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-0">{{ Auth::user()->claimRequests()->count() }}</h4>
                        <small class="text-muted">My Claims</small>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('lost-items.create') }}" class="text-decoration-none">
                    <div class="card text-center p-3 border-danger">
                        <i class="bi bi-plus-circle text-danger" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-0">Report Lost</h4>
                        <small class="text-muted">Create Report</small>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-search"></i> Report Lost Item</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Lost something on campus? Submit a report to help find it.</p>
                        <a href="{{ route('lost-items.create') }}" class="btn btn-danger w-100">
                            <i class="bi bi-plus-circle"></i> Report Lost Item
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-handbag"></i> Report Found Item</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Found something? Help reunite it with its owner.</p>
                        <a href="{{ route('found-items.create') }}" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle"></i> Report Found Item
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-search"></i> Browse Lost Items</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Search through reported lost items to see if someone found your item.</p>
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-danger w-100">
                            <i class="bi bi-search"></i> View Lost Items
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-handbag"></i> Browse Found Items</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Check found items to claim something that belongs to you.</p>
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline-success w-100">
                            <i class="bi bi-handbag"></i> View Found Items
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Activity</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>Type</th>
                                        <th>Item</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(Auth::user()->lostItems()->take(3)->get() as $item)
                                        <tr>
                                            <td><span class="badge bg-danger">Lost</span></td>
                                            <td>{{ $item->item_name }}</td>
                                            <td><span class="badge badge-status status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></td>
                                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                    @forelse(Auth::user()->foundItems()->take(3)->get() as $item)
                                        <tr>
                                            <td><span class="badge bg-success">Found</span></td>
                                            <td>{{ $item->item_name }}</td>
                                            <td><span class="badge badge-status status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></td>
                                            <td>{{ $item->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @endforeach
                                    @if(Auth::user()->lostItems()->count() == 0 && Auth::user()->foundItems()->count() == 0)
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">No activity yet. Start by reporting a lost or found item!</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
