<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
        @error('phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="department" class="form-label">Department</label>
        <input type="text" class="form-control @error('department') is-invalid @enderror" id="department" name="department" value="{{ old('department', $user->department) }}">
        @error('department')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning">
            Your email address is unverified.
            <button form="send-verification" class="btn btn-link p-0">Click here to re-send the verification email.</button>
        </div>
    @endif

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">Save</button>
        @if (session('status') === 'profile-updated')
            <span class="btn btn-success disabled">Saved</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
