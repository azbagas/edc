<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="{{ asset('storage/images/logo-tooth-only.png') }}" alt="Logo Klinik" class="brand-image"
            style="opacity: .8">
        <span class="brand-text font-weight-light ml-2">EDC</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/' . Auth::user()->photo) }}" class="img-circle elevation-2" alt="User Image" style="width: 2.1rem; height: 2.1rem; object-fit: cover;">
            </div>
            <div class="info">
                <a href="/profile" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
       with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-th-large"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/appointments" class="nav-link {{ Request::is('appointments*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-notes-medical"></i>
                        <p>Pertemuan</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/patients" class="nav-link {{ Request::is('patients*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Data Pasien</p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('treatment-types*') || Request::is('treatments*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <p>
                            Data Tindakan
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/treatment-types" class="nav-link {{ Request::is('treatment-types*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Tindakan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/treatments" class="nav-link {{ Request::is('treatments*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Tindakan</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::is('diseases*') || Request::is('diagnoses*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-disease"></i>
                        <p>
                            Data Penyakit
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/diseases" class="nav-link {{ Request::is('diseases*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Penyakit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/diagnoses" class="nav-link {{ Request::is('diagnoses*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Diagnosis</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item {{ Request::is('medicine-types*') || Request::is('medicines*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-pills"></i>
                        <p>
                            Data Obat
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/medicine-types" class="nav-link {{ Request::is('medicine-types*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Jenis Obat</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/medicines" class="nav-link {{ Request::is('medicines*') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Obat</p>
                            </a>
                        </li>
                    </ul>
                </li>
                @can('admin')
                    <li class="nav-item {{ Request::is('doctors*') || Request::is('admins*') || Request::is('assistants*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-user"></i>
                            <p>
                                Data User
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/doctors" class="nav-link {{ Request::is('doctors*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Dokter</p>
                                </a>
                            </li>
                            @can('owner')
                                <li class="nav-item">
                                    <a href="/admins" class="nav-link {{ Request::is('admins*') ? 'active' : '' }}">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Data Admin</p>
                                    </a>
                                </li>
                            @endcan
                            <li class="nav-item">
                                <a href="/assistants" class="nav-link {{ Request::is('assistants*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Data Asisten</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('admin')
                    <li class="nav-item {{ Request::is('income*') || Request::is('expenses*') || Request::is('payment-types*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-money-bill"></i>
                            <p>
                                Keuangan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="/expenses" class="nav-link {{ Request::is('expenses*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/income" class="nav-link {{ Request::is('income*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pendapatan</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/payment-types" class="nav-link {{ Request::is('payment-types*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Metode Pembayaran</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endcan
                @can('admin')
                    <li class="nav-item {{ Request::is('reports*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                Laporan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('community-health-center-daily') }}" class="nav-link {{ Request::is('reports/community-health-center/daily*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan untuk Puskesmas (Harian)</p>
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a href="{{ route('community-health-center-monthly') }}" class="nav-link {{ Request::is('reports/community-health-center/monthly*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Laporan untuk Puskesmas (Bulanan)</p>
                                </a>
                            </li> 
                        </ul>
                    </li>
                @endcan
                @can('owner')
                    <li class="nav-item {{ Request::is('recap*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-scroll"></i>
                            <p>
                                Rekap
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('recap-daily') }}" class="nav-link {{ Request::is('recap/daily*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap (Harian)</p>
                                </a>
                            </li> 
                            <li class="nav-item">
                                <a href="{{ route('recap-monthly') }}" class="nav-link {{ Request::is('recap/monthly*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap (Bulanan)</p>
                                </a>
                            </li> 
                        </ul>
                    </li>
                @endcan
                <li class="nav-header">LOGOUT</li>
                <li class="nav-item">
                    <form action="/logout" method="POST" id="logout" class="d-none">
                        @csrf
                    </form>
                    <a href="#" class="nav-link" onclick="logout()">
                        <i class="nav-icon fas fa-arrow-circle-left"></i>
                        <p>Logout</p>
                    </a>
                </li>
                {{-- tambah class menu-open kalo mau buka --}}
                {{-- <li class="nav-item menu-open">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Starter Pages
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Active Page</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Inactive Page</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Simple Link
                            <span class="right badge badge-danger">New</span>
                        </p>
                    </a>
                </li> --}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

@push('sidebarScripts')
    <script>
        function logout() {
            document.getElementById('logout').submit();
        }
    </script>
@endpush