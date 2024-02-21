<nav class="navbar navbar-expand-lg navbar-light d-flex align-items-center">
    <!-- ... Navbar-Inhalt ... -->
    <div class="navbar-content ">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <div class="fixed-md-bottom p-4 text-right">
                    @if (Route::has('login'))
                        @auth
                            <!-- If the user is authenticated and has a recognized role, render the appropriate dashboard -->
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
