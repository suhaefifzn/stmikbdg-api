<section>
    <h5 class="mt-4 mb-3 fw-bold">
        (ADM) Get List Status Tersedia yang Dapat Digunakan
    </h5>
    <p>
        Untuk melihat status apa saja yang digunakan pada pengajuan pendaftaran wisuda, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/list/status-tersedia</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_status": [
            {
                "status_id": 1,
                "kd_status": "M",
                "ket_status": "Menunggu"
            },
            {
                "status_id": 2,
                "kd_status": "S",
                "ket_status": "Disetujui"
            },
            {
                "status_id": 3,
                "kd_status": "T",
                "ket_status": "Ditolak"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Get Detail Pengajuan Pendaftaran Wisuda Milik Mahasiswa
    </h5>

    <p>
        Admin dapat melihat detail pengajuan pendaftaran mahasiswa dengan mengirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/detail/{nim}</span>, ganti <b>nim</b> dengan NIM milik mahasiswa yang telah mengajukan pendaftaran wisuda. Misalnya seperti <span class="badge bg-dark">/wisuda/pengajuan/detail/1220001</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "detail_pengajuan": {
            "pengajuan_id": 3,
            "status_id": 1,
            "jadwal_wisuda_id": null,
            "file_id": 2,
            "nim": "1220001",
            "nama": "Suhaefi Fauzian",
            "nik": "322xxxxxxxxx",
            "tempat_lahir": "Bandung",
            "tgl_lahir": "21-01-2002",
            "email": "suhaefi@simak.dev",
            "no_hp": "0857xxxxxx",
            "judul_skripsi": "EDIT JUDUL INTEGRASI SISTEM STMIK BANDUNG DALAM BENTUK API (APPLICATION PROGRAMMING INTERFACE)",
            "kd_status": "M",
            "ket_status": "Menunggu",
            "file_bukti_pembayaran": "edit-asdasd.pdf",
            "file_ktp": "edit-ktp.pdf",
            "file_bukti_pembayaran_sumbangan": "edit-asdasdlk.pdf",
            "file_ijazah": "ijazah.jpg",
            "is_bayar": null,
            "is_ditolak": null,
            "ditolak_alasan": null,
            "tgl_sidang_akhir": "20-07-2024",
            "tgl_wisuda": null,
            "tgl_pengajuan": "2024-04-04 15:09:47"
        }
    }
}</code></pre>
</section>

<!--<h5 class="mt-5 mb-3 fw-bold">
    (ADM) Edit Pengajuan Pendaftaran Wisuda Milik Mahasiswa
</h5>
<p>
    Gunakan HTTP method <span class="badge bg-info">put</span>, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/{nim}/update-by-admin</span>, ganti <b>nim</b> dengan NIM mahasiswa yang mengajukan pendaftaran, contohnya <span class="badge bg-dark">/wisuda/pengajuan/1220001/update-by-admin</span>. Kemudian, sertakan payload body dalam format JSON seperti di bawah ini:
</p>
<pre><code class="language-json bg-primary-subtle">{
"pengajuan_id": 3,
"nim": "1220001",
"nama": "Suhaefi Fauzian",
"nik": "322xxxxxxxxx",
"tempat_lahir": "Bandung",
"tgl_lahir": "21-01-2002",
"email": "suhaefi@simak.dev",
"no_hp": "0857xxxxxx",
"tgl_sidang_akhir": "20-07-2024",
"file_bukti_pembayaran": "contoh-edit-by-admin-asdajskhd.pdf",
"file_ktp": "contoh-edit-by-admin-ktp.pdf",
"file_bukti_pembayaran_sumbangan": "edit-by-admin-asdasdlk.pdf",
"file_ijazah": "ijazah.jpg",
"judul_skripsi": "CONTOH DIEDIT SAMA ADMIN"
}</code></pre>
<div class="alert alert-warning">
    <b>Peringatan!</b><br>
    Melakukan update atau edit terhadap pengajuan akan mengembalikan status menjadi menunggu.
</div>-->

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Verifikasi Pengajuan Pendaftaran Wisuda Milik Mahasiswa
    </h5>

    <p>
        Untuk memverifikasi pengajuan pendaftaran wisuda milik mahasiswa, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/{nim}/verifikasi</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>, ganti <b>nim</b> dengan NIM milik mahasiswa yang akan diverifikasi pengajuannya. Misalnya <span class="badge bg-dark">/wisuda/pengajuan/1220001/verifikasi</span> dan sertakan data dalam format JSON sebagai payload body dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "pengajuan_id": 3,
    "nim": "1220001",
    "is_bayar": false,
    "ditolak_alasan": "Bukti pembayaran tidak sah"
    }</code></pre>
    <p>
        Contoh di atas adalah untuk menolak pengajuan wisuda, ditandai dengan nilai <b>is_bayar</b> diisikan dengan false dan nilai <b>ditolak_alasan</b> haruslah diisi. Jika pengajuan disetujui, maka cukup nilai <b>is_bayar</b> adalah true dan tidak perlu menyertakan nilai pada <b>ditolak_alasan</b>. Contoh jika pengajuan akan disetujui:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "pengajuan_id": 3,
    "nim": "1220001",
    "is_bayar": true
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Get Statistik Pengajuan
    </h5>

    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/statistik/pendaftaran</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> untuk mendapatkan angka total pengajuan yang diajukan dan pengajuan yang diterima. Respons akan berbentuk seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "statistik_pengajuan": {
            "total_pengajuan": 1,
            "total_pengajuan_diterima": 1,
            "total_pengajuan_jurusan_si_diterima": 0,
            "total_pengajuan_jurusan_if_diterima": 1
        }
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Get List Jadwal dan Angkatan Wisuda
    </h5>

    <p>
        Admin dapat mengetahui daftar jadwal dan angkatan wisuda setiap tahunnya. Kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/list/jadwal</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jadwal_wisuda": [
            {
                "jadwal_wisuda_id": 2,
                "tahun": 2024,
                "tgl_wisuda": "23-09-2024",
                "angkatan_wisuda": 33
            },
            {
                "jadwal_wisuda_id": 1,
                "tahun": 2023,
                "tgl_wisuda": "20-09-2023",
                "angkatan_wisuda": 32
            }
        ]
    }
}</code></pre>
    <p>
        Untuk mendapatkan jadwal dan angkatan wisuda yang sedang aktif tambahkan <span class="badge bg-secondary">?aktif=true</span>, sehingga menjadi <span class="badge bg-dark">/wisuda/pengajuan/list/jadwal?aktif=true</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jadwal_wisuda": {
            "jadwal_wisuda_id": 2,
            "tahun": 2024,
            "tgl_wisuda": "23-09-2024",
            "angkatan_wisuda": 33
        }
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Add Jadwal Wisuda
    </h5>
    <p>
        Untuk menambah jadwal wisuda, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/jadwal/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan data dalam bentuk JSON sebagai payload body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tgl_wisuda": "23-09-2025"
    }</code></pre>
    <p>
        Secara otomatis nilai <b>angkatan_wisuda</b> akan bertambah satu. Penambahan ini dilakukan dengan mengambil jadwal kuliah terbaru yang ada pada tabel didatabase.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Edit Jadwal dan Angkatan Wisuda
    </h5>
    <p>
        Bila dirasa ada tahun dan angkatan pada jadwal wisuda yang tidak sesuai, Anda dapat mengubahnya secara manual dengan mengirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/jadwal/{tahun}/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span>, ganti <b>tahun</b> dengan tahun wisuda yang ada dan sertakan data dalam format JSON seperti di bawah ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tgl_wisuda": "23-09-2026",
    "angkatan_wisuda": 34
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">
        (ADM) Get List Pengajuan Pendaftaran Wisuda
    </h5>
    <p>
        Untuk mendapatkan daftar pengajuan wisuda, lakukan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/list/pendaftar</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_pengajuan": [
            {
                "pengajuan_id": 3,
                "status_id": 2,
                "jadwal_wisuda_id": 2,
                "file_id": 2,
                "nim": "1220001",
                "nama": "Suhaefi Fauzian",
                "kd_jur": "12",
                "nik": "322xxxxxxxxx",
                "tempat_lahir": "Bandung",
                "tgl_lahir": "21-01-2002",
                "email": "suhaefi@simak.dev",
                "no_hp": "0857xxxxxx",
                "judul_skripsi": "CONTOH DIEDIT SAMA ADMIN",
                "kd_status": "S",
                "ket_status": "Disetujui",
                "file_bukti_pembayaran": "contoh-edit-by-admin-asdajskhd.pdf",
                "file_ktp": "contoh-edit-by-admin-ktp.pdf",
                "file_bukti_pembayaran_sumbangan": "edit-by-admin-asdasdlk.pdf",
                "file_ijazah": "ijazah.jpg",
                "is_bayar": true,
                "is_ditolak": false,
                "ditolak_alasan": null,
                "tgl_sidang_akhir": "20-07-2024",
                "tgl_wisuda": "23-09-2024",
                "tgl_pengajuan": "2024-04-04 15:13:42"
            },
            {
                "pengajuan_id": 4,
                "status_id": 1,
                "jadwal_wisuda_id": null,
                "file_id": 2,
                "nim": "1220288",
                "nama": "Firman Alamsah",
                "kd_jur": "12",
                "nik": "321xxxxxxxxx",
                "tempat_lahir": "Bandung",
                "tgl_lahir": "20-05-1998",
                "email": "firman@simak.dev",
                "no_hp": "0821xxxxxx",
                "judul_skripsi": "CONTOH JUDUL SKRIPSINYA",
                "kd_status": "M",
                "ket_status": "Menunggu",
                "file_bukti_pembayaran": "sdajskhd.pdf",
                "file_ktp": "ktp.pdf",
                "file_bukti_pembayaran_sumbangan": "asdasdlk.pdf",
                "file_ijazah": "ijazah.jpg",
                "is_bayar": true,
                "is_ditolak": false,
                "ditolak_alasan": null,
                "tgl_sidang_akhir": "20-07-2024",
                "tgl_wisuda": null,
                "tgl_pengajuan": "2024-04-04 15:13:42"
            },
            ...
        ]
    }
}</code></pre>
    <p>
        Akses get list pengajuan mahasiswa ini dapat digunakan juga untuk rekap berdasarkan status yang telah disetujui dan tahun wisuda. Caranya tambahan query parameter ke URL seperti <span class="badge bg-dark">/wisuda/pengajuan/list/pendaftar?kd_status=S&tahun=2024</span>, contoh tersebut berarti mendapatkan list pengajuan dengan status yang sudah disetujui (S) dan tahun wisudanya adalah 2024. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_pengajuan": [
            {
                "pengajuan_id": 3,
                "status_id": 2,
                "jadwal_wisuda_id": 2,
                "file_id": 2,
                "nim": "1220001",
                "nama": "Suhaefi Fauzian",
                "kd_jur": "12",
                "nik": "322xxxxxxxxx",
                "tempat_lahir": "Bandung",
                "tgl_lahir": "21-01-2002",
                "email": "suhaefi@simak.dev",
                "no_hp": "0857xxxxxx",
                "judul_skripsi": "CONTOH DIEDIT SAMA ADMIN",
                "kd_status": "S",
                "ket_status": "Disetujui",
                "file_bukti_pembayaran": "contoh-edit-by-admin-asdajskhd.pdf",
                "file_ktp": "contoh-edit-by-admin-ktp.pdf",
                "file_bukti_pembayaran_sumbangan": "edit-by-admin-asdasdlk.pdf",
                "file_ijazah": "ijazah.jpg",
                "is_bayar": true,
                "is_ditolak": false,
                "ditolak_alasan": null,
                "tgl_sidang_akhir": "20-07-2024",
                "tgl_wisuda": "23-09-2024",
                "tgl_pengajuan": "2024-04-04 15:13:42"
            }
        ]
    }
}</code></pre>
    <p>
        Kode status yang tersedia saat ini adalah:
    </p>
    <ul>
        <li><b>M</b>, adalah menunggu</li>
        <li><b>S</b>, adalah disetujui</li>
        <li><b>T</b>, adalah ditolak</li>
    </ul>
    <div class="alert alert-warning">
        <b>Peringatan!</b><br>
        Sesuai dengan hasil diskusi bahwa rekap hanyalah untuk pengajuan yang telah diterima dan dapat difilter berdasarkan tahun wisuda.
        <br>
        Pada hasil diskusi juga disebutkan bahwa rekap juga harus dapat diekspor ke file excel, yang mana pengelolaan ekspor tersebut dilakukan pada sisi frontend.
    </div>
</section>

<section>
    <h5 class="mt-5 mb-3">(ADM) Delete Jadwal Wisuda</h5>
    <p>
        Untuk menghapus jadwal wisuda, kirimkan permintaan ke <span class="badge bg-dark">/wisuda/pengajuan/jadwal/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload dalam bentuk JSON dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "jadwal_wisuda_id": 3
}</code></pre>
</section>
