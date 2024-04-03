@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> Pengajuan Wisuda</h4>
<hr>
<div class="m-2">
    <h5 class="mt-4">
        (MHS) Add Pengajuan Pendaftaran Wisuda
    </h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload dengan format JSON pada body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nim": "1219019",
    "nama": "Deva Aditya Octavian",
    "nik": "322xxxxxxxxx",
    "tempat_lahir": "Bandung",
    "tgl_lahir": "02-04-2024",
    "email": "deva@simak.dev",
    "no_hp": "088xxxxxx",
    "tgl_sidang_akhir": "20-01-2024",
    "file_bukti_pembayaran": "asdasd.pdf",
    "file_ktp": "ktp.pdf",
    "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf"
}</code></pre>
    <div class="alert alert-warning">
        Untuk setiap file yang ada hanya kirimkan storage path dari file yang telah berhasil disimpan pada sisi frontend. Hal ini sesuai dengan kesepakatan saat diskusi di Jumat, 29 Maret 2024.
    </div>
    <h5 class="mt-4">
        (MHS) Get Detail Pengajuan
    </h5>
    <hr>
    <p>
        Untuk melihat detail pengajuan pendaftaran sendiri (sesuai dengan pemilik akun yang login ke sistem) kirimkan ke permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/{nim}</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>nim</b> dengan nim si pemilik akun yang login. Misalnya login sebagai Deva Aditya Octavian dengan NIM 1219019 maka URL-nya menjadi <span class="badge bg-dark">/wisuda/pengajuan/1219019</span>. Jika berhasil akan memberikan respons seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pengajuan_wisuda": {
            "pengajuan_id": 36,
            "status_id": 2,
            "jadwal_id": null,
            "file_id": 32,
            "nim": "1219019",
            "nama": "Deva Aditya Octavian",
            "nik": "322xxxxxxxxx",
            "tempat_lahir": "Bandung",
            "tgl_lahir": "02-04-2024",
            "email": "deva@simak.dev",
            "no_hp": "088xxxxxx",
            "kd_status": "M1",
            "ket_status": "Menunggu Review",
            "tgl_wisuda": null,
            "file_bukti_pembayaran": "asdasd.pdf",
            "file_ktp": "ktp.pdf",
            "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf",
            "tgl_sidang_akhir": "20-01-2024",
            "tgl_pengajuan": "2024-04-03 11:44:10"
        }
    }
}</code></pre>
    <h5 class="mt-4">
        (MHS) Get Status Pengajuan
    </h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/{nim}/status</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>nim</b> dengan NIM mahasiswa pemilik akun yang login. Misalnya, login sebagai Deva Aditya Octavian dengan NIM 1219019, maka URL-nya menjadi <span class="badge bg-dark">/wisuda/pengajuan/1219019/status</span>. Hasilnya seperti di bawah ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pengajuan_wisuda": {
            "pengajuan_id": 36,
            "kd_status": "M1",
            "ket_status": "Menunggu Review",
            "tgl_pengajuan": "2024-04-03 11:44:10"
        }
    }
}</code></pre>
    <h5 class="mt-4">
        (ADM) Add Pengajuan By Admin
    </h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/add-by-admin</span> dengan HTTP method <span class="badge bg-dark">post</span>, dan sertakan payload body yang sama seperti pada bagian <b>(MHS) Add Pengajuan Pendaftaran Wisuda</b>. Karena yang melakukan pengajuan adalah akun Admin, maka admin dapat membantu membuat pengajuan untuk mahasiswa selama nim dari mahasiswa yang dibantu terdaftar sebagai mahasiswa STMIK Bandung.
    </p>
    <h5 class="mt-4">
        (ADM) Get List Pengajuan Pendaftaran Wisuda
    </h5>
    <hr>
    <p>
        Untuk melihat semua daftar pengajuan pendaftaran yang dilakukan mahasiswa, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/list/pendaftar</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil akan memberikan repons seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_pengajuan": [
            {
                "pengajuan_id": 36,
                "status_id": 2,
                "jadwal_id": null,
                "file_id": 32,
                "nim": "1219019",
                "nama": "Deva Aditya Octavian",
                "nik": "322xxxxxxxxx",
                "tempat_lahir": "Bandung",
                "tgl_lahir": "02-04-2024",
                "email": "deva@simak.dev",
                "no_hp": "088xxxxxx",
                "kd_status": "M1",
                "ket_status": "Menunggu Review",
                "tgl_wisuda": null,
                "file_bukti_pembayaran": "asdasd.pdf",
                "file_ktp": "ktp.pdf",
                "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf",
                "tgl_sidang_akhir": "20-01-2024",
                "tgl_pengajuan": "2024-04-03 11:44:10"
            }
        ]
    }
}</code></pre>
    <p>
        URL untuk get list pengajuan pendaftaran wisuda ini dapat digunakan juga untuk memfilter pengajuan berdasarkan statusnya. Misal, untuk mendapatkan daftar pengajuan berdasarkan status pengajuan diterima maka tambahkan query <span class="badge bg-secondary">?kd_status=S1</span>. Sehingga penggunaannya menjadi <span class="badge bg-dark">/wisuda/pengajuan/list/pendaftar?kd_status=S1</span> dan bentuk respons-nya sama seperti di atas.
    </p>
    <div class="alert alert-warning">
        URL untuk get list pengajuan pendaftaran wisuda dapat digunakan untuk rekap daftar pengajuan yang telah diterima dengan menggunakan query <b>kd_status=S1</b>. Sesuai dengan hasil diskusi bahwa hasil rekap adalah pengajuan yang telah diterima dan akan berbentuk file excel yang diproses pada sisi frontend.
    </div>
    <h5 class="mt-4">
        (ADM) Get List Status Tersedia yang Dapat Digunakan
    </h5>
    <hr>
    <p>
        Untuk melihat status apa saja yang digunakan pada pengajuan pendaftaran wisuda, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/list/status-tersedia</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_status": [
            {
                "status_id": 1,
                "kd_status": "M2",
                "ket_status": "Menunggu VERDIG"
            },
            {
                "status_id": 2,
                "kd_status": "M1",
                "ket_status": "Menunggu Review"
            },
            {
                "status_id": 3,
                "kd_status": "S1",
                "ket_status": "Disetujui"
            },
            {
                "status_id": 4,
                "kd_status": "T2",
                "ket_status": "Ditolak VERDIG"
            },
            {
                "status_id": 5,
                "kd_status": "T1",
                "ket_status": "Ditolak"
            },
            {
                "status_id": 6,
                "kd_status": "B2",
                "ket_status": "Belum Mengajukan VERDIG"
            }
        ]
    }
}</code></pre>
    <p>
        Pada sisi frontend bagian admin hanya menggunakan dua status saja yaitu yang memiliki <b>"kd_status": "S1"</b> dan <b>"kd_status": "T1"</b>. Sisanya digunakan pada sistem API untuk menentukan status pengajuan otomatis berdasarkan pada status pengajuan verifikasi digital milik mahasiswa.
    </p>
    <h5 class="mt-4">
        (ADM) Get Detail Pengajuan Pendaftaran Wisuda
    </h5>
    <hr>
    <p>
        Admin dapat melihat detail pengajuan pendaftaran mahasiswa dengan mengirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/detail/{nim}</span>, ganti <b>nim</b> dengan NIM milik mahasiswa yang telah mengajukan pendaftaran wisuda. Misalnya seperti <span class="badge bg-dark">/wisuda/pengajuan/detail/1219019</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "detail_pengajuan": {
            "pengajuan_id": 36,
            "status_id": 2,
            "jadwal_id": null,
            "file_id": 32,
            "nim": "1219019",
            "nama": "Deva Aditya Octavian",
            "nik": "322xxxxxxxxx",
            "tempat_lahir": "Bandung",
            "tgl_lahir": "02-04-2024",
            "email": "deva@simak.dev",
            "no_hp": "088xxxxxx",
            "kd_status": "M1",
            "ket_status": "Menunggu Review",
            "tgl_wisuda": null,
            "file_bukti_pembayaran": "asdasd.pdf",
            "file_ktp": "ktp.pdf",
            "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf",
            "tgl_sidang_akhir": "20-01-2024",
            "tgl_pengajuan": "2024-04-03 11:44:10"
        }
    }
}</code></pre>
    <h5 class="mt-4">
        (ADM) Update Pengajuan Pendaftaran Wisuda
    </h5>
    <hr>
    <p>
        Gunakan HTTP method <span class="badge bg-info">put</span>, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/detail/{nim}</span>, ganti <b>nim</b> dengan NIM mahasiswa yang mengajukan pendaftaran, kemudian sertakan payload body dalam format JSON seperti di bawah ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "pengajuan_id": 36,
    "nim": "1219019",
    "nama": "Deva Aditya Octavian",
    "nik": "322xxxxxxx",
    "tempat_lahir": "Bandung",
    "tgl_lahir": "02-04-2024",
    "email": "deva@simak.dev",
    "no_hp": "088xxxxxxxx",
    "tgl_sidang_akhir": "20-01-2024",
    "file_bukti_pembayaran": "asdasd.pdf",
    "file_ktp": "ktp.pdf",
    "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf",
    "kd_status": "S1",
    "tgl_wisuda": "30-08-2024"
}</code></pre>
    <div class="alert alert-warning">
        <b>Perlu diperhatikan!</b><br>
        Jika mengirim nilai <b>kd_status</b> berisi <b>S1</b> yang berarti bahwa admin menyatakan bahwa pengajuan pendaftaran tersebut diterima maka wajib mengisi nilai <b>tgl_wisuda</b> dengan format <b>dd-mm-YYYY</b>, contoh: 31-03-2024.
    </div>
</div>
    <h5 class="mt-4">
        (ADM) Delete Pengajuan Mahasiswa
    </h5>
    <hr>
    <p>
        Admin dapat menghapus pengajuan pendaftaran wisuda milik mahasiswa, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/{nim}/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span>, ganti <b>nim</b> dengan NIM yang pernah mengajukan pendaftaran wisuda. Jika berhasil dihapus akan memberikan respons:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Pengajuan mahasiswa dengan NIM 1219019 berhasil dihapus"
}</code></pre>
    <h5 class="mt-4">
        (ADM) Get Statistik Pengajuan
    </h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/statistik/pendaftaran</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> untuk mendapatkan angka total pengajuan yang diajukan dan pengajuan yang diterima. Respons-nya akan berbentuk seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "statistik_pengajuan": {
            "total_pengajuan": 1,
            "total_pengajuan_diterima": 0,
            "total_pengajuan_jurusan_si_diterima": 0,
            "total_pengajuan_jurusan_if_diterima": 1
        }
    }
}</code></pre>
@endsection
