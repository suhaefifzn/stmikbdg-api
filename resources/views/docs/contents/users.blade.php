@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> Users</h4>
<hr>
<div class="m-2">
    <h5 class="mt-4">User Profile dan Account</h5>
    <hr>
    <p>
        Untuk mendapatkan data profile dan akun pengguna aktif atau pengguna terautentikasi dilakukan dengan cara mengirim permintaan ke <span class="badge bg-dark">/users/me</span> menggunakan HTTP method <span class="badge bg-info">get</span> dan jangan lupa sertakan <b>headers</b> seperti yang telah dijelaskan pada bagian Authentications.
    </p>
    <p>Jika permintaan berhasil, maka akan memberikan response seperti berikut:</p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "mahasiswa": {
            "kd_kampus": "A ",
            "mhs_id": 15534,
            "angkatan_id": 76,
            "kelas": "A",
            "nim": "1220001",
            "jk": "L",
            "dosen_id": 573,
            "jur_id": 23,
            "jns_mhs": "R",
            "sts_mhs": "A",
            "angkatan": 2020,
            "nama_jurusan": "S1 - TEKNIK INFORMATIKA",
            "dosen_wali": "Mina Ismu Rahayu, M.T",
            "tmp_lahir": "BANDUNG",
            "tgl_lahir": "2002-01-21",
            "masuk_semester": 0,
            "masuk_tahun": 2020,
            "nama": "SUHAEFI FAUZIAN"
        },
        "account": {
            "email": "suhaefi@simak.dev",
            "image": "http://stmikbdg-api.test/storage/users/images/college_student.png",
            "is_admin": false,
            "is_dosen": false,
            "is_mhs": true,
            "is_dev": true
        }
    }
}</code></pre>
    <p>
        Perhatikan pada nilai <b>account</b>. Bagian tersebut berisi informasi akun yang digunakan untuk mengakses API dan juga role dari si pemilik. Anda dapat menyimpan setiap nilai dalam properti <b>account</b> untuk digunakan dalam mengatur akses ke setiap fitur yang dibuat pada sisi Front-End.
    </p>
    <p>
        Demikian juga pada sisi API, kami menggunakan setiap nilai yang ada untuk menentukan akses pengguna ke endpoint. <b>Jadi tidak semua pengguna dapat mengakses endpoint yang ada di API ini</b>.
    </p>

    {{-- Ganti Email --}}
    <h5 class="mt-4">Update Email</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/users/me</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan kirimkan email baru dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "email": "emailbaru@simak.dev"
}</code></pre>
    <p>
        Email baru haruslah unik dalam artian berbeda dengan pengguna lainnya karena digunakan untuk login. Jika email yang digunakan telah terpakai maka API akan memberikan response yang menyatakan validasi email gagal dengan status code <span class="badge bg-warning">422</span>.
    </p>
    <div class="alert alert-warning">
        <b>Perlu diperhatikan!</b> Kemungkinan API digunakan oleh beberapa sistem, sehingga mengganti email artinya mengganti email pengguna yang bersangkutan pada seluruh sistem yang menggunakan API ini.
    </div>

    {{-- Ganti Password --}}
    <h5 class="mt-5">Update Password</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/users/me/password</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan mengirim payload dengan format:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "current_password": "password_saat_ini",
    "new_password": "password_baru"
}</code></pre>
    <p>
        Apabila berhasil maka akan memberikan response yang terdapat pesan yang menyatakan bahwa perubahan password telah berhasil dengan status code <span class="badge bg-success">200</span>. Setelah password berhasil diubah sangat direkomendasikan untuk logout kemudian login kembali atau autentikasi ulang.
    </p>
    <div class="alert alert-warning">
        <b>Perlu diperhatikan!</b> Kemungkinan API digunakan oleh beberapa sistem, sehingga mengganti password artinya mengganti password pengguna yang bersangkutan pada seluruh sistem yang menggunakan API ini.
    </div>

    {{-- Ganti foto profile --}}
    <h5 class="mt-4">Update Foto Profile</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/users/me/image</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan payload berupa file dengan ekstensi png atau jpg, jenis payloadnya adalah form data. Maksimal ukuran file gambarnya adalah 1 MB.
    </p>
    <p>
        Jika berhasil diganti maka akan memberikan response yang menyisipkan url gambar baru sebagai nilai dari properti <b>image</b>.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
        "status": "success",
        "message": "Foto profile berhasil diperbaharui.",
        "data": {
            "image": "http://stmikbdg-api.test/storage/users/images/qWXlHi9GRct9DCsNK4QFzgognGQvS8re6RGlpZnR.jpg"
        }
}</code></pre>
    <div class="alert alert-warning">
        <b>Perlu diperhatikan!</b> Kemungkinan API digunakan oleh beberapa sistem, sehingga mengganti foto progile artinya mengganti foto progile pengguna yang bersangkutan pada seluruh sistem yang menggunakan API ini.
    </div>

    {{-- Daftar web yang bisa diakses user --}}
    <h5 class="mt-4">Get Daftar Web yang Bisa Diakses</h5>
    <hr>
    <p>
        Digunakan untuk get list web yang bisa diakses oleh user. Kirim permintaan ke <span class="badge bg-dark">/sites</span> menggunakan HTTP method <span class="badge bg-info">get</span>, jika berhasil akan mengembalikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "sites": [
            {
                "id": 1,
                "url": "http://stmikbdg-acl.test/"
            }
        ]
    }
}</code></pre>
    <p>
        List web yang bisa diakses oleh user terdapat pada properti <b>sites</b>.
    </p>
</div>
@endsection
