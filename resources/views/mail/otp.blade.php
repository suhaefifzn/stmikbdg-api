<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="STMIK Bandung">
    <title>STMIK Bandung</title>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    {{-- Feather Icons --}}
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
</head>
    <body>
        <main class="container p-2">
            <p>
                Berikut adalah kode OTP untuk reset password akun Anda yang digunakan untuk mengakses sistem akademik di STMIK Bandung:
            </p>
            <div class="alert alert-info"><b>{{ $otp }}</b></div>
            <br>
            <p>
                Kode OTP hanya berlaku selama 5 menit sejak Anda berhasil mengirim permintaan untuk kode OTP.
            </p>
            <div class="alert alert-warning fw-bold">
                Abaikan pesan ini jika Anda tidak meminta reset password.
            </div>
        </main>

        {{-- Footer --}}
        <footer class="d-flex align-items-center justify-content-center p-3 fw-bold">
            &copy; {{ date('Y') === '2024' ? '2024' : '2024 - ' . date('Y') }}. STMIK Bandung
        </footer>

        {{-- Bootstrap --}}
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

        {{-- Feather Icons --}}
        <script>
            feather.replace()
        </script>
    </body>
</html>
