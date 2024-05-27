<section>
    <h5 class="mt-4 mb-3 fw-bold">(ADM) Get List Antrian Sidang</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/sidang/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil API akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_antrian": [
            {
                "sidang_id": 1,
                "dosen_id": 2,
                "kd_dosen": "MN",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "nim": "1220001",
                "nm_mhs": "SUHAEFI FAUZIAN",
                "dosen_penguji1": "RENA WIJAWA, S.KOM",
                "dosen_penguji2": "M. RIZKY PRATAMA, S.KOM",
                "tgl_sidang": "2024-08-25",
                "created_at": "2024-05-27 20:40:55"
            }
        ]
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Get Detail Antrian Sidang</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/sidang/detail/{sidang_id}</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Ganti nilai <b>{sidang_id}</b> dengan nilai sidang_id yang ada di list antrian sidang. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "antrian": {
            "sidang_id": 1,
            "dosen_id": 2,
            "kd_dosen": "MN",
            "nm_dosen": "MINA ISMU RAHAYU, M.T",
            "nim": "1220001",
            "nm_mhs": "SUHAEFI FAUZIAN",
            "dosen_penguji1": "RENA WIJAWA, S.KOM",
            "dosen_penguji2": "M. RIZKY PRATAMA, S.KOM",
            "tgl_sidang": "2024-08-25",
            "created_at": "2024-05-27 20:40:55"
        }
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Add Antrian Sidang</h5>
    <p>
        Sertakan payload dalam bentuk JSON seperti di bawah ini, kemudian kirimkan ke <span class="badge bg-dark">/antrian/sidang/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>. Contoh payload:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nim": "1220001",
    "nm_mhs": "Suhaefi Fauzian",
    "dosen_pembimbing": "Mina Ismu Rahayu, M.T",
    "dosen_penguji1": "Rena Wijawa, S.Kom",
    "dosen_penguji2": "M. Rizky Pratama, S.Kom",
    "tgl_sidang": "25-08-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Antrian Sidang</h5>
    <p>
        Sertakan payload seperti di bawah ini dan kirimkan permintaan ke <span class="badge bg-dark">/antrian/sidang/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span>. Contoh payload:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "sidang_id": 1,
    "nim": "1220001",
    "nm_mhs": "Suhaefi Fauzian - EDIT",
    "dosen_pembimbing": "Mina Ismu Rahayu, M.T",
    "dosen_penguji1": "Rena Wijawa, S.Kom",
    "dosen_penguji2": "M. Rizky Pratama, S.Kom",
    "tgl_sidang": "25-10-2024"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Delete Antrian Sidang</h5>
    <p>
        Untuk menghapus antrian sidang dari list, kirimkan permintaan ke <span class="badge bg-dark">/antrian/sidang/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai sidang_id dalam payload seperti berikut ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "sidang_id": 1
}</code></pre>
</section>