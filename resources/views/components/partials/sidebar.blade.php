<aside class="app-sidebar bg-dark" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ asset('dist') }}/index.html" class="brand-link">
            <img src="/assets/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image opacity-75 shadow" />
            <span class="brand-text fw-light">AdminLTE 4</span>
        </a>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                aria-label="Main navigation" data-accordion="false" id="navigation">

                @php
                    // Ambil konfigurasi menu dari config/menu.php
                    $sidebarMenu = config('menu.sidebar');

                    // Fungsi helper untuk mengecek apakah rute aktif
                    function isActive($item)
                    {
                        if (isset($item['route']) && Route::currentRouteNamed($item['route'])) {
                            return true;
                        }
                        if (isset($item['active_routes'])) {
                            foreach ($item['active_routes'] as $routePattern) {
                                if (Route::currentRouteNamed($routePattern)) {
                                    return true;
                                }
                            }
                        }
                        if (isset($item['submenu'])) {
                            foreach ($item['submenu'] as $subItem) {
                                if (isActive($subItem)) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    }

                    // Fungsi helper untuk mengecek apakah menu perlu "menu-open"
                    function isMenuOpen($item)
                    {
                        if (isset($item['active']) && $item['active'] === true) {
                            return true; // Aktifkan jika ditandai aktif secara eksplisit
                        }
                        if (isset($item['submenu'])) {
                            foreach ($item['submenu'] as $subItem) {
                                if (isActive($subItem)) {
                                    return true;
                                }
                            }
                        }
                        return false;
                    }
                @endphp

                @foreach ($sidebarMenu as $menuItem)
                    @if (isset($menuItem['header']))
                        <li class="nav-header">{{ $menuItem['header'] }}</li>
                    @else
                        <li class="nav-item @if (isset($menuItem['submenu']) && isMenuOpen($menuItem)) menu-open @endif">
                            <a href="{{ $menuItem['url'] ?? '#' }}"
                                class="nav-link @if (isActive($menuItem)) active @endif">
                                @if (isset($menuItem['icon']))
                                    <i class="nav-icon {{ $menuItem['icon'] }}"></i>
                                @endif
                                <p>
                                    {{ $menuItem['text'] }}
                                    @if (isset($menuItem['sub_text']))
                                        <small>{{ $menuItem['sub_text'] }}</small>
                                    @endif
                                    @if (isset($menuItem['badge']))
                                        <span class="nav-badge badge {{ $menuItem['badge']['class'] ?? '' }}">
                                            {{ $menuItem['badge']['text'] }}
                                        </span>
                                    @endif
                                    @if (isset($menuItem['submenu']))
                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                    @endif
                                </p>
                            </a>
                            @if (isset($menuItem['submenu']))
                                <ul class="nav nav-treeview">
                                    @foreach ($menuItem['submenu'] as $subMenuItem)
                                        <li class="nav-item @if (isset($subMenuItem['submenu']) && isMenuOpen($subMenuItem)) menu-open @endif">
                                            <a href="{{ $subMenuItem['url'] ?? '#' }}"
                                                class="nav-link @if (isActive($subMenuItem)) active @endif">
                                                @if (isset($subMenuItem['icon']))
                                                    <i class="nav-icon {{ $subMenuItem['icon'] }}"></i>
                                                @else
                                                    <i class="nav-icon bi bi-circle"></i>
                                                @endif
                                                <p>
                                                    {{ $subMenuItem['text'] }}
                                                    @if (isset($subMenuItem['sub_text']))
                                                        <small>{{ $subMenuItem['sub_text'] }}</small>
                                                    @endif
                                                    @if (isset($subMenuItem['submenu']))
                                                        <i class="nav-arrow bi bi-chevron-right"></i>
                                                    @endif
                                                </p>
                                            </a>
                                            @if (isset($subMenuItem['submenu']))
                                                <ul class="nav nav-treeview">
                                                    @foreach ($subMenuItem['submenu'] as $subSubMenuItem)
                                                        <li class="nav-item">
                                                            <a href="{{ $subSubMenuItem['url'] ?? '#' }}"
                                                                class="nav-link @if (isActive($subSubMenuItem)) active @endif">
                                                                @if (isset($subSubMenuItem['icon']))
                                                                    <i
                                                                        class="nav-icon {{ $subSubMenuItem['icon'] }}"></i>
                                                                @else
                                                                    <i class="nav-icon bi bi-record-circle-fill"></i>
                                                                @endif
                                                                <p>{{ $subSubMenuItem['text'] }}</p>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endif
                @endforeach

            </ul>
        </nav>
    </div>
</aside>
