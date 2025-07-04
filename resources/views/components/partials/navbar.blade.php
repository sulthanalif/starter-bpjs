<nav class="app-header navbar navbar-expand bg-dark" data-bs-theme="dark">
    <!--begin::Container-->
    <div class="container-fluid">
        <!--begin::Start Navbar Links-->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a
                    class="nav-link"
                    data-lte-toggle="sidebar"
                    href="#"
                    role="button"
                >
                    <i class="bi bi-list"></i>
                </a>
            </li>
            {{-- <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Home</a>
            </li>
            <li class="nav-item d-none d-md-block">
                <a href="#" class="nav-link">Contact</a>
            </li> --}}
        </ul>
        <!--end::Start Navbar Links-->
        <!--begin::End Navbar Links-->
        <ul class="navbar-nav ms-auto">
            <!--begin::Fullscreen Toggle-->
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="#"
                    data-lte-toggle="fullscreen"
                >
                    <i
                        data-lte-icon="maximize"
                        class="bi bi-arrows-fullscreen"
                    ></i>
                    <i
                        data-lte-icon="minimize"
                        class="bi bi-fullscreen-exit"
                        style="display: none"
                    ></i>
                </a>
            </li>
            <!--end::Fullscreen Toggle-->
            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
                <a
                    href="#"
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                >
                    <span class="d-none d-md-inline"
                        >{{ auth()->user()->name }}</span
                    >
                </a>
                <ul
                    class="dropdown-menu dropdown-menu-end"
                >
                    <!--begin::User Image-->
                    <li class="px-2 py-4 d-flex justify-content-center text-center">
                        <p>
                            <strong>{{ auth()->user()->name }}</strong><br>
                            <small>{{ auth()->user()->getRoleNames()->first() }}</small>
                        </p>
                    </li>
                    <!--end::User Image-->
                    <!--begin::Menu Body-->
                    {{-- <li class="user-body">
                        <!--begin::Row-->
                        <div class="row">
                            <div class="col-4 text-center">
                                <a href="#">Followers</a>
                            </div>
                            <div class="col-4 text-center">
                                <a href="#">Sales</a>
                            </div>
                            <div class="col-4 text-center">
                                <a href="#">Friends</a>
                            </div>
                        </div>
                        <!--end::Row-->
                    </li> --}}
                    <!--end::Menu Body-->
                    <!--begin::Menu Footer-->
                    <li class="user-footer">
                        <a href="#" class="btn btn-default btn-flat"
                            >Profile</a
                        >
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-default btn-flat float-end">
                                Logout
                            </button>
                        </form>
                    </li>
                    <!--end::Menu Footer-->
                </ul>
            </li>
            <!--end::User Menu Dropdown-->
        </ul>
        <!--end::End Navbar Links-->
    </div>
    <!--end::Container-->
</nav>
