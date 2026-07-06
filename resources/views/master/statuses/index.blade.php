@extends('layouts.app')

@section('title', 'Statuses')

@section('content')

@if(session('success'))
    <x-alert type="success" class="mb-3">{{ session('success') }}</x-alert>
@endif

<x-app-card title="Statuses" icon="label" :badge="$items->total()" :noPad="true">
    <x-slot:actions>
        <div class="d-flex align-items-center gap-2">
            <form method="GET" action="{{ route('master.statuses.index') }}" class="d-flex">
                <div class="input-group input-group-sm" style="width:200px;">
                    <input type="text" name="search" value="{{ $search }}"
                           class="form-control" placeholder="Search…">
                    <button class="btn btn-outline-secondary" type="submit">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">search</span>
                    </button>
                    @if($search)
                        <a href="{{ route('master.statuses.index') }}" class="btn btn-outline-secondary" title="Clear">
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
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Color</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Default</th>
                    <th class="px-4 py-3 border-0" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Status</th>
                    <th class="px-4 py-3 border-0 text-end" style="color:#7b809a;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($items as $status)
                    <tr>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ $items->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3">
                            <span class="badge bg-{{ $status->color }}" style="font-size:0.78rem;">
                                {{ $status->name }}
                            </span>
                        </td>
                        <td class="px-4 py-3" style="color:#7b809a;">{{ ucfirst($status->color) }}</td>
                        <td class="px-4 py-3">
                            @if($status->is_default)
                                <span class="badge" style="background:#e3f2fd;color:#1565c0;font-size:0.72rem;">Default</span>
                            @else
                                <span style="color:#adb5bd;font-size:0.78rem;">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($status->is_active)
                                <span class="badge" style="background:#e8f5e9;color:#2e7d32;font-size:0.72rem;">Active</span>
                            @else
                                <span class="badge" style="background:#fdecea;color:#c62828;font-size:0.72rem;">Inactive</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-end">
                            <button type="button"
                                    onclick="openEdit({{ $status->id }}, @json($status->name), @json($status->color), {{ $status->is_default ? 'true' : 'false' }}, {{ $status->is_active ? 'true' : 'false' }})"
                                    class="btn btn-sm btn-outline-primary me-1"
                                    style="font-size:0.75rem;padding:3px 10px;">
                                Edit
                            </button>
                            <button type="button"
                                    onclick="openDelete({{ $status->id }}, @json($status->name))"
                                    class="btn btn-sm btn-outline-danger"
                                    style="font-size:0.75rem;padding:3px 10px;">
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-4 text-center text-muted">
                            {{ $search ? 'No statuses match your search.' : 'No statuses yet.' }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($items->hasPages())
        <div class="px-4 py-3 border-top">{{ $items->links() }}</div>
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
                    <h6 class="modal-title text-white fw-bold" id="formModalTitle">Add Status</h6>
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
                               placeholder="e.g. In Progress" maxlength="50" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Color <span class="text-danger">*</span></label>
                        <select id="fieldColor" name="color"
                                class="form-select form-select-sm @error('color') is-invalid @enderror" required>
                            @foreach(['info'=>'Info (Cyan)','primary'=>'Primary (Blue)','success'=>'Success (Green)','warning'=>'Warning (Yellow)','danger'=>'Danger (Red)','secondary'=>'Secondary (Grey)','dark'=>'Dark'] as $val => $label)
                                <option value="{{ $val }}" {{ old('color') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-check form-switch mb-2">
                        <input class="form-check-input" type="checkbox" id="fieldDefault" name="is_default" value="1"
                               {{ old('is_default') ? 'checked' : '' }}>
                        <label class="form-check-label" for="fieldDefault" style="font-size:0.82rem;">
                            Set as default status
                            <small class="text-muted d-block" style="font-size:0.72rem;">Only one status can be the default.</small>
                        </label>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="fieldActive" name="is_active" value="1"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label class="form-check-label" for="fieldActive" style="font-size:0.82rem;">Active</label>
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
                        <span style="font-size:0.78rem;color:#7b809a;">This action cannot be undone.</span>
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
const storeUrl  = '{{ route("master.statuses.store") }}';
const updateUrl = (id) => `/master/statuses/${id}`;
const deleteUrl = (id) => `/master/statuses/${id}`;

function openCreate() {
    document.getElementById('formModalTitle').textContent = 'Add Status';
    document.getElementById('dataForm').action = storeUrl;
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('editId').value = '';
    document.getElementById('fieldName').value = '';
    document.getElementById('fieldColor').value = 'secondary';
    document.getElementById('fieldDefault').checked = false;
    document.getElementById('fieldActive').checked = true;
    new bootstrap.Modal(document.getElementById('formModal')).show();
}

function openEdit(id, name, color, isDefault, isActive) {
    document.getElementById('formModalTitle').textContent = 'Edit Status';
    document.getElementById('dataForm').action = updateUrl(id);
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('editId').value = id;
    document.getElementById('fieldName').value = name;
    document.getElementById('fieldColor').value = color;
    document.getElementById('fieldDefault').checked = isDefault;
    document.getElementById('fieldActive').checked = isActive;
    new bootstrap.Modal(document.getElementById('formModal')).show();
}

function openDelete(id, name) {
    document.getElementById('deleteItemName').textContent = name;
    document.getElementById('deleteForm').action = deleteUrl(id);
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

@if($errors->any())
document.addEventListener('DOMContentLoaded', function () {
    @if(old('_method') === 'PUT')
        openEdit(
            {{ (int) old('_edit_id', 0) }},
            @json(old('name', '')),
            @json(old('color', 'secondary')),
            {{ old('is_default') ? 'true' : 'false' }},
            {{ old('is_active') ? 'true' : 'false' }}
        );
    @else
        openCreate();
    @endif
});
@endif
</script>
@endsection