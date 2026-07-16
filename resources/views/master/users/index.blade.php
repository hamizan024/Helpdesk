@extends('layouts.app')

@section('title', 'Users')

@section('content')

@if(session('success'))
    <x-alert type="success" class="mb-3">{{ session('success') }}</x-alert>
@endif

@if(session('error'))
    <x-alert type="danger" class="mb-3">{{ session('error') }}</x-alert>
@endif

<x-app-card title="Users" icon="manage_accounts" :badge="$items->total()" :noPad="true">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('master.users.index') }}" class="d-flex">
                <div class="input-group input-group-sm" style="width:220px;">
                    <input type="text" name="search" value="{{ $search }}"
                           class="form-control" placeholder="Search users…">
                    <button class="btn btn-outline-secondary" type="submit">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">search</span>
                    </button>
                    @if($search)
                        <a href="{{ route('master.users.index') }}" class="btn btn-outline-secondary" title="Clear">
                            <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">close</span>
                        </a>
                    @endif
                </div>
            </form>
            <button type="button" onclick="openCreate()"
                    class="btn btn-sm text-white fw-bold"
                    style="background:linear-gradient(195deg,#EC407A,#D81B60);font-size:0.78rem;padding:6px 14px;">
                <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:middle;">add</span>
                Add
            </button>
        </div>
    </x-slot:actions>

    <div class="table-responsive">
        <table class="table table-hover mb-0" style="font-size:0.84rem;">
            <thead style="background:#fafafa;">
                <tr>
                    <th class="px-4 py-3 border-0" style="width:3rem;color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">#</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Name</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Email</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Role</th>
                    <th class="px-4 py-3 border-0 text-end" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $u)
                    <tr>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ $items->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 fw-semibold" style="color:#344767;">{{ $u->name }}</td>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ $u->email }}</td>
                        <td class="px-4 py-3">
                            @php
                                $roleColors = [
                                    'admin'      => ['bg' => '#fdecea', 'fg' => '#c62828'],
                                    'technician' => ['bg' => '#e3f2fd', 'fg' => '#1565c0'],
                                    'user'       => ['bg' => '#eceff1', 'fg' => '#455a64'],
                                ];
                                $rc = $roleColors[$u->role] ?? $roleColors['user'];
                            @endphp
                            <span class="badge" style="background:{{ $rc['bg'] }};color:{{ $rc['fg'] }};font-size:0.72rem;text-transform:capitalize;">
                                {{ $u->role }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-end">
                            <button type="button"
                                    onclick='openEdit({{ $u->id }}, @json($u->name), @json($u->email), @json($u->role))'
                                    class="btn btn-sm btn-outline-primary me-1"
                                    style="font-size:0.75rem;padding:3px 10px;">
                                Edit
                            </button>
                            @if($u->id !== auth()->id())
                                <button type="button"
                                        onclick='openDelete({{ $u->id }}, @json($u->name), {{ $u->tickets_count }}, {{ $u->assigned_tickets_count }})'
                                        class="btn btn-sm btn-outline-danger"
                                        style="font-size:0.75rem;padding:3px 10px;">
                                    Delete
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-4 text-center text-muted">
                            {{ $search ? 'No users match your search.' : 'No users yet.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-4 py-3 border-top">
            {{ $items->links() }}
        </div>
    @endif

</x-app-card>


{{-- ===== Create / Edit Modal ===== --}}
<div class="modal fade" id="formModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="dataForm" method="POST" action="">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="_edit_id" id="editId" value="">

                <div class="modal-header" style="background:linear-gradient(195deg,#42424a,#191919);border-radius:8px 8px 0 0;">
                    <h6 class="modal-title text-white fw-bold" id="formModalTitle">Add User</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-4">
                    @if($errors->any())
                        <div class="alert alert-danger py-2 px-3 mb-3" style="font-size:0.82rem;">
                            <ul class="mb-0 ps-3">
                                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Name <span class="text-danger">*</span></label>
                        <input type="text" id="fieldName" name="name"
                               value="{{ old('name') }}"
                               class="form-control form-control-sm @error('name') is-invalid @enderror"
                               placeholder="Full name" maxlength="100" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Email <span class="text-danger">*</span></label>
                        <input type="email" id="fieldEmail" name="email"
                               value="{{ old('email') }}"
                               class="form-control form-control-sm @error('email') is-invalid @enderror"
                               placeholder="name@example.com" maxlength="255" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Role <span class="text-danger">*</span></label>
                        <select id="fieldRole" name="role"
                                class="form-select form-select-sm @error('role') is-invalid @enderror" required>
                            <option value="user"       {{ old('role') == 'user'       ? 'selected' : '' }}>User</option>
                            <option value="technician" {{ old('role') == 'technician' ? 'selected' : '' }}>Technician</option>
                            <option value="admin"      {{ old('role') == 'admin'      ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold" id="fieldPasswordLabel" style="font-size:0.82rem;">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password" id="fieldPassword" name="password"
                               class="form-control form-control-sm @error('password') is-invalid @enderror"
                               placeholder="Min. 8 characters" autocomplete="new-password">
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text" id="fieldPasswordHint" style="font-size:0.72rem;"></div>
                    </div>
                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm text-white fw-bold"
                            style="background:linear-gradient(195deg,#EC407A,#D81B60);">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ===== Delete Modal ===== --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="modal-header" style="background:linear-gradient(195deg,#ef5350,#c62828);border-radius:8px 8px 0 0;">
                    <h6 class="modal-title text-white fw-bold">Confirm Delete</h6>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <span class="material-icons-round d-block mb-2" style="font-size:2.5rem;color:#ef5350;">warning_amber</span>
                    <p style="font-size:0.875rem;color:#344767;">
                        Delete <strong id="deleteItemName"></strong>?<br>
                        <span style="font-size:0.78rem;color:#7b809a;" id="deleteItemWarning"></span>
                    </p>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
const storeUrl  = '{{ route("master.users.store") }}';
const updateUrl = (id) => `/master/users/${id}`;
const deleteUrl = (id) => `/master/users/${id}`;

function openCreate() {
    document.getElementById('formModalTitle').textContent = 'Add User';
    document.getElementById('dataForm').action = storeUrl;
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('editId').value = '';
    document.getElementById('fieldName').value = '';
    document.getElementById('fieldEmail').value = '';
    document.getElementById('fieldRole').value = 'user';
    document.getElementById('fieldPassword').value = '';
    document.getElementById('fieldPassword').setAttribute('required', 'required');
    document.getElementById('fieldPasswordLabel').innerHTML = 'Password <span class="text-danger">*</span>';
    document.getElementById('fieldPasswordHint').textContent = '';
    new bootstrap.Modal(document.getElementById('formModal')).show();
}

function openEdit(id, name, email, role) {
    document.getElementById('formModalTitle').textContent = 'Edit User';
    document.getElementById('dataForm').action = updateUrl(id);
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('editId').value = id;
    document.getElementById('fieldName').value = name;
    document.getElementById('fieldEmail').value = email;
    document.getElementById('fieldRole').value = role;
    document.getElementById('fieldPassword').value = '';
    document.getElementById('fieldPassword').removeAttribute('required');
    document.getElementById('fieldPasswordLabel').textContent = 'Password';
    document.getElementById('fieldPasswordHint').textContent = 'Leave blank to keep the current password.';
    new bootstrap.Modal(document.getElementById('formModal')).show();
}

function openDelete(id, name, ticketsCreated, ticketsAssigned) {
    document.getElementById('deleteItemName').textContent = name;
    let warning = 'This action cannot be undone.';
    if (ticketsCreated > 0) {
        warning = `This will also delete ${ticketsCreated} ticket(s) created by this user. ` + warning;
    } else if (ticketsAssigned > 0) {
        warning = `${ticketsAssigned} ticket(s) currently assigned to this user will become unassigned. ` + warning;
    }
    document.getElementById('deleteItemWarning').textContent = warning;
    document.getElementById('deleteForm').action = deleteUrl(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', function () {
    @if(old('_method') === 'PUT')
        openEdit(
            {{ (int) old('_edit_id', 0) }},
            @json(old('name', '')),
            @json(old('email', '')),
            @json(old('role', 'user'))
        );
    @else
        openCreate();
    @endif
});
@endif
</script>
@endsection
