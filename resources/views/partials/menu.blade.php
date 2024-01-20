<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            {{ trans('panel.site_title') }}
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.home") }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.pengaduan.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/pengaduan") || request()->is("admin/pengaduan/*") ? "c-active" : "" }}">
                <i class="fa-fw far fa-file-alt c-sidebar-nav-icon"></i>
                {{ trans('cruds.pengaduan.title') }}
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.tugas.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/tugas") || request()->is("admin/tugas/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-clipboard-check c-sidebar-nav-icon"></i>
                {{ trans('cruds.tugas.title') }}
            </a>
        </li>

        <li class="c-sidebar-nav-item">
            <a href="{{ route("admin.laporan.index") }}"
                class="c-sidebar-nav-link {{ request()->is("admin/laporan") || request()->is("admin/laporan/*") ? "c-active" : "" }}">
                <i class="fa-fw fas fa-clipboard-list c-sidebar-nav-icon"></i>
                {{ trans('cruds.laporan.title') }}
            </a>
        </li>

        <li class="c-sidebar-nav-dropdown {{ request()->is("admin/lokasi*") ? "c-show" : "" }} {{ request()->is("admin/kategori*") ? "c-show" : "" }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-table c-sidebar-nav-icon"></i>
                {{ trans('cruds.masterData.title') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.lokasi.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/lokasi") || request()->is("admin/lokasi/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-map-marker-alt c-sidebar-nav-icon"></i>
                        {{ trans('cruds.lokasi.title') }}
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.kategori.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/kategori") || request()->is("admin/kategori/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-bars c-sidebar-nav-icon"></i>
                        {{ trans('cruds.kategori.title') }}
                    </a>
                </li>
                <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.pengguna.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/pengguna") || request()->is("admin/pengguna/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                        Daftar Pengguna
                    </a>
                </li>
                {{-- <li class="c-sidebar-nav-item">
                    <a href="{{ route("admin.users.index") }}"
                        class="c-sidebar-nav-link {{ request()->is("admin/users") || request()->is("admin/users/*") ? "c-active" : "" }}">
                        <i class="fa-fw fas fa-user c-sidebar-nav-icon"></i>
                        {{ trans('cruds.user.title') }}
                    </a>
                </li> --}}
            </ul>
        </li>

        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}"
                href="{{ route('profile.password.edit') }}">
                <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                {{ trans('global.change_password') }}
            </a>
        </li>
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link"
                onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>
</div>
