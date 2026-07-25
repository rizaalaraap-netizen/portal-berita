@csrf
<div class="mb-3">
    <label class="form-label" for="name">Nama</label>
    <input id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label" for="email">Email</label>
    <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-3">
    <label class="form-label" for="role">Role</label>
    <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
        @foreach(\App\Models\User::ROLES as $role => $label)
            <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ $label }}</option>
        @endforeach
    </select>
    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<div class="mb-4">
    <label class="form-label" for="password">Password {{ $user->exists ? '(kosongkan jika tidak diganti)' : '' }}</label>
    <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" @required(! $user->exists)>
    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>
<button class="btn btn-danger">{{ $button }}</button>
<a class="btn btn-outline-secondary" href="{{ route('admin.users.index') }}">Batal</a>
