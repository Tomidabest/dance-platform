<header class="site-header">
    <nav class="navbar">
        <div class="container">
            <a href="/" class="nav-logo">
                DanceIgnite
            </a>
            
            <ul class="nav-links">
                <li><a href="/studios">Studios</a></li>
                @auth
                    <li><a href="/profile">Profile</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard">Admin Dashboard</a></li>
                    @elseif(auth()->user()->role === 'instructor')
                        <li><a href="{{ route('instructor.dashboard') }}">Instructor Dashboard</a></li>
                    @else
                        <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                    @endif
                    <li><a href="/logout" class="nav-logout">Log out</a></li>
                @else
                    <li><a href="/register">Sign up</a></li>
                    <li><a href="/login">Log in</a></li>
                @endauth
            </ul>
        </div>
    </nav>
</header>

