@extends('layouts.app')

@section('title', '403 — Access Denied')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 text-center py-5">
        <div class="mb-4" style="font-size:5rem; line-height:1; color:#E53935; font-weight:800;">403</div>
        <h4 class="fw-bold mb-2" style="color:#344767;">Access Denied</h4>
        <p class="text-muted mb-4">You do not have permission to view this page.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-4">
            <span class="material-icons-round me-1" style="font-size:.9rem; vertical-align:middle;">home</span>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
