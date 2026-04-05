<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-people"></i> User Management</h2>
                <p class="text-muted mb-0">Manage user roles and permissions</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $users->total() }} Users</span>
        </div>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Department</th>
                            <th>Role</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                            <tr class="{{ $user->id === auth()->id() ? 'table-primary' : '' }}">
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-info ms-1">You</span>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department ?? '-' }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-primary"><i class="bi bi-shield-lock"></i> Admin</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $user->role === 'admin' ? 'outline-danger' : 'outline-primary' }}">
                                                @if($user->role === 'admin')
                                                    <i class="bi bi-person-dash"></i> Remove Admin
                                                @else
                                                    <i class="bi bi-person-plus"></i> Make Admin
                                                @endif
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Current User</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No users found</td>
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
</x-app-layout>
