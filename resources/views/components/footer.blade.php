<footer class="site-footer">
    <div class="footer-container">
        <div class="footer-section">
            <h2 class="footer-logo">DanceIgnite</h2>
        </div>

        <div class="footer-section">
            <h3>More</h3>
            <ul>
                <li><a href="/studios">Find a Studio</a></li>

                @auth
                    @if(auth()->user()->role === 'admin')
                        <li><a href="/admin/dashboard">Admin Dashboard</a></li>
                    @elseif(auth()->user()->role === 'instructor')
                        <li><a href="{{ route('instructor.dashboard') }}">Instructor Dashboard</a></li>
                    @else
                        <li><a href="{{ route('user.dashboard') }}">My Dashboard</a></li>
                    @endif
                @else
                    <li><a href="/register">Sign up</a></li>
                    <li><a href="/login">Log in</a></li>
                @endauth
            </ul>
        </div>

        <div class="footer-section">
            <h3>Our Social</h3>
            <ul class="social-links">
                <li><a href="https://x.com/Losbaeth" class="social-link twitter">X (Twitter)</a></li>
                <li><a href="https://www.instagram.com/v_rusew/" class="social-link instagram">Instagram</a></li>
                <li><a href="https://www.facebook.com/profile.php?id=100008760581235" class="social-link facebook">Facebook</a></li>
            </ul>
        </div>
    </div>
</footer>
