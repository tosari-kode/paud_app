@extends('layouts.master')
@section('content')

<div class="pagetitle">
      <h1>Account</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Account</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section profile">
      <div class="row">

        <div class="col-xl-12">

          <div class="card">
            <div class="card-body pt-3">
              <!-- Bordered Tabs -->
              <ul class="nav nav-tabs nav-tabs-bordered">

                <li class="nav-item">
                  <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile-overview">Overview</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-edit">Edit Profile</button>
                </li>

                <li class="nav-item">
                  <button class="nav-link" data-bs-toggle="tab" data-bs-target="#profile-change-password">Change Password</button>
                </li>

              </ul>
              <div class="tab-content pt-2">

                <div class="tab-pane fade show active profile-overview" id="profile-overview">

                  <h5 class="card-title">Account Details</h5>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label ">Nama</div>
                    <div class="col-lg-9 col-md-8">{{$user->name}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Email</div>
                    <div class="col-lg-9 col-md-8">{{$user->email}}</div>
                  </div>

                  <div class="row">
                    <div class="col-lg-3 col-md-4 label">Hak Akses</div>
                    <div class="col-lg-9 col-md-8">{{$user->role}}</div>
                  </div>

                </div>

                <div class="tab-pane fade profile-edit pt-3" id="profile-edit">


                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="row mb-3">
                        <label for="name" class="col-md-4 col-lg-3 col-form-label">Nama</label>
                        <div class="col-md-8 col-lg-9">
                            <input
                                name="name"
                                type="text"
                                class="form-control @error('name') is-invalid @enderror"
                                id="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                autofocus
                                autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="email" class="col-md-4 col-lg-3 col-form-label">Email</label>
                        <div class="col-md-8 col-lg-9">
                            <input
                                name="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                id="email"
                                value="{{ old('email', $user->email) }}"
                                required
                                autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            {{-- Verifikasi Email --}}
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="mt-2">
                                    <p class="small text-muted mb-1">
                                        Your email address is unverified.
                                        <button form="send-verification"
                                                class="btn btn-link btn-sm p-0 align-baseline">
                                            Click here to re-send the verification email.
                                        </button>
                                    </p>
                                    @if (session('status') === 'verification-link-sent')
                                        <p class="small text-success mb-0">
                                            A new verification link has been sent to your email address.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>



                </div>



                <div class="tab-pane fade pt-3" id="profile-change-password">
                  <!-- Change Password Form -->
                  <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="row mb-3">
                            <label for="current_password" class="col-md-4 col-lg-3 col-form-label">Current Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input
                                    id="current_password"
                                    name="current_password"
                                    type="password"
                                    class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="current-password">

                                @error('current_password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-lg-3 col-form-label">New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                                    autocomplete="new-password">

                                @error('password', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-md-4 col-lg-3 col-form-label">Re-enter New Password</label>
                            <div class="col-md-8 col-lg-9">
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                                    autocomplete="new-password">

                                @error('password_confirmation', 'updatePassword')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">Change Password</button>

                            @if (session('status') === 'password-updated')
                                <p class="text-success mt-2 mb-0">Password successfully updated.</p>
                            @endif
                        </div>
                    </form>


                </div>

              </div><!-- End Bordered Tabs -->

            </div>
          </div>

        </div>
      </div>
    </section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ambil status & error dari Blade lewat atribut data
        const status = "{{ session('status') }}";
        const errors = JSON.parse(`{!! json_encode($errors->all()) !!}`);
        const updatePasswordErrors = JSON.parse(`{!! json_encode($errors->updatePassword->all() ?? []) !!}`);

        // ==== PROFILE SUCCESS ====
        if (status === 'profile-updated') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Profile has been updated successfully.',
                showConfirmButton: false,
                timer: 2000
            });
        }

        // ==== PROFILE FAILED ====
        if (errors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Update Failed',
                html: `
                    <ul style="text-align:left;">
                        ${errors.map(e => `<li>${e}</li>`).join('')}
                    </ul>
                `,
                confirmButtonColor: '#d33',
            });
        }

        // ==== PASSWORD SUCCESS ====
        if (status === 'password-updated') {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Password has been updated successfully.',
                showConfirmButton: false,
                timer: 2000
            });
        }

        // ==== PASSWORD FAILED ====
        if (updatePasswordErrors.length > 0) {
            Swal.fire({
                icon: 'error',
                title: 'Update Gagal',
                html: `
                    <ul style="text-align:center;">
                        ${updatePasswordErrors.map(e => `<li>${e}</li>`).join('')}
                    </ul>
                `,
                confirmButtonColor: '#d33',
            });
        }
    });
</script>











@endsection
