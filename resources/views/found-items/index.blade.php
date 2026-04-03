<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-handbag"></i> Found Items</h2>
            @auth
                <a href="{{ route('found-items.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Report Found Item
                </a>
            @endauth
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or description..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="category" class="form-select">
                    <option value="">All Categories</option>
                    @foreach(['Electronics', 'Books', 'Bags', 'Clothing', 'Accessories', 'Documents', 'Jewelry', 'Others'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-success w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('found-items.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>

        @if($foundItems->isEmpty())
            <div class="empty-state card">
                <i class="bi bi-handbag" style="font-size: 4rem;"></i>
                <h4 class="mt-3">No Found Items</h4>
                <p class="text-muted">No found items match your search criteria.</p>
            </div>
        @else
            <div class="row">
                @foreach($foundItems as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card card-found h-100">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->item_name }}" class="item-image">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center item-image">
                                    <i class="bi bi-handbag text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $item->item_name }}</h5>
                                    <span class="badge badge-status status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                                </div>
                                <p class="card-text text-muted small">{{ Str::limit($item->description, 100) }}</p>
                                <div class="small">
                                    <p class="mb-1"><i class="bi bi-tag"></i> {{ $item->category }}</p>
                                    <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $item->location }}</p>
                                    <p class="mb-1"><i class="bi bi-calendar"></i> {{ $item->date_found->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="card-footer bg-white border-0">
                                <a href="{{ route('found-items.show', $item) }}" class="btn btn-outline-success btn-sm w-100">View Details</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center">
                {{ $foundItems->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
