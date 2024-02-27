<nav class="sidebar">
    <div class="sidebar-header">
        <a href="#" class="sidebar-brand">
            Work<span>Network</span>
        </a>
        <div class="sidebar-toggler not-active">
            <span></span>
            <span></span>
            <span></span>
        </div>
    </div>
    <div class="sidebar-body">
        <ul class="nav">
            <li class="nav-item nav-category">Main</li>
            <li class="nav-item">
                <a href="{{ route('welcome') }}" class="nav-link">
                    <i class="link-icon" data-feather="box"></i>
                    <span class="link-title">Home</span>
                </a>
            </li>
            <li class="nav-item nav-category">User</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#userMenu" role="button" aria-expanded="false"
                   aria-controls="adminMenu">
                    <i class="link-icon" data-feather="user"></i>
                    <span class="link-title">Admin</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="userMenu">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('user.profile') }}" class="nav-link">Profile</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile.edit') }}" class="nav-link">Edit Profile</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile.change.password') }}" class="nav-link">Change Password</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.portfolio') }}" class="nav-link">Make Portfolio</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.profile_requests') }}" class="nav-link">Profile Requests</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('user.logout') }}" class="nav-link">Log Out</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Pages</li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="collapse" href="#authPages" role="button" aria-expanded="false"
                   aria-controls="authPages">
                    <i class="link-icon" data-feather="unlock"></i>
                    <span class="link-title">Authentication</span>
                    <i class="link-arrow" data-feather="chevron-down"></i>
                </a>
                <div class="collapse" id="authPages">
                    <ul class="nav sub-menu">
                        <li class="nav-item">
                            <a href="{{ route('user.login') }}" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Register</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item nav-category">Docs</li>

            <li class="nav-item">
                <a href="#" target="_blank" class="nav-link">
                    <i class="link-icon" data-feather="hash"></i>
                    <span class="link-title">Documentation</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
