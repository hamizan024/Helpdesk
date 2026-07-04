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

                <form action="{{ route('tickets.store') }}" method="POST">
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

                    <x-form-select label="Priority" name="priority" class="mb-4">
                        <option value="" disabled {{ old('priority') ? '' : 'selected' }}>-- Select Priority --</option>
                        <option value="Low"    {{ old('priority') == 'Low'    ? 'selected' : '' }}>Low</option>
                        <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
                        <option value="High"   {{ old('priority') == 'High'   ? 'selected' : '' }}>High</option>
                    </x-form-select>

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
