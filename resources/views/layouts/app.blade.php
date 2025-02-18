<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Application')</title>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <script src="/js/axios.min.js" crossorigin="anonymous"></script>
    <script src="/js/jquery.min.js" crossorigin="anonymous"></script>

    @yield('additionalstyles')
</head>

<body>
    <div class="wrapper">
        <!-- Navigation Bar -->
        <header>
            <nav class="navbar navbar-expand-lg light-1">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ url('/') }}">BernEvents</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/') }}">Home</a>
                                </li>
                                <!-- Display only if the user is not logged in -->
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/login') }}">Login</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/register') }}">Signup</a>
                                </li>
                            @endguest
                            @auth
                                <!-- Display only if the user is logged in -->
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <!-- Display the user's name -->
                                        Hi {{ auth()->user()->name }}!
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/dashboard') }}">Profile</a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                                            <form id="logout-form" action="{{ url('/logout') }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </header>

        <!-- Main Content -->
        <main id="page-content" class="container mt-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="text-center text-lg-start mt-4 light-1">
            <div class="text-center p-3">
                &copy; 2024 BernEvents
            </div>
        </footer>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    @yield('additionalscripts')
</body>

</html>
