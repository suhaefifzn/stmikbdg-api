<div class="m-2">
    <p>
        Authentications digunakan untuk generate dan hapus access token atau biasa dikenal dengan login dan logout. Kedua proses tersebut dapat dilakukan dengan mengakses satu URI yang sama tetapi dengan HTTP method yang berbeda, yaitu <span class="badge bg-dark">/authentications</span>.
    </p>

    {{-- Login --}}
    <h5 class="mt-4">Login - Generate Access Token</h5>
    <hr>
    <p>
        Agar dapat mengakses setiap URI atau endpoint yang ada pada API diperlukan sebuah access token yang diperoleh dari hasil pengecekan kredensial pengguna. Kredensial pengguna yang perlu dikirim adalah berupa payload berisi email dan password yang disimpan dalam format JSON dan dikirim menggunakan HTTP method <span class="badge bg-info">post</span> ke <span class="badge bg-dark">/authentications</span>.
    </p>
    <p>Contoh payload:</p>
    <pre><code class="language-json bg-primary-subtle">{
    "email": "suhaefi@simak.dev",
    "password": "1220001@simak.dev"
}</code></pre>
    <div class="alert alert-warning">
        <b>Perlu diperhatikan!</b> Saat ini API digunakan oleh dua platform yang berbeda yakni android dan web, token memiliki waktu kadaluarsa yang berbeda untuk setiap platformnya. Karena hal tersebut, maka tambahkan query <span class="badge bg-secondary">platform</span> setelah <span class="badge bg-dark">/authentications</span> dan isi nilainya dengan <i>android</i> atau <i>web</i>. Sehingga menjadi <span class="badge bg-dark">/authentications?platform=android</span>
    </div>
    <p>
        Apabila kredensial yang dikirim sesuai, maka API akan mengembalikan response dalan bentuk JSON dengan status code <span class="badge bg-success">201</span> yang menandakan bahwa permintaan berhasil diproses dan dibuatnya access token. Response tersebut kurang lebih seperti:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "token": {
            "access_token": "eyJ0eXxxxxxxxxxxxx",
            "token_type": "bearer",
            "expires_in": null
        },
        "platform": "android"
    }
}</code></pre>
    <p>
        Nilai <b>expires_in</b> ditentukan berdasarkan isi dari nilai query platform yang dikirim. Jika platformnya adalah android maka token akan memiliki akses waktu yang dapat dikatakan permanen. Jika platformnya adalah web, maka access token hanya berlaku untuk 6 jam. Simpanlah nilai yang terdapat pada <b>access_token</b> dengan baik dan aman, karena <b>token tersebut akan selalu digunakan untuk mengirim permintaan ke API</b>. Perhatikan juga pada <b>token_type</b> yang berisi bearer, memberikan informasi bahwa jenis Authorization yang digunakan adalah Bearer Token.
    </p>
    <p>
        Gunakan <b>access_token</b> tersebut setiap kali melakukan request atau permintaan ke API. Simpan dalam headers setiap kali request, kecuali saat melakukan login.
    </p>
    <p>Berikut adalah format <b>headers</b> yang biasa digunakan:</p>
    <pre><code class="language-json bg-primary-subtle">{
    "Content-Type": "application/json",
    "Authorization": "Bearer eyJ0eXxxxxxxxxxxxx"
}</code></pre>
    
    {{-- Logout --}}
    <h5 class="mt-4">Logout - Hapus Access Token</h5>
    <hr>
    <p>
        Untuk menghapus access token cukup lakukan permintaan ke <span class="badge bg-dark">/authentications</span> menggunakan HTTP method <span class="badge bg-info">delete</span>. Sertakan juga <b>headers</b> seperti di atas. Apabila berhasil maka access token tersebut tidak dapat digunakan lagi untuk mengirim request ke API, logout berhasil ditandai dengan response yang berisi seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Logout berhasil. Access token telah dihapus"
}</code></pre>
</div>