@php
    // Memeriksa apakah ada anak (submenu) untuk item ini.
    // Menggunakan null coalescing operator untuk fallback ke array kosong jika 'children' tidak ada.
    $hasChildren = !empty($item['children'] ?? []);

    // Memeriksa apakah item menu ini sedang aktif.
    // Mendukung string tunggal atau array untuk 'active_patterns'.
    // request()->is() akan cocok dengan URL path saat ini.
    $isActive = false;
    if (isset($item['active_patterns'])) {
        // Jika active_patterns adalah string, konversi menjadi array untuk request()->is()
        $patterns = is_array($item['active_patterns']) ? $item['active_patterns'] : [$item['active_patterns']];
        $isActive = request()->is(...$patterns); // Menggunakan spread operator untuk array patterns
    }

    // Menentukan kelas 'menu-open' untuk item induk jika memiliki anak dan aktif.
    // Menentukan kelas 'active' untuk link item menu.
    $menuOpenClass = $hasChildren && $isActive ? 'menu-open' : '';
    $linkActiveClass = $isActive ? 'active' : '';

    // Menentukan href. Jika ada anak, ini akan menjadi '#'. Jika tidak, coba gunakan route atau fallback ke '#'.
    $href = $hasChildren ? '#' : (isset($item['route']) ? route($item['route']) : (isset($item['url']) ? url($item['url']) : '#'));

    // Menentukan target link jika ada (misal: '_blank' untuk link eksternal)
    $target = $item['target'] ?? null;
@endphp

{{-- Hanya tampilkan item menu jika pengguna memiliki izin yang sesuai --}}
@can($item['permission'] ?? null)
    <li class="nav-item {{ $menuOpenClass }}">
        <a href="{{ $href }}"
           @class(['nav-link', $linkActiveClass]) {{-- @class directive lebih rapi untuk kondisional class --}}
           @if($target) target="{{ $target }}" @endif>
            {{-- Ikon menu, fallback ke ikon default jika tidak ada --}}
            <i class="nav-icon {{ $item['icon'] ?? 'far fa-circle nav-icon' }}"></i>
            <p>
                {{ $item['label'] }} {{-- Label menu --}}
                @if($hasChildren)
                    <i class="fas fa-angle-left right"></i> {{-- Ikon panah jika ada submenu --}}
                @endif
            </p>
        </a>

        {{-- Rekursif untuk submenu --}}
        @if($hasChildren)
            <ul class="nav nav-treeview">
                @foreach($item['children'] as $child)
                    @include('layouts.partials', ['item' => $child]) {{-- Panggil kembali partial ini untuk setiap anak --}}
                @endforeach
            </ul>
        @endif
    </li>
@endcan
