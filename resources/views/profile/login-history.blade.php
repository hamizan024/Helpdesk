@extends('layouts.app')

@section('title', 'Login History')

@section('content')

<div class="row justify-content-center">
    <div class="col-lg-8">

        @include('profile.partials.profile-nav')

        <x-app-card title="Login History" icon="history" :noPad="true">

            <div class="table-responsive">
                <table class="table table-hover mb-0" style="font-size:0.84rem;">
                    <thead>
                        <tr style="background:#fafafa;">
                            <th class="px-4 py-3 border-0"
                                style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#7b809a;">
                                Date &amp; Time
                            </th>
                            <th class="px-4 py-3 border-0"
                                style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#7b809a;">
                                IP Address
                            </th>
                            <th class="px-4 py-3 border-0"
                                style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#7b809a;">
                                Browser
                            </th>
                            <th class="px-4 py-3 border-0"
                                style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#7b809a;">
                                OS
                            </th>
                            <th class="px-4 py-3 border-0"
                                style="font-size:0.72rem;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#7b809a;">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($histories as $history)
                            <tr>
                                <td class="px-4 py-3" style="color:#344767;font-variant-numeric:tabular-nums;">
                                    {{ $history->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-3" style="color:#344767;">
                                    {{ $history->ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3" style="color:#344767;">
                                    {{ $history->browser }}
                                </td>
                                <td class="px-4 py-3" style="color:#344767;">
                                    {{ $history->os }}
                                </td>
                                <td class="px-4 py-3">
                                    @if($history->status === 'success')
                                        <span class="badge"
                                              style="background:#e8f5e9;color:#2e7d32;font-size:0.72rem;font-weight:600;">
                                            Success
                                        </span>
                                    @else
                                        <span class="badge"
                                              style="background:#fdecea;color:#c62828;font-size:0.72rem;font-weight:600;">
                                            Failed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-muted">
                                    No login history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($histories->hasPages())
                <div class="px-4 py-3 border-top">
                    {{ $histories->links() }}
                </div>
            @endif

        </x-app-card>

    </div>
</div>

@endsection