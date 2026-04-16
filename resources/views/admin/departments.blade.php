<x-app-layout>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-1"><i class="bi bi-building me-2"></i>Department Management</h4>
                <p class="text-muted mb-0 small">Manage departments for the registration system</p>
            </div>
            <span class="badge bg-primary">{{ $departments->total() }} Departments</span>
        </div>

        <form method="GET" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search departments..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.departments') }}" class="btn btn-secondary btn-sm w-100">Reset</a>
            </div>
        </form>

        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Add New Department</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.departments.store') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label small fw-bold">Department Name *</label>
                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" placeholder="e.g., Computer Science" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="code" class="form-label small fw-bold">Code (Optional)</label>
                                <input type="text" class="form-control form-control-sm" name="code" placeholder="e.g., CS" maxlength="20">
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                <i class="bi bi-plus me-1"></i>Add Department
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="small">Code</th>
                                    <th class="small">Name</th>
                                    <th class="small">Users</th>
                                    <th class="small">Status</th>
                                    <th class="small text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $dept)
                                    <tr class="{{ !$dept->is_active ? 'table-secondary' : '' }}">
                                        <td>
                                            <span class="badge bg-secondary small">{{ $dept->code ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <strong class="small">{{ $dept->name }}</strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border small">{{ $dept->users_count ?? 0 }} users</span>
                                        </td>
                                        <td>
                                            @if($dept->is_active)
                                                <span class="badge bg-success small">Active</span>
                                            @else
                                                <span class="badge bg-secondary small">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" data-id="{{ $dept->id }}" data-name="{{ addslashes($dept->name) }}" data-code="{{ $dept->code ?? '' }}" onclick="openEditModal(this)" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-{{ $dept->is_active ? 'warning' : 'success' }}" data-id="{{ $dept->id }}" data-active="{{ $dept->is_active }}" data-name="{{ addslashes($dept->name) }}" onclick="openToggleModal(this)" title="{{ $dept->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="bi bi-{{ $dept->is_active ? 'x-circle' : 'check-circle' }}"></i>
                                                </button>
                                                @if(($dept->users_count ?? 0) > 0)
                                                    <button class="btn btn-outline-secondary" disabled title="Has assigned users">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-outline-danger" data-id="{{ $dept->id }}" data-name="{{ addslashes($dept->name) }}" onclick="deleteDept(this)" title="Delete">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                            <form method="POST" action="{{ route('admin.departments.destroy', $dept) }}" id="delete-form-{{ $dept->id }}" class="d-none">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                            <form method="POST" action="{{ route('admin.departments.update', $dept) }}" id="edit-form-{{ $dept->id }}" class="d-none">
                                                @csrf
                                                @method('PATCH')
                                                <input type="text" name="name" id="edit-name-{{ $dept->id }}">
                                                <input type="text" name="code" id="edit-code-{{ $dept->id }}">
                                            </form>
                                            <form method="POST" action="{{ route('admin.departments.toggle', $dept) }}" id="toggle-form-{{ $dept->id }}" class="d-none">
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            <i class="bi bi-building" style="font-size: 2rem;"></i>
                                            <p class="mt-2 mb-0 small">No departments found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($departments->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                @if($departments->onFirstPage())
                                    <li class="page-item disabled"><span class="page-link">Prev</span></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $departments->previousPageUrl() }}">Prev</a></li>
                                @endif
                                @foreach($departments->getUrlRange(1, $departments->lastPage()) as $page => $url)
                                    <li class="page-item {{ $page == $departments->currentPage() ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach
                                @if($departments->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $departments->nextPageUrl() }}">Next</a></li>
                                @else
                                    <li class="page-item disabled"><span class="page-link">Next</span></li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h6 class="modal-title"><i class="bi bi-pencil me-2"></i>Edit Department</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="editModalForm">
                    @csrf
                    @method('PATCH')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Department Name</label>
                            <input type="text" class="form-control form-control-sm" name="name" id="modal-dept-name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Code</label>
                            <input type="text" class="form-control form-control-sm" name="code" id="modal-dept-code" maxlength="20">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="toggleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header" id="toggleModalHeader">
                    <h6 class="modal-title" id="toggleModalTitle"></h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p class="mb-0" id="toggleModalText"></p>
                </div>
                <div class="modal-footer" id="toggleModalFooter">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm" id="toggleModalBtn">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function openEditModal(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;
            const code = button.dataset.code;
            document.getElementById('modal-dept-name').value = name;
            document.getElementById('modal-dept-code').value = code;
            document.getElementById('editModalForm').action = '/admin/departments/' + id;
            new bootstrap.Modal(document.getElementById('editModal')).show();
        }

        function openToggleModal(button) {
            const id = button.dataset.id;
            const isActive = button.dataset.active === '1';
            const name = button.dataset.name;
            const modal = new bootstrap.Modal(document.getElementById('toggleModal'));
            const header = document.getElementById('toggleModalHeader');
            const title = document.getElementById('toggleModalTitle');
            const text = document.getElementById('toggleModalText');
            const btn = document.getElementById('toggleModalBtn');
            const form = document.getElementById('toggle-form-' + id);

            if (isActive) {
                header.className = 'modal-header bg-warning text-dark';
                title.innerHTML = '<i class="bi bi-x-circle me-2"></i>Deactivate Department';
                text.innerHTML = 'Are you sure you want to deactivate <strong>' + name + '</strong>?';
                btn.className = 'btn btn-warning btn-sm';
                btn.textContent = 'Deactivate';
            } else {
                header.className = 'modal-header bg-success text-white';
                title.innerHTML = '<i class="bi bi-check-circle me-2"></i>Activate Department';
                text.innerHTML = 'Are you sure you want to activate <strong>' + name + '</strong>?';
                btn.className = 'btn btn-success btn-sm';
                btn.textContent = 'Activate';
            }

            btn.onclick = function() {
                form.submit();
            };

            modal.show();
        }

        function deleteDept(button) {
            const id = button.dataset.id;
            const name = button.dataset.name;
            Swal.fire({
                title: 'Delete Department',
                text: 'Are you sure you want to delete "' + name + '"? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }
    </script>
</x-app-layout>
