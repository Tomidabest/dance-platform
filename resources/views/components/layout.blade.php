<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DanceIgnite')</title>
    <link rel="stylesheet" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
</head>
<body>
    <!-- Header Section -->
    <header class="site-header">
        <nav class="navbar">
            <div class="container">
                <!-- Logo Section -->
                <a href="/" class="nav-logo">DanceIgnite</a>
                
                <!-- Navigation Links -->
                <ul class="nav-links">
                    <li><a href="/studios">Studios</a></li>
                    <li><a href="/register">Sign in</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/faq">FAQ</a></li>
                    <li><a href="/events">Events</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content Section -->
    <main class="site-content">
        <div>
            <?php echo $slot; ?>
        </div>
    </main>

    <!-- Footer Section -->
    <footer class="site-footer">
        <div class="container">
            <ul class="footer-links">
                <li><a href="/studios">Studios</a></li>
                <li><a href="/classes">Classes</a></li>
                <li><a href="/about">About</a></li>
                <li><a href="/contact">Contact</a></li>
                <li><a href="/faq">FAQ</a></li>
                <li><a href="/events">Events</a></li>
            </ul>
            <p>&copy; 2024 DanceIgnite. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>