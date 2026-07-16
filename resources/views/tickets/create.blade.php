@extends('layouts.app')

@section('title', 'Create Ticket')

@section('content')

    <div class="row justify-content-center">
        <div class="col-lg-7">

            <x-app-card title="Create Ticket">

                @if ($errors->any())
                    <x-alert>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li style="font-size:0.875rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <x-form-input
                        label="Title"
                        name="title"
                        :value="old('title')"
                        placeholder="Enter ticket title" />

                    <x-form-textarea
                        label="Description"
                        name="description"
                        :value="old('description')"
                        placeholder="Describe the issue in detail" />

                    <div class="row g-3 mb-1">
                        <div class="col-sm-12">
                            <x-form-select label="Category" name="category_id">
                                <option value="">-- No Category --</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </x-form-select>
                        </div>
                    </div>
                    <p class="text-muted mb-4" style="font-size:0.78rem;">
                        <span class="material-icons-round" style="font-size:0.9rem;vertical-align:middle;">info</span>
                        Priority dan teknisi penanggung jawab ditentukan otomatis berdasarkan kategori yang dipilih.
                    </p>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">Due Date <span class="text-muted">(optional)</span></label>
                        <input type="date" name="due_date"
                               value="{{ old('due_date') }}"
                               class="form-control form-control-sm @error('due_date') is-invalid @enderror"
                               min="{{ now()->format('Y-m-d') }}">
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold" style="font-size:0.82rem;">
                            Attachments <span class="text-muted">(optional, max 5 files, 10 MB each)</span>
                        </label>
                        <input type="file" name="attachments[]" multiple
                               class="form-control form-control-sm @error('attachments.*') is-invalid @enderror"
                               accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar,.txt">
                        @error('attachments.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        @error('attachments')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                                class="btn btn-sm px-4 py-2 text-white fw-bold bg-gradient-success">
                            Save Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}"
                           class="btn btn-sm px-4 py-2 bg-gradient-secondary text-white">
                            Cancel
                        </a>
                    </div>

                </form>

            </x-app-card>

        </div>
    </div>

@endsection