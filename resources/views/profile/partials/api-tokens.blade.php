<x-app-card title="API Tokens" class="mb-4">
    <p class="text-muted mb-4" style="font-size:0.82rem;">
        API tokens allow external tools and scripts to authenticate with the IT Helpdesk API.
        Each token should be given a descriptive name so you can identify and revoke it later.
    </p>

    {{-- New token just created — show it once --}}
    @if(session('new_token'))
        <div class="alert alert-success d-flex align-items-start gap-2 mb-4" role="alert">
            <span class="material-icons-round mt-1" style="font-size:1rem;">key</span>
            <div style="flex:1;">
                <div style="font-size:0.82rem;font-weight:600;margin-bottom:6px;">Token created — copy it now, it won't be shown again:</div>
                <div class="d-flex align-items-center gap-2">
                    <code id="new-token-value"
                          style="font-size:0.78rem;background:#fff3;padding:6px 10px;border-radius:4px;word-break:break-all;flex:1;">
                        {{ session('new_token') }}
                    </code>
                    <button type="button"
                            onclick="copyToken()"
                            class="btn btn-sm btn-light"
                            style="font-size:0.75rem;white-space:nowrap;">
                        <span class="material-icons-round me-1" style="font-size:0.85rem;vertical-align:-2px;">content_copy</span>
                        Copy
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Create token form --}}
    <form method="POST" action="{{ route('profile.tokens.store') }}" class="mb-4">
        @csrf
        <div class="d-flex gap-2 align-items-start">
            <div style="flex:1;">
                <input type="text"
                       name="token_name"
                       class="form-control form-control-sm @error('token_name') is-invalid @enderror"
                       placeholder="Token name (e.g. My Script, Postman)"
                       value="{{ old('token_name') }}"
                       maxlength="100"
                       required>
                @error('token_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-sm btn-primary" style="background:linear-gradient(195deg,#EC407A,#D81B60);border:none;white-space:nowrap;">
                <span class="material-icons-round me-1" style="font-size:0.85rem;vertical-align:-2px;">add</span>
                Create Token
            </button>
        </div>
    </form>

    {{-- Existing tokens list --}}
    @php $tokens = auth()->user()->tokens()->latest()->get(); @endphp

    @if($tokens->isEmpty())
        <p class="text-muted text-center py-3" style="font-size:0.82rem;">No API tokens yet.</p>
    @else
        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0" style="font-size:0.82rem;">
                <thead>
                    <tr>
                        <th style="color:#7b809a;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Name</th>
                        <th style="color:#7b809a;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Last Used</th>
                        <th style="color:#7b809a;font-weight:600;font-size:0.72rem;text-transform:uppercase;letter-spacing:.05em;">Created</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tokens as $token)
                    <tr>
                        <td>
                            <span class="material-icons-round me-1" style="font-size:0.9rem;vertical-align:-3px;color:#7b809a;">vpn_key</span>
                            {{ $token->name }}
                        </td>
                        <td class="text-muted">
                            {{ $token->last_used_at ? $token->last_used_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-muted">
                            {{ $token->created_at->format('d M Y') }}
                        </td>
                        <td class="text-end">
                            <form method="POST"
                                  action="{{ route('profile.tokens.destroy', $token->id) }}"
                                  onsubmit="return confirm('Revoke token \'{{ addslashes($token->name) }}\'?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-link btn-sm text-danger p-0"
                                        title="Revoke token">
                                    <span class="material-icons-round" style="font-size:1rem;">delete_outline</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-app-card>

<script>
function copyToken() {
    const val = document.getElementById('new-token-value').textContent.trim();
    navigator.clipboard.writeText(val).then(() => {
        const btn = event.currentTarget;
        btn.innerHTML = '<span class="material-icons-round me-1" style="font-size:0.85rem;vertical-align:-2px;">check</span>Copied!';
        setTimeout(() => {
            btn.innerHTML = '<span class="material-icons-round me-1" style="font-size:0.85rem;vertical-align:-2px;">content_copy</span>Copy';
        }, 2000);
    });
}
</script>
