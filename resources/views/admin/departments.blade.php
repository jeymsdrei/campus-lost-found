<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1"><i class="bi bi-building-fill"></i> Department Management</h2>
                <p class="text-muted mb-0">Manage departments for the registration system</p>
            </div>
            <span class="badge bg-primary fs-6">{{ $departments->total() }} Departments</span>
        </div>

        <form method="GET" class="row g-3 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search departments..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-funnel"></i> Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.departments') }}" class="btn btn-outline-secondary w-100"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
            </div>
        </form>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-transparent border-0">
                        <h5 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Department</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.departments.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">Department Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="e.g., Computer Science" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label">Code (Optional)</label>
                                <input type="text" class="form-control" name="code" placeholder="e.g., CS" maxlength="20">
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-plus-lg me-2"></i>Add Department
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th><i class="bi bi-hash me-1"></i> Code</th>
                                    <th><i class="bi bi-building me-1"></i> Name</th>
                                    <th><i class="bi bi-people me-1"></i> Users</th>
                                    <th><i class="bi bi-toggle-on me-1"></i> Status</th>
                                    <th><i class="bi bi-gear me-1"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $dept)
                                    <tr class="{{ !$dept->is_active ? 'table-secondary' : '' }}">
                                        <td>
                                            <span class="badge bg-secondary">{{ $dept->code ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $dept->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $dept->users_count ?? 0 }} users</span>
                                        </td>
                                        <td>
                                            @if($dept->is_active)
                                                <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                                            @else
                                                <span class="badge bg-secondary"><i class="bi bi-x-circle me-1"></i>Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-gear"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#editModal{{ $dept->id }}">
                                                            <i class="bi bi-pencil me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.departments.toggle', $dept) }}">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                @if($dept->is_active)
                                                                    <i class="bi bi-toggle-off me-2"></i>Deactivate
                                                                @else
                                                                    <i class="bi bi-toggle-on me-2"></i>Activate
                                                                @endif
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" onsubmit="return confirm('Are you sure?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item text-danger" {{ ($dept->users_count ?? 0) > 0 ? 'disabled' : '' }}>
                                                                <i class="bi bi-trash me-2"></i>Delete
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="modal fade" id="editModal{{ $dept->id }}" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Edit Department</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <form method="POST" action="{{ route('admin.departments.update', $dept) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Department Name</label>
                                                                    <input type="text" class="form-control" name="name" value="{{ $dept->name }}" required>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label class="form-label">Code</label>
                                                                    <input type="text" class="form-control" name="code" value="{{ $dept->code }}" maxlength="20">
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-building" style="font-size: 2rem;"></i>
                                            <p class="mt-2">No departments found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $departments->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>