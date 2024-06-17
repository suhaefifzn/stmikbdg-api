<section>
    <h5 class="mt-4 mb-3 fw-bold">(ADM) Get All Pengumuman</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/pengumuman/admin/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika terdapat pengumuman, API akan memberikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_pengumuman": [
            {
                "pengumuman_id": 21,
                "target": 0,
                "pengirim": 1,
                "nm_pengirim": "Admin STMIK Bandung",
                "tgl_dikirim": "2024-06-17 19:37:59",
                "image": "http://stmikbdg-api.test/storage/pengumuman/images/Screenshot1.png",
                "message": "Test pengumuman oleh Admin"
            },
            {
                "pengumuman_id": 17,
                "target": 3262,
                "pengirim": 573,
                "nm_pengirim": "Mina Ismu Rahayu, M.T",
                "tgl_dikirim": "2024-06-17 18:08:47",
                "image": null,
                "message": "Test pengumuman mata kuliah"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Kirim Pengumuman</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/pengumuman/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan data atau payload dalam bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "target": 0,
    "image": null,
    "message": "Test pengumuman oleh Admin"
}</code></pre>
    <p>
        Saat ini admin hanya dapat mengirim pengumuman ke semua mahasiswa yang telah terdaftar device tokennya. Hal ini dikarenakan admin adalah entitas baru yang terdapat pada database berbeda, sehingga entitas admin tidak memiliki relasi terhadap entitas lain seperti mahasiswa dan mata kuliah.
    </p>
    <p>
        Jika ingin menambahkan gambar pada pengumuman, maka kirimkan terlebih dahulu gambar tersebut ke <span class="badge bg-dark">/file/image/add?to=pengumuman</span>. Jika berhasil API akan memberikan response yang berisi link gambar yang telah ditambahkan, seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Image berhasil diupload",
    "data": {
        "image": "http://stmikbdg-api.test/storage/pengumuman/images/Screenshot1.png"
    }
}</code></pre>
    <p>
        Kirimkan nama file gambar yang sama sebagai nilai <b>image</b> saat akan mengirim pengumuman, sehingga seperti:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "target": 0, // semua
    "image": "Screenshot1.png",
    "message": "Test pengumuman oleh Admin"
}</code></pre>
</section>
