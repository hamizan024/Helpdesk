@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">

        <x-app-card title="Edit Ticket">

            @if ($errors->any())
                <x-alert>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li style="font-size:0.875rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </x-alert>
            @endif

            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-form-input
                    label="Title"
                    name="title"
                    :value="old('title', $ticket->title)"
                    placeholder="Enter ticket title" />

                <x-form-textarea
                    label="Description"
                    name="description"
                    :value="old('description', $ticket->description)"
                    placeholder="Describe the issue in detail" />

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <x-form-select label="Priority" name="priority">
                            <option value="Low"    {{ old('priority', $ticket->priority) == 'Low'    ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', $ticket->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High"   {{ old('priority', $ticket->priority) == 'High'   ? 'selected' : '' }}>High</option>
                        </x-form-select>
                    </div>
                    <div class="col-sm-6">
                        <x-form-select label="Status" name="status">
                            <option value="Open"        {{ old('status', $ticket->status) == 'Open'        ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed"      {{ old('status', $ticket->status) == 'Closed'      ? 'selected' : '' }}>Closed</option>
                        </x-form-select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <x-form-select label="Assign Technician" name="assigned_to">
                            <option value="">-- Unassigned --</option>
                            @foreach ($technicians as $tech)
                                <option value="{{ $tech->id }}"
                                    {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </x-form-select>
                    </div>
                    <div class="col-sm-6">
                        <x-form-select label="Category" name="category_id">
                            <option value="">-- No Category --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                    {{ old('category_id', $ticket->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </x-form-select>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label fw-semibold" style="font-size:0.82rem;">Due Date</label>
                    <input type="date" name="due_date"
                           value="{{ old('due_date', $ticket->due_date?->format('Y-m-d')) }}"
                           class="form-control form-control-sm @error('due_date') is-invalid @enderror">
                    @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            class="btn btn-sm px-4 py-2 text-white fw-bold bg-gradient-success">
                        Update Ticket
                    </button>
                    <a href="{{ route('tickets.show', $ticket->id) }}"
                       class="btn btn-sm px-4 py-2 bg-gradient-secondary text-white">
                        Cancel
                    </a>
                </div>

            </form>

        </x-app-card>

    </div>
</div>

@endsection
