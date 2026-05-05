<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1"><i class="bi bi-people me-2"></i>User Management</h4>
                <p class="text-muted mb-0 small">List of registered users by department</p>
            </div>
            <span class="badge bg-primary">{{ $users->total() }} Users</span>
        </div>

        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="department" class="form-select form-select-sm">
                    <option value="">All Departments</option>
                    @php
                    $departments = \App\Models\Department::where('is_active', true)->orderBy('name', 'asc')->get();
                    @endphp
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ request('department') == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Search</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        @php
            $groupedUsers = $users->getCollection()->groupBy(function($user) {
                return $user->department ?? 'No Department';
            });
        @endphp

        @forelse($groupedUsers as $deptName => $deptUsers)
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0"><i class="bi bi-building me-2"></i>{{ $deptName }}</h5>
                    <span class="badge bg-secondary">{{ $deptUsers->count() }} user(s)</span>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small">Name</th>
                                    <th class="small">Year Level</th>
                                    <th class="small">Semester</th>
                                    <th class="small text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deptUsers as $user)
                                    <tr>
                                        <td>
                                            <strong class="small">{{ $user->name }}</strong>
                                            <br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </td>
                                        <td>
                                            @if($user->year_level)
                                                <span class="badge bg-primary">{{ $user->year_level }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->semester)
                                                <span class="badge bg-info">{{ $user->semester }}</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteUser({{ $user->id }})" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" id="delete-user-form-{{ $user->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @empty
            <div class="card shadow-sm">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">No registered users found</p>
                </div>
            </div>
        @endforelse

        @if($users->hasPages())
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deleteUser(id) {
            Swal.fire({
                title: 'Delete User',
                text: 'Are you sure you want to delete this user? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-user-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
