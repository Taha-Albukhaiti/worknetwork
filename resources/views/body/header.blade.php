<nav class="navbar navbar-expand-lg navbar-light d-flex align-items-center">
    <a href="#" class="sidebar-toggler">
        <i data-feather="menu"></i>
    </a>
    <div class="navbar-content">
        <form class="search-form">
            <div class="input-group">
                <div class="input-group-text">
                    <i data-feather="search"></i>
                </div>
                <input type="text" class="form-control" id="navbarForm" placeholder="Search here...">
            </div>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <div class="fixed-md-bottom p-4 text-right">
                    @if (Route::has('login'))
                        @auth
                            @if(Auth::user()->role == 'company')
                                <a href="{{ route('company.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                            @elseif(Auth::user()->role == 'user')
                                <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                            @elseif(Auth::user()->role == 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-primary">Dashboard</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-primary">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </li>
        </ul>
    </div>
</nav>
