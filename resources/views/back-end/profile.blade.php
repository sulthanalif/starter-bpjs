@extends('layouts.main')
@section('title', 'Profile')

@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Profile</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
              <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                <li class="nav-item">
                  <a class="nav-link active" id="custom-tabs-three-profile-tab" data-toggle="pill" href="#custom-tabs-three-profile" role="tab" aria-controls="custom-tabs-three-profile" aria-selected="true">Profil</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-three-password-tab" data-toggle="pill" href="#custom-tabs-three-password" role="tab" aria-controls="custom-tabs-three-password" aria-selected="false">Ganti Password</a>
                </li>
                {{-- <li class="nav-item">
                  <a class="nav-link" id="custom-tabs-three-settings-tab" data-toggle="pill" href="#custom-tabs-three-settings" role="tab" aria-controls="custom-tabs-three-settings" aria-selected="false">Tanda Tangan</a>
                </li> --}}
              </ul>
            </div>
            <div class="card-body">
              <div class="tab-content" id="custom-tabs-three-tabContent">
                <div class="tab-pane fade show active" id="custom-tabs-three-profile" role="tabpanel" aria-labelledby="custom-tabs-three-profile-tab">
                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" >
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ auth()->user()->email }}" readonly>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="custom-tabs-three-password" role="tabpanel" aria-labelledby="custom-tabs-three-password-tab">
                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="current_password">Password Saat Ini</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span toggle="#password-field" class="fa fa-eye-slash field_icon toggle-password" role="button"></span>
                                    </div>
                                </div>
                            </div>
                            @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span toggle="#password-field" class="fa fa-eye-slash field_icon toggle-password-2" role="button"></span>
                                    </div>
                                </div>
                            </div>
                            @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="new_password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span toggle="#password-field" class="fa fa-eye-slash field_icon toggle-password-3" role="button"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">Ganti Password</button>
                        </div>
                    </form>
                </div>
                {{-- <div class="tab-pane fade" id="custom-tabs-three-settings" role="tabpanel" aria-labelledby="custom-tabs-three-settings-tab">
                    @if (!auth()->user()->hasBeenSigned())
                        <h2 class="text-danger"></h2>
                        <form action="{{ auth()->user()->getSignatureRoute() }}" method="POST">
                            @csrf
                            <div style="text-align: center">
                                <x-creagia-signature-pad
                                border-color="#eaeaea"
                                pad-classes="rounded-xl border-2"
                                button-classes="btn btn-primary px-4 py-2 rounded-xl mt-4"
                                clear-name="Clear"
                                submit-name="Submit"
                                :disabled-without-signature="true"
                                />
                            </div>
                        </form>
                        <script src="{{ asset('vendor/sign-pad/sign-pad.min.js') }}"></script>
                    @else
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('storage/' . auth()->user()->signature->getSignatureImagePath()) }}" alt="Signature">
                    </div>


                        <div class="d-flex justify-content-center items-center">
                            <a href="{{ route('signature.delete') }}" class="btn btn-danger"> Delete</a>
                        </div>
                    @endif
                </div> --}}
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div>
    </div>
</section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $("body").on('click', '.toggle-password', function() {
                $(this).toggleClass("fa-eye-slash fa-eye");
                var passInput=$("#current_password");
                if(passInput.attr('type')==='password')
                {
                    passInput.attr('type','text');
                }else{
                    passInput.attr('type','password');
                }
            })

            $("body").on('click', '.toggle-password-2', function() {
                $(this).toggleClass("fa-eye-slash fa-eye");
                var passInput=$("#new_password");
                if(passInput.attr('type')==='password')
                {
                    passInput.attr('type','text');
                }else{
                    passInput.attr('type','password');
                }
            })

            $("body").on('click', '.toggle-password-3', function() {
                $(this).toggleClass("fa-eye-slash fa-eye");
                var passInput=$("#new_password_confirmation");
                if(passInput.attr('type')==='password')
                {
                    passInput.attr('type','text');
                }else{
                    passInput.attr('type','password');
                }
            })
        })
    </script>
@endpush
