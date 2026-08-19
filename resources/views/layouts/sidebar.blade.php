<style>
    /* Mengubah kotak menu yang aktif menjadi kuning menyala */
    .nav-pills .nav-link.active,
    .nav-pills .show > .nav-link {
        background-color: #edf700 !important; /* Warna kuning menyala */
        color: #111827 !important; /* Warna teks menjadi gelap */
        box-shadow: 0 4px 6px -1px rgba(255, 204, 0, 0.4) !important; /* Sedikit efek bayangan kuning (opsional) */
    }

    /* Memastikan warna icon ikut menjadi gelap saat menu aktif */
    .nav-pills .nav-link.active i {
        color: #111827 !important;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background: linear-gradient(180deg, #46556d 0%, #000000 100%) !important;">

    <!-- Logo -->
<a href="{{ route('dashboard') }}" class="brand-link text-center" style="display: block; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.1);">
    <!-- Ganti 'images/binus3.png' jika Anda memiliki file logo putih atau logo lain yang lebih pas -->
    <img src="{{ asset('images/BINUSUNIVERSITYWhite.png') }}"
         alt="Logo Event"
         class="img-fluid"
         style="max-height: 45px; width: auto; margin: 0 auto; filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.3));">
</a>

    <div class="sidebar d-flex flex-column justify-content-between" style="height: calc(100vh - 70px); overflow-y: auto;">
        @php
            $user = auth()->user();
        @endphp
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- MASTER -->
                @if($user->hasRole('Administrator'))

                    <li class="nav-header">MASTER</li>

                    <li class="nav-item">
                        <a href="{{ route('events.index') }}"
                        class="nav-link {{ request()->routeIs('events.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>Master Event</p>
                        </a>
                    </li>

                @endif

                <!-- TRANSAKSI -->
                @if($user->hasAnyRole(['Administrator','Petugas']))

                    <li class="nav-header">TRANSAKSI</li>

                    {{-- TANDA TERIMA: Administrator + Petugas --}}
                    <li class="nav-item">
                        <a href="{{ route('receipt.index') }}"
                        class="nav-link {{ request()->routeIs('receipt.*') ? 'active' : '' }}">

                            <i class="nav-icon fas fa-receipt"></i>

                            <p>Tanda Terima</p>

                        </a>
                    </li>


                    {{-- ABSENSI: Administrator saja --}}
                    @if($user->hasRole('Administrator'))

                        <li class="nav-item">
                            <a href="{{ route('checkin.index') }}"
                            class="nav-link {{ request()->routeIs('checkin.*') ? 'active' : '' }}">

                                <i class="nav-icon fas fa-user-check"></i>

                                <p>Absensi</p>

                            </a>
                        </li>

                    @endif

                @endif

                <!-- LAPORAN -->
                @if($user->hasAnyRole([
                    'Administrator',
                    'Petugas',
                    'Viewer'
                    ]))
                    <li class="nav-header">LAPORAN</li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}"
                        class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Laporan
                            </p>
                        </a>
                    </li>
                @endif

                <!-- SYSTEM -->
                @if($user->hasRole('Administrator'))

                    <li class="nav-header">SYSTEM</li>

                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                        class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>User Management</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('settings.index') }}"
                        class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-cogs"></i>
                            <p>Settings</p>
                        </a>
                    </li>

                @endif

                <li class="nav-item">
                    <a href="#" class="nav-link" id="btnLogout">
                        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                </li>

                <form id="logout-form"
                    action="{{ route('logout') }}"
                    method="POST"
                    style="display:none;">

                    @csrf

                </form>

            </ul>
        </nav>
    </div>
    <div class="text-center py-3" style="position: absolute; bottom: 0; width: 100%; border-top: 1px solid rgba(255,255,255,0.1); font-size: 11px; color: #a1aab9; background: linear-gradient(180deg, #46556d 0%, #000000 100%);">
        &copy; 2026 IT BINUS Univ Bekasi.<br>All rights reserved.
    </div>
</aside>
