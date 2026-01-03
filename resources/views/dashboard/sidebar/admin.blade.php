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
            <a href="#"
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
