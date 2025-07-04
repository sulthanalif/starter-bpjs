<x-layouts.guest>
    <x-slot name="title">
        Login
    </x-slot>

    <form action="{{ route('auth.login') }}" method="post">
        @csrf
        <div class="input-group mb-3">
            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Email"
            />
            <div class="input-group-text">
                <span class="bi bi-envelope"></span>
            </div>
        </div>
        <div class="input-group mb-3">
            <input
                type="password"
                name="password"
                class="form-control"
                placeholder="Password"
            />
            <div class="input-group-text">
                <span class="bi bi-lock-fill"></span>
            </div>
        </div>

        <p class="mb-3">
            <a href="#">Belum memiliki akun? Registrasi disini!</a>
        </p>
        <!--begin::Row-->
        <div class="row">
            <div class="col-8">
                <div class="form-check">
                    <input
                        class="form-check-input"
                        type="checkbox"
                        name="remember"
                        value=""
                        id="flexCheckDefault"
                    />
                    <label
                        class="form-check-label"
                        for="flexCheckDefault"
                    >
                        Remember Me
                    </label>
                </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
                <div class="d-grid gap-2">
                    <button
                        type="submit"
                        class="btn btn-primary"
                    >
                        Login
                    </button>
                </div>
            </div>
            <!-- /.col -->
        </div>
        <!--end::Row-->
    </form>
</x-layouts.guest>
