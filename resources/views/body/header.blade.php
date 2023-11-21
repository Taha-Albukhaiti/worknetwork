<nav class="navbar navbar-expand-lg navbar-light d-flex align-items-center">
    <!-- ... Navbar-Inhalt ... -->
    <div class="navbar-content ">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <div class="fixed-md-bottom p-4 text-right">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-primary">Dashboard</a>
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
