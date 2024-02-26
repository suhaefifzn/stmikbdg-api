@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> ACL (Access Control Level)</h4>
<hr>
<div class="m-2">
    <p>
        Setiap route yang ada di halaman ini hanya digunakan oleh admin untuk mengatur kendali pengguna terhadap sistem seperti menambah pengguna baru, melihat daftar akun, menambah alamat situs, dab mengatur akses akun terhadap suatu alamat web.
    </p>
    <h5 class="mt-4">Menambah User</h5>
    <hr>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirim permintaan ke <span class="badge bg-dark">/users</span> dengan menyertakan payload atau data pada body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kd_user": "1220001",
    "is_dosen": false,
    "is_admin": false,
    "is_mhs": true,
    "is_dev": true,
    "email": "suhaefi@simak.dev",
    "password": "secretlah"
}</code></pre>
    <h5 class="mt-4">Tambah Alamat Web Baru</h5>
    <hr>
    <p>
        Kirim request ke <span class="badge bg-dark">/sites</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan data seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "url": "http://localhost:8000/"
}</code></pre>
    <h5 class="mt-4">Tambah Akses User ke Web</h5>
    <hr>
    <p>
        Untuk menambahkan akses pengguna ke alamat web tertentu maka kirim request ke <span class="badge bg-dark">/sites/user-access</span> dengan HTTP method <span class="badge bg-info">post</span>. Kemudian sertakan data payload body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "user_id": 31,
    "site_id": 1
}</code></pre>
    <h5 class="mt-4">Hapus Akses User ke Web</h5>
    <hr>
    <p>
        Untuk menghapus akses user ke suatu url, kirimkan payload pada body yang berisi <b>user_id</b> dan <b>site_id</b>. Nilai id tersebut didapat setelah melihat daftar pengguna yang ada pada suatu alamat web. Kirimkan request ke <span class="badge bg-dark">/sites/user-access</span> dengan HTTP method <span class="badge bg-info">delete</span>.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "site_id": 3,
    "user_id": 30
}</code></pre>
    <h5 class="mt-4">Lihat List Alamat Web</h5>
    <hr>
    <p>
        Untuk melihat semua alamat web yang ada lakukan request ke <span class="badge bg-dark">/sites</span> dengan HTTP method <span class="badge bg-info">get</span>. Hasilnya seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "sites": [
            {
                "id": 1,
                "url": "http://stmikbdg-boilerplate-web.test/"
            },
            {
                "id": 2,
                "url": "http://stmikbdg-acl.test/"
            },
            {
                "id": 3,
                "url": "http://localhost:8000/"
            }
        ]
    }
}</code></pre>
    <h5 class="mt-4">Lihat Daftar Pengguna Dari Web Tertentu</h5>
    <hr>
    <p>
        Untuk melihat daftar pengguna yang memiliki akses ke web tertentu, lakukan permintaan ke <span class="badge bg-dark">/sites?site_id=id_alamat_web</span>. Isi <span class="badge bg-secondary">site_id</span> dengan id dari alamat web yang ada di list seperti di atas. Misalnya ingin melihat user yang memiliki akses ke url http://stmikbdg-boilerplate-web.test/ maka isi site_id dengan nilai 1. Sehingga secara keseluruhan adalah <span class="badge bg-dark">/sites?site_id=1</span>, kemudian gunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "site_users": [
            {
                "user_id": 30,
                "site_id": 1,
                "url": "http://stmikbdg-boilerplate-web.test/",
                "user_email": "suhaefi@simak.dev"
            }
        ]
    }
}</code></pre>
    <h5 class="mt-4">Import User Dari Excel</h5>
    <hr>
    <p>
        Untuk impor user baru menggunakan file excel, saat ini harus mengikuti format excel dengan memiliki seperti berikut:
        <div class="img-wrapper">
            <img src="https://lh3.googleusercontent.com/drive-viewer/AKGpihZAXBswaf8fBsaUb0hudlSQ5ll6Vmdz3ZgYGabrQoecmDedKca8PxKeIuOX_ZW34f2MY-cHRGthCgeqFLXVY_PusMJk=s2560" alt="Format Import User File Excel" class="m-2 img-fluid">
        </div>
        Nilai <b>Kd User</b> dapat berupa kode dosen atau nim yang kemudian diikuti dengan nilai <b>Is Dosen</b>. Apabila <b>Kd User</b> berisi kode dosen maka pastikan nilai <b>Is Dosen</b> adalah true.
    </p>
    <p>
        Kirim request ke <span class="badge bg-dark">/users?import=excel</span> menggunakan HTTP method <span class="badge bg-info">post</span>. Jangan lupa sertakan file excel-nya dengan format seperti gambar di atas, kemudian kirim sebagai payload body berupa form data. Jika berhasil maka akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Data user pada file excel berhasil ditambahkan"
}</code></pre>
</div>
@endsection
