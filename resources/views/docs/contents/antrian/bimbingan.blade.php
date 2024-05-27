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
                "bimbingan_id": 1,
                "dosen_id": 2,
                "kd_dosen": "MN",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220001",
                "nm_mhs": "Suhaefi Fauzian",
                "tgl_bimbingan": "2024-05-27",
                "sudah": false,
                "created_at": "2024-05-27 16:59:02"
            }
        ]
    }
}</code></pre>
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
            "bimbingan_id": 1,
            "dosen_id": 2,
            "kd_dosen": "MN",
            "nm_dosen": "MINA ISMU RAHAYU, M.T",
            "nim": "1220001",
            "nm_mhs": "Suhaefi Fauzian",
            "tgl_bimbingan": "2024-05-27",
            "sudah": false,
            "created_at": "2024-05-27 16:59:02"
        }
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Add Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/add</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nim": "1220001",
    "nm_mhs": "Suhaefi Fauzian",
    "dosen_pembimbing": "Mina Ismu Rahayu, M.T",
    "tgl_bimbingan": "27-05-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Status Antrian Bimbingan</h5>
    <p>
        Untuk memperbarui status antrian bimbingan, kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-blue">put</span> ke <span class="badge bg-dark">/antrian/bimbingan/status/update</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 1,
    "sudah": true
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Antrian Bimbingan</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/update</span> dengan menggunakan HTTP method <span class="badge bg-blue">put</span> dan sertakan payload dalam JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 1,
    "nim": "1220001",
    "nm_mhs": "Suhaefi Fauzian",
    "dosen_pembimbing": "Rena Wijaya, S.Kom",
    "tgl_bimbingan": "02-06-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Hapus Antrian Bimbingan</h5>
    <p>
        Untuk menghapus antrian bimbingan tertentu, kirimkan permintaan ke <span class="badge bg-dark">/antrian/bimbingan/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai <b>bimbingan_id</b> yang akan dihapus dalam payload dengan bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "bimbingan_id": 1
}</code></pre>
</section>