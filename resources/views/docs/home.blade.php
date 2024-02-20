<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="STMIK Bandung">
    <title>STMIK Bandung - API Docs</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- Feather Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    {{-- JQuery --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Sweet Alert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
    <body>
        <nav id="navbar-example2" class="navbar bg-dark-subtle px-3 mb-3 px-md-0 px-lg-5">
            <a class="navbar-brand" href="/">
                <span class="fw-bold fs-5">STMIK Bandung</span>
                <div style="font-size: 12px;">
                    API Documentation
                </div>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading1">Auth</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading2">User</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading3">KRS</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyHeading4">ACL</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Menu</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#scrollspyHeading5">Lima</a></li>
                        <li><a class="dropdown-item" href="#scrollspyHeading6">Enam</a></li>
                        <li><a class="dropdown-item" href="#scrollspyHeading7">Tujuh</a></li>

                        {{-- Logout --}}
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/docs/api/logout">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <main class="container">
            <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example bg-body-tertiary p-3 rounded-2" tabindex="0">
                <h4 id="scrollspyHeading1">First heading</h4>
                <p>...</p>
                <h4 id="scrollspyHeading2">Second heading</h4>
                <p>...</p>
                <h4 id="scrollspyHeading3">Third heading</h4>
                <p>...</p>
                <h4 id="scrollspyHeading4">Fourth heading</h4>
                <p>...</p>
                <h4 id="scrollspyHeading5">Fifth heading</h4>
                <p>...</p>
            </div>
        </main>

        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        {{-- Feather Icons --}}
        <script>
            feather.replace()
        </script>
    </body>
</html>
