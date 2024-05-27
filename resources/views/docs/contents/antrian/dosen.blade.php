<section>
    <h5 class="mb-3 mt-4 fw-bold">(ADM) Get List Dosen</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/dosen/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_dosen": [
            {
                "dosen_id": 2,
                "kd_dosen": "MN",
                "nm_dosen": "MINA ISMU RAHAYU, M.T",
                "no_card": "02",
                "created_at": "2024-05-27 14:19:08"
            },
            {
                "dosen_id": 1,
                "kd_dosen": "RW",
                "nm_dosen": "RENA WIJAYA, S.KOM",
                "no_card": "01",
                "created_at": "2024-05-27 14:15:24"
            }
        ]
    }
}</code></pre>
</section>
<section>
    <h5 class="mb-3 mt-5 fw-bold">(ADM) Get Detail Dosen</h5>
    <p>
        Untuk melihat satu data dosen saja, kirimkan permintaan ke <span class="badge bg-dark">/antrian/dosen/detail/{dosen_id}</span>, ganti nilai <b>{dosen_id}</b> dengan nilai dosen id yang ada saat get list dosen. Kirimkan permintaan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "dosen": {
            "dosen_id": 1,
            "kd_dosen": "RW",
            "nm_dosen": "RENA WIJAYA, S.KOM",
            "no_card": "01",
            "created_at": "2024-05-27 14:15:24"
        }
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Add Data Dosen</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/dosen/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan data dalam payload dengan format JSON, contohnya seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kd_dosen": "RW",
    "nm_dosen": "Rena Wijaya, S.Kom",
    "no_card": "01"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Dosen</h5>
    <p>
        Untuk memperbarui data dosen kirimkan permintaan ke <span class="badge bg-dark">/antrian/dosen/update</span> dengan menggunakan HTTP method <span class="badge bg-info"></span> dan sertakan data dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "dosen_id": 1,
    "kd_dosen": "RW-EDIT",
    "nm_dosen": "Rena Wijaya, S.Kom",
    "no_card" : "012"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Delete Data Dosen</h5>
    <p>
        Untuk menghapus data dosen dari API sistem antrian, kirimkan permintaan ke <span class="badge bg-dark">/antrian/dosen/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload yang berisi <b>dosen_id</b> yang akan dihapus, contohnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "dosen_id": 1
}</code></pre>
</section>