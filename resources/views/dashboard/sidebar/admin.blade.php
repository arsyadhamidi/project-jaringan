<li class="nav-item">
    <a href="{{ route('admin-instansi.index') }}"
       class="nav-link @yield('menuDataInstansi')">
        <i class="nav-icon fas fa-building"></i>
        <p>
            Data Instansi
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin-status.index') }}"
       class="nav-link @yield('menuDataStatusLaporan')">
        <i class="nav-icon fas fa-clipboard-check"></i>
        <p>
            Status Laporan
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin-jaringan.index') }}"
       class="nav-link @yield('menuDataJaringan')">
        <i class="nav-icon fas fa-network-wired"></i>
        <p>
            Jaringan
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="{{ route('admin-laporangangguan.index') }}"
       class="nav-link @yield('menuDataLaporanGangguan')">
        <i class="nav-icon fas fa-exclamation-triangle"></i>
        <p>
            Laporan Gangguan
        </p>
    </a>
</li>
<li class="nav-item">
    <a href="#"
       class="nav-link @yield('menuDataAutentikasi')">
        <i class="fas fa-users nav-icon"></i>
        <p>
            Data Autentikasi
            <i class="fas fa-angle-left right"></i>
        </p>
    </a>
    <ul class="nav nav-treeview">
        <li class="nav-item">
            <a href="{{ route('admin-users.index') }}"
               class="nav-link @yield('menuDataUserRegistrasi')">
                <i class="far fa-circle nav-icon"></i>
                <p>User Registrasi</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin-level.index') }}"
               class="nav-link @yield('menuDataLevel')">
                <i class="far fa-circle nav-icon"></i>
                <p>Level</p>
            </a>
        </li>
    </ul>
</li>
