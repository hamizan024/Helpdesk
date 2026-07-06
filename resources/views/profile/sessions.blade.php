@extends('layouts.app')

@section('title', 'Active Sessions')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        @include('profile.partials.profile-nav')

        @if(session('status') === 'session-revoked')
            <x-alert type="success" class="mb-3">Session revoked successfully.</x-alert>
        @endif

        @if(session('status') === 'other-sessions-revoked')
            <x-alert type="success" class="mb-3">All other sessions have been revoked.</x-alert>
        @endif

        <x-app-card title="Active Sessions" icon="devices">
            <x-slot:actions>
                @if($sessions->count() > 1)
                    <form method="POST" action="{{ route('profile.sessions.others') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-sm btn-outline-danger"
                                style="font-size:0.75rem;padding:4px 12px;">
                            Revoke All Others
                        </button>
                    </form>
                @endif
            </x-slot:actions>

            @forelse($sessions as $session)
                <div class="d-flex align-items-center justify-content-between py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex align-items-center gap-3">
                        <div style="width:42px;height:42px;border-radius:10px;background:#f0f2f5;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @php
                                $osIcon = match($session->os) {
                                    'Windows' => 'computer',
                                    'macOS'   => 'laptop_mac',
                                    'Android' => 'phone_android',
                                    'iOS'     => 'phone_iphone',
                                    'Linux'   => 'terminal',
                                    default   => 'devices',
                                };
                            @endphp
                            <span class="material-icons-round" style="font-size:1.25rem;color:#7b809a;">{{ $osIcon }}</span>
                        </div>
                        <div>
                            <div style="font-size:0.84rem;font-weight:600;color:#344767;line-height:1.4;">
                                {{ $session->browser }} on {{ $session->os }}
                                @if($session->isCurrent)
                                    <span class="badge ms-1"
                                          style="background:#e8f5e9;color:#2e7d32;font-size:0.68rem;font-weight:600;">
                                        This device
                                    </span>
                                @endif
                            </div>
                            <div style="font-size:0.75rem;color:#7b809a;">
                                {{ $session->ip_address ?? 'Unknown IP' }}
                                &middot;
                                {{ $session->lastActive }}
                            </div>
                        </div>
                    </div>

                    @if(!$session->isCurrent)
                        <form method="POST"
                              action="{{ route('profile.sessions.destroy', $session->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-sm btn-outline-danger"
                                    style="font-size:0.75rem;padding:4px 12px;">
                                Revoke
                            </button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-muted mb-0" style="font-size:0.875rem;">No active sessions found.</p>
            @endforelse

        </x-app-card>

    </div>
</div>

@endsection