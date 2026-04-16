<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" autocomplete="tel">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="department" class="form-label">Department</label>
            <select class="form-select @error('department') is-invalid @enderror" id="department" name="department">
                <option value="">Select Department</option>
                @php
                $departments = \App\Models\Department::where('is_active', true)->orderBy('name', 'asc')->get();
                @endphp
                @foreach($departments as $dept)
                    <option value="{{ $dept->name }}" {{ old('department', $user->department) == $dept->name ? 'selected' : '' }}>{{ $dept->name }}</option>
                @endforeach
            </select>
            @error('department')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="year_level" class="form-label">Year Level</label>
            <select class="form-select @error('year_level') is-invalid @enderror" id="year_level" name="year_level">
                <option value="">Select Year Level</option>
                <option value="1" {{ old('year_level', $user->year_level) == '1' ? 'selected' : '' }}>1st Year</option>
                <option value="2" {{ old('year_level', $user->year_level) == '2' ? 'selected' : '' }}>2nd Year</option>
                <option value="3" {{ old('year_level', $user->year_level) == '3' ? 'selected' : '' }}>3rd Year</option>
                <option value="4" {{ old('year_level', $user->year_level) == '4' ? 'selected' : '' }}>4th Year</option>
            </select>
            @error('year_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="semester" class="form-label">Semester</label>
            <select class="form-select @error('semester') is-invalid @enderror" id="semester" name="semester">
                <option value="">Select Semester</option>
                <option value="1st" {{ old('semester', $user->semester) == '1st' ? 'selected' : '' }}>1st Semester</option>
                <option value="2nd" {{ old('semester', $user->semester) == '2nd' ? 'selected' : '' }}>2nd Semester</option>
            </select>
            @error('semester')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <div class="alert alert-warning">
            Your email address is unverified.
            <button form="send-verification" class="btn btn-link p-0">Click here to re-send the verification email.</button>
        </div>
    @endif

    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary"><i class="bi bi-check2 me-1"></i>Save Changes</button>
        @if (session('status') === 'profile-updated')
            <span class="btn btn-success disabled"><i class="bi bi-check-circle me-1"></i>Saved</span>
        @endif
    </div>
</form>

<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>
