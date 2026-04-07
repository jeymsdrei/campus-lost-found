<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-people-fill"></i> User Management</h2>
                <p class="text-muted mb-0">Manage user roles and view by department</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $users->total() }} Users</span>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select">
                    <option value="">All Departments</option>
                    @php
                    $departments = ['Administration', 'Business Administration', 'Computer Science', 'Economics', 'Education', 'Engineering', 'History', 'Information Technology', 'Law', 'Literature', 'Mathematics', 'Medicine', 'Music', 'Other', 'Physics', 'Psychology'];
                    @endphp
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th><i class="bi bi-person me-1"></i> Name</th>
                            <th><i class="bi bi-envelope me-1"></i> Email</th>
                            <th><i class="bi bi-building me-1"></i> Department</th>
                            <th><i class="bi bi-shield me-1"></i> Role</th>
                            <th><i class="bi bi-calendar me-1"></i> Joined</th>
                            <th><i class="bi bi-gear me-1"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->id === auth()->id() ? 'table-primary' : '' }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-2">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <strong>{{ $user->name }}</strong>
                                        @if($user->id === auth()->id())
                                            <span class="badge bg-info ms-2">You</span>
                                        @endif
                                    </div>
                                </td>
                                <td><i class="bi bi-envelope text-muted me-1"></i>{{ $user->email }}</td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $user->department ?? '-' }}</span>
                                </td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-gradient" style="background: linear-gradient(135deg, #667eea, #764ba2);"><i class="bi bi-shield-lock-fill"></i> Admin</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="bi bi-person"></i> User</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small></td>
                                <td>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->role === 'admin' ? 'btn-outline-danger' : 'btn-outline-primary' }}">
                                                @if($user->role === 'admin')
                                                    <i class="bi bi-person-dash"></i> Remove
                                                @else
                                                    <i class="bi bi-person-plus"></i> Make Admin
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted"><i class="bi bi-check-circle"></i> Current</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-search" style="font-size: 2rem;"></i>
                                    <p class="mt-2">No users found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>

    <style>
        .avatar-circle {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>
</x-app-layout>