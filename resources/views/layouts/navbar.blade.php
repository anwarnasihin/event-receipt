<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    <!-- Left navbar -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar -->
    <ul class="navbar-nav ml-auto">

        <li class="nav-item dropdown">

            <a class="nav-link d-flex align-items-center py-1"
               data-toggle="dropdown"
               href="#">

                <!-- Ikon profil diperhalus ukurannya agar pas -->
                <i class="fas fa-user-circle fa-lg text-secondary mr-2"></i>

                <div class="text-left" style="line-height: 1.2;">
                    <span class="font-weight-bold text-dark" style="font-size: 0.9rem;">
                        {{ auth()->user()->name }}
                    </span>

                    <br>

                   @php
    $role = auth()->user()->roles->first();
@endphp

                    @if($role)
                        @if($role->name == 'Administrator')
                            <span class="badge badge-danger px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                                Administrator
                            </span>
                        @elseif($role->name == 'Petugas')
                            <span class="badge badge-primary px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                                Petugas
                            </span>
                        @else
                            <span class="badge badge-secondary px-2 py-1" style="font-size: 0.7rem; border-radius: 4px;">
                                Viewer
                            </span>
                        @endif
                    @endif
                </div>

                <!-- Tambahan panah kecil dropdown agar terlihat interaktif -->
                <i class="fas fa-angle-down ml-2 text-muted" style="font-size: 0.8rem;"></i>

            </a>

            <div class="dropdown-menu dropdown-menu-right shadow-sm border-0 mt-2">

                <span class="dropdown-header text-muted font-weight-bold py-2">
                    {{ auth()->user()->email }}
                </span>

                <div class="dropdown-divider my-0"></div>

                <a href="#"
                   class="dropdown-item py-2 text-danger"
                   id="btnLogoutNavbar">

                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Logout

                </a>

            </div>

        </li>

    </ul>

</nav>
