<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
        <img src="/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ $siteName ?? 'CMS Manager'}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home')}}" class="nav-link active">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('products')}}" class="nav-link">
                        <i class="fas fa-dolly-flatbed"></i>
                        <p>Products</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('categories')}}" class="nav-link">
                        <i class="fas fa-stream"></i>
                        <p>Categories</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('collections')}}" class="nav-link">
                        <i class="fas fa-stream"></i>
                        <p>Collections</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('blogs')}}" class="nav-link">
                        <i class="fas fa-blog"></i>
                        <p>Blogs</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pages')}}" class="nav-link">
                        <i class="fas fa-file"></i>
                        <p>Pages (CMS)</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <p>
                            Settings
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('menus') }}" class="nav-link">
                                <i class="fas fa-stream nav-icon"></i>
                                <p>Menus</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('banners') }}" class="nav-link">
                                <i class="fas fa-bars nav-icon"></i>
                                <p>Banners</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('videos') }}" class="nav-link">
                                <i class="fas fa-film nav-icon"></i>
                                <p>YT Videos</p>
                            </a>
                        </li>
                        <?php /* <li class="nav-item">
                            <a href="{{ route('media') }}" class="nav-link">
                                <i class="fas fa-photo-video"></i>
                                <p>Media</p>
                            </a>
                        </li> */ ?>
                        <li class="nav-item">
                            <a href="{{ route('settings') }}" class="nav-link">
                                <i class="fas fa-cogs nav-icon"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('subscriptions') }}" class="nav-link">
                                <i class="fas fa-mail-bulk"></i>
                                <p>Subscription</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact-us') }}" class="nav-link">
                                <i class="fas fa-address-book"></i>
                                <p>Contact Request</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="fas fa-users"></i>
                        <p>
                            User Management
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('users') }}" class="nav-link">
                                <i class="fas fa-user-friends nav-icon"></i>
                                <p>Users</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('add-user')}}" class="nav-link">
                                <i class="fas fa-user-plus nav-icon"></i>
                                <p>Add Users</p>
                            </a>
                        </li>
                    </ul>
                </li>
                
                
                <li class="nav-header">AUTH</li>
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link">
                        <i class="nav-icon fas fa-sign-out-alt text-info"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>