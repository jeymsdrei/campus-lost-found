<x-app-layout>
    <div class="container py-4">
        <h2 class="mb-4"><i class="bi bi-people"></i> Manage Users</h2>
        
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
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->department ?? '-' }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">User</span>
                                    @endif
                                </td>
                                <td>{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    @if($user->id !== auth()->id())
                                        <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-{{ $user->role === 'admin' ? 'outline-secondary' : 'outline-primary' }}">
                                                {{ $user->role === 'admin' ? 'Remove Admin' : 'Make Admin' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted">Current User</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $users->links() }}
        </div>
    </div>
</x-app-layout>
