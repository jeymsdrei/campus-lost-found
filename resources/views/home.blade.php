<x-app-layout>
    <div class="container py-4">
        <div class="page-header text-center">
            <h1 class="mb-2"><img src="{{ asset('images/logo.png') }}" alt="Lost & Found" style="width: 50px; height: 50px; vertical-align: middle; margin-right: 10px;">Welcome to Campus Lost & Found</h1>
            <p class="mb-0 opacity-75">Help reunite lost items with their owners. Report lost items or help identify found items.</p>
            <div class="mt-3">
                <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">
                    <i class="bi bi-person-plus me-1"></i> Register
                </a>
            </div>
        </div>

        <div class="row mb-4 g-4">
            <div class="col-md-3">
                <div class="stat-card primary h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-search"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 opacity-75">Total Lost</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['totalLost'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card success h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-handbag"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 opacity-75">Total Found</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['totalFound'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card warning h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 opacity-75">Unclaimed</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['unclaimed'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card danger h-100">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon me-3">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 opacity-75">Pending</h6>
                            <h2 class="mb-0 fw-bold">{{ $stats['pendingClaims'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h4 class="mb-0"><i class="bi bi-search text-danger me-2"></i>Recent Lost Items</h4>
                    </div>
                    <div class="card-body">
                        @forelse($recentLostItems as $item)
                            <div class="d-flex align-items-center mb-3 p-3 rounded" style="background: #fef2f2;">
                                <div class="lost-icon me-3">
                                    <i class="bi bi-search"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $item->item_name }}</h6>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $item->location }} • {{ $item->date_lost->format('M d, Y') }}</small>
                                </div>
                                <a href="{{ route('lost-items.show', $item) }}" class="btn btn-sm btn-outline-danger rounded-pill">View</a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-2">No lost items reported yet</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('lost-items.index') }}" class="btn btn-outline-danger w-100 rounded-pill">
                            <i class="bi bi-arrow-right me-2"></i>View All Lost Items
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-transparent border-0 pt-4 pb-0">
                        <h4 class="mb-0"><i class="bi bi-handbag text-success me-2"></i>Recent Found Items</h4>
                    </div>
                    <div class="card-body">
                        @forelse($recentFoundItems as $item)
                            <div class="d-flex align-items-center mb-3 p-3 rounded" style="background: #ecfdf5;">
                                <div class="found-icon me-3">
                                    <i class="bi bi-handbag"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-semibold">{{ $item->item_name }}</h6>
                                    <small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $item->location }} • {{ $item->date_found->format('M d, Y') }}</small>
                                </div>
                                <a href="{{ route('found-items.show', $item) }}" class="btn btn-sm btn-outline-success rounded-pill">View</a>
                            </div>
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-handbag text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                                <p class="text-muted mt-2">No found items reported yet</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-transparent border-0">
                        <a href="{{ route('found-items.index') }}" class="btn btn-outline-success w-100 rounded-pill">
                            <i class="bi bi-arrow-right me-2"></i>View All Found Items
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card text-center p-5" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                    <h3 class="mb-3"><i class="bi bi-question-circle me-2"></i>Lost or Found Something?</h3>
                    <p class="mb-4">Join our campus community and help reunite lost items with their owners. It's quick and easy!</p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('lost-items.create') }}" class="btn btn-light btn-lg">
                            <i class="bi bi-search me-2"></i>Report Lost Item
                        </a>
                        <a href="{{ route('found-items.create') }}" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-handbag me-2"></i>Report Found Item
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 3rem 2rem;
            border-radius: 20px;
            color: white;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255,255,255,0.2);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        
        .card {
            border-radius: 16px;
            overflow: hidden;
        }
        
        .lost-icon {
            width: 45px;
            height: 45px;
            background: #fee2e2;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
        }
        
        .found-icon {
            width: 45px;
            height: 45px;
            background: #d1fae5;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #059669;
        }
    </style>
</x-app-layout>