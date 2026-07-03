@extends('layouts.app')

@section('title', 'Edit Ticket')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-7">
        <div class="card">

            <div class="card-header py-3 px-4"
                 style="background: linear-gradient(195deg, #42424a, #191919); border-radius: 12px 12px 0 0;">
                <h6 class="text-white mb-0 fw-bold">Edit Ticket</h6>
            </div>

            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li style="font-size:0.875rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text"
                               name="title"
                               class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $ticket->title) }}"
                               placeholder="Enter ticket title">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description"
                                  class="form-control @error('description') is-invalid @enderror"
                                  rows="5"
                                  placeholder="Describe the issue in detail">{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select @error('priority') is-invalid @enderror">
                            <option value="Low"    {{ old('priority', $ticket->priority) == 'Low'    ? 'selected' : '' }}>Low</option>
                            <option value="Medium" {{ old('priority', $ticket->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                            <option value="High"   {{ old('priority', $ticket->priority) == 'High'   ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="Open"        {{ old('status', $ticket->status) == 'Open'        ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Closed"      {{ old('status', $ticket->status) == 'Closed'      ? 'selected' : '' }}>Closed</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Assign Technician</label>
                        <select name="assigned_to" class="form-select @error('assigned_to') is-invalid @enderror">
                            <option value="">-- Select Technician --</option>
                            @foreach ($technicians as $tech)
                                <option value="{{ $tech->id }}"
                                    {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>
                                    {{ $tech->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('assigned_to')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit"
                                class="btn btn-sm px-4 py-2 text-white fw-bold bg-gradient-success">
                            Update Ticket
                        </button>
                        <a href="{{ route('tickets.index') }}"
                           class="btn btn-sm px-4 py-2 bg-gradient-secondary text-white">
                            Cancel
                        </a>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

@endsection
