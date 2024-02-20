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

    {{-- Highlight JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script>
</head>
    <body>
        <nav id="navbar-example2" class="navbar bg-dark-subtle px-3 mb-3 px-md-0 px-lg-5 fixed-top">
            <a class="navbar-brand" href="/">
                <span class="fw-bold fs-5">STMIK Bandung</span>
                <div style="font-size: 12px;">
                    API Documentation
                </div>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyAuth">Auth</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyUsers">Users</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#scrollspyKRS">KRS</a>
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
        <main class="container px-5" style="margin-top: 130px;">
            <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example bg-body-tertiary p-4 rounded-2" tabindex="0">
                <?php $apiBaseURL = config('app.url') . 'api'; ?>
                <p>
                    <b>Base URL:</b> {{ $apiBaseURL }}
                </p>
                <p>
                    Base URL merupakan url utama dari API. Agar request yang dilakukan sesuai dengan yang dibutuhkan maka tambahkan dengan URI atau beberapa endpoint yang ada di bawah ini. Misalnya jika ingin melakukan login untuk meminta access token, maka alamat yang digunakan untuk meminta ke API menjadi <b>Base URL + endpoint</b>, sehingga url yang digunakan untuk login adalah <b>{{ $apiBaseURL }}/authentications</b>.
                </p>
                <h4 class="mt-4" id="scrollspyAuth"><b>#</b> Authentications</h4>
                <hr>
                @include('docs.docs_auth')
                <h4 class="mt-4" id="scrollspyUsers"><b>#</b> Users</h4>
                <hr>
                @include('docs.docs_users')
                <h4 class="mt-4" id="scrollspyKRS"><b>#</b> KRS - Sisi Mahasiswa</h4>
                <hr>
                @include('docs.docs_krs')
            </div>
        </main>

        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        {{-- Feather Icons --}}
        <script>
            feather.replace()
        </script>

        {{-- Highlight Code --}}
        <script>hljs.highlightAll();</script>
    </body>
</html>
