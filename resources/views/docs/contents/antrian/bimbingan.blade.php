<section>
    <h5 class="mt-4 mb-3 fw-bold">(ADM) Get List Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/list</span> dengan menggunakan HTTP metod <span class="badge bg-info">get</span>. Tambahkan query parameter <span class="badge bg-secondary">sudah</span> dengan isian nilai berupa boolean jika ingin memfilter hasil yang diinginkan, jika nilai yang dikirim false berarti list antrian bimbingannya adalah belum selesai. Penggunaannya seperti <span class="badge bg-dark">/antrian/bimbingan/list?sudah=false</span>. Response yang diberikan:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_antrian": [
            {
                "bimbingan_id": 6,
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220313",
                "nm_mhs": "YOGA PRATAMA",
                "tgl_bimbingan": "2024-06-16",
                "is_sudah": false,
                "created_at": "2024-06-04 20:48:30"
            },
            {
                "bimbingan_id": 5,
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220001",
                "nm_mhs": "SUHAEFI FAUZIAN",
                "tgl_bimbingan": "2024-06-07",
                "is_sudah": false,
                "created_at": "2024-06-04 20:41:07"
            }
        ]
    }
}</code></pre>
    <div class="alert alert-warning">
        <p>
            Anda juga dapat menggunakan dua query parameter yang telah disediakan untuk memfilter antrian berdasarkan nilai <b>is_sudah</b> dan nilai <b>kd_dosen</b>. Berikut adalah beberapa contoh penggunaan dari query parameter yang telah disediakan:
        </p>
        <ul>
            <li><span class="badge bg-dark">/antrian/bimbingan/list?is_sudah=true&kd_dosen=IF054</span></li>
            <li><span class="badge bg-dark">/antrian/bimbingan/list?is_sudah=false</span></li>
            <li><span class="badge bg-dark">/antrian/bimbingan/list?kd_dosen=IF054</span></li>
        </ul>
    </div>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Get Detail Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/detail/{bimbingan_id}</span>, ganti nilai <b>{bimbingan_id}</b> dengan nilai bimbingan_id yang ada saat get list antrian bimbingan. Kirimkan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "antrian": {
            "bimbingan_id": 6,
            "dosen_id": 2,
            "kd_dosen": "IF054",
            "nm_dosen": "MINA ISMU RAHAYU, M.T",
            "nim": "1220313",
            "nm_mhs": "YOGA PRATAMA",
            "tgl_bimbingan": "2024-06-16",
            "is_sudah": false,
            "created_at": "2024-06-04 20:48:30"
        }
    }
}}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Add Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/add</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nim": "1220313",
    "nm_mhs": "Yoga Pratama",
    "dosen_pembimbing": "Mina Ismu Rahayu, M.T",
    "kd_dosen": "IF054",
    "tgl_bimbingan": "07-06-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Status Antrian Bimbingan</h5>
    <p>
        Untuk memperbarui status antrian bimbingan, kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-blue">put</span> ke <span class="badge bg-dark">/antrian/bimbingan/status/update</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 5,
    "is_sudah": true
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/update</span> dengan menggunakan HTTP method <span class="badge bg-blue">put</span> dan sertakan payload dalam JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 6,
    "nim": "1220313",
    "nm_mhs": "Yoga Pratama",
    "dosen_pembimbing": "Mina Ismu Rahayu, M.T",
    "kd_dosen": "IF054",
    "tgl_bimbingan": "16-06-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Hapus Antrian Bimbingan</h5>
    <p>
        Untuk menghapus antrian bimbingan tertentu, kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai <b>bimbingan_id</b> yang akan dihapus dalam payload dengan bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 6
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(DSN) Get List Antrian Bimbingan</h5>
    <p>
        Digunakan oleh dosen untuk get list antrian bimbingan. Kirimkan permintaan ke <span class="badge bg-dark">/antrian/list/bimbingan</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. API akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-json">{
    "status": "success",
    "data": {
        "list_antrian": [
            {
                "bimbingan_id": 6,
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220313",
                "nm_mhs": "YOGA PRATAMA",
                "tgl_bimbingan": "2024-06-16",
                "is_sudah": false,
                "created_at": "2024-06-04 20:48:30"
            },
            {
                "bimbingan_id": 5,
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220001",
                "nm_mhs": "SUHAEFI FAUZIAN",
                "tgl_bimbingan": "2024-06-07",
                "is_sudah": true,
                "created_at": "2024-06-04 20:41:07"
            }
        ]
    }
}</code></pre>
    <div class="alert alert-warning">
        <p>
            Anda juga dapat menggunakan dua query parameter yang telah disediakan untuk memfilter antrian berdasarkan nilai <b>is_sudah</b>. Berikut adalah contoh penggunaan dari query parameter yang telah disediakan:
        </p>
        <ul>
            <li><span class="badge bg-dark">/antrian/list/bimbingan?is_sudah=false</span></li>
        </ul>
    </div>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(DSN) Update Status Antrian Bimbingan</h5>
    <p>
        Digunakan oleh dosen untuk mengubah status antrian bimbingan. Kirimkan permintaan ke <span class="badge bg-dark">/antrian/list/bimbingan</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 5,
    "is_sudah": false
}</code></pre>
</section>