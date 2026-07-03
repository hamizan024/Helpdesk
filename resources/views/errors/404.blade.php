@extends('layouts.app')

@section('title', '404 — Not Found')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-5 text-center py-5">
        <div class="mb-4" style="font-size:5rem; line-height:1; color:#1A73E8; font-weight:800;">404</div>
        <h4 class="fw-bold mb-2" style="color:#344767;">Page Not Found</h4>
        <p class="text-muted mb-4">The page you're looking for doesn't exist or has been moved.</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary btn-sm px-4">
            <span class="material-icons-round me-1" style="font-size:.9rem; vertical-align:middle;">home</span>
            Back to Dashboard
        </a>
    </div>
</div>
@endsection
