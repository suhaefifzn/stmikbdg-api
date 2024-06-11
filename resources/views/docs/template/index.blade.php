<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="STMIK Bandung">
    <title>API Docs - {{ $title }}</title>

    {{-- Favicons --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/images/favicons/favicon-16x16.png">
    <meta name="theme-color" content="#ffffff">

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

    <style>
        #goToTop {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 1000;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #007bff;
            color: white;
            border: none;
            outline: none;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        #goToTop svg {
            width: 24px;
            height: 24px;
        }
    </style>
</head>
    <body>
        @include('docs.template.nav')
        <main class="container px-3" style="margin-top: 120px;">
            @yield('docs_contents')
        </main>

        {{-- Footer --}}
        <footer class="d-flex align-items-center justify-content-center p-5 fw-bold">
            &copy; {{ date('Y') === '2024' ? '2024' : '2024 - ' . date('Y') }}. STMIK Bandung
        </footer>

        {{-- Go to top --}}
        <button id="goToTop">
            <i data-feather="arrow-up"></i>
        </button>

        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        {{-- Feather Icons --}}
        <script>
            feather.replace();
        </script>

        {{-- Highlight Code --}}
        <script>hljs.highlightAll();</script>

        {{-- Go to Top --}}
        <script>
            $(document).ready(function() {
                if ($(window).scrollTop() < 100) {
                    $('#goToTop').fadeOut();
                }

                $(window).scroll(function() {
                    if ($(this).scrollTop() > 100) {
                        $('#goToTop').fadeIn();
                    } else {
                        $('#goToTop').fadeOut();
                    }
                });

                $('#goToTop').click(function() {
                    $('html, body').animate({ scrollTop: 0 }, 800);
                    return false;
                });
            });
        </script>
    </body>
</html>
