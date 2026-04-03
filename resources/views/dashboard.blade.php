<x-app-layout>
    <div class="container py-4">
        <h2 class="mb-4">My Dashboard</h2>

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
                    <div class="card text-center p-3 border-primary">
                        <i class="bi bi-plus-circle text-primary" style="font-size: 2rem;"></i>
                        <h4 class="mt-2 mb-0">Report</h4>
                        <small class="text-muted">Lost or Found Item</small>
                    </div>
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('lost-items.create') }}" class="btn btn-danger w-100">
                                    <i class="bi bi-search"></i> Report Lost Item
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('found-items.create') }}" class="btn btn-success w-100">
                                    <i class="bi bi-handbag"></i> Report Found Item
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('lost-items.index') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-search"></i> Browse Lost Items
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('found-items.index') }}" class="btn btn-outline-success w-100">
                                    <i class="bi bi-handbag"></i> Browse Found Items
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('claims.my-claims') }}" class="btn btn-outline-warning w-100">
                                    <i class="bi bi-file-text"></i> View My Claims
                                </a>
                            </div>
                            @if(Auth::user()->isAdmin())
                                <div class="col-md-4 mb-3">
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-100">
                                        <i class="bi bi-shield-lock"></i> Admin Panel
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
