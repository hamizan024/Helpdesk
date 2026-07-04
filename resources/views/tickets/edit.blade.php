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

                <x-form-select label="Priority" name="priority">
                    <option value="Low"    {{ old('priority', $ticket->priority) == 'Low'    ? 'selected' : '' }}>Low</option>
                    <option value="Medium" {{ old('priority', $ticket->priority) == 'Medium' ? 'selected' : '' }}>Medium</option>
                    <option value="High"   {{ old('priority', $ticket->priority) == 'High'   ? 'selected' : '' }}>High</option>
                </x-form-select>

                <x-form-select label="Status" name="status">
                    <option value="Open"        {{ old('status', $ticket->status) == 'Open'        ? 'selected' : '' }}>Open</option>
                    <option value="In Progress" {{ old('status', $ticket->status) == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="Closed"      {{ old('status', $ticket->status) == 'Closed'      ? 'selected' : '' }}>Closed</option>
                </x-form-select>

                <x-form-select label="Assign Technician" name="assigned_to">
                    <option value="">-- Select Technician --</option>
                    @foreach ($technicians as $tech)
                        <option value="{{ $tech->id }}"
                            {{ old('assigned_to', $ticket->assigned_to) == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </x-form-select>

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

        </x-app-card>

    </div>
</div>

@endsection
