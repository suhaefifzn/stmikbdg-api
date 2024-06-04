<section>
    <h5 class="mt-4 mb-3 fw-bold">(ADM) Get List Antrian Tamu</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Response yang diberikan seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_antrian": [
            {
                "tamu_id": 8,
                "nama": "SUHAEFI",
                "alamat": "KP. Jadaria",
                "pihak_tujuan": "MINA ISMU RAHAYU, M.T",
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "keperluan": "Urusan akademik",
                "tgl": "2024-06-24",
                "is_sudah": false,
                "created_at": "2024-06-04 19:49:51"
            },
            {
                "tamu_id": 7,
                "nama": "YOGA PRATAMA",
                "alamat": "Ujung Berung",
                "pihak_tujuan": "MINA ISMU RAHAYU, M.T",
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "keperluan": "Perwalian",
                "tgl": "2024-06-10",
                "is_sudah": true,
                "created_at": "2024-06-04 19:48:59"
            }
        ]
    }
}</code></pre>
    <div class="alert alert-warning">
        <p>
            Anda juga dapat menggunakan dua query parameter yang telah disediakan untuk memfilter antrian berdasarkan nilai <b>is_sudah</b> dan nilai <b>kd_dosen</b>. Berikut adalah beberapa contoh penggunaan dari query parameter yang telah disediakan:
        </p>
        <ul>
            <li><span class="badge bg-dark">/antrian/tamu/list?is_sudah=true&kd_dosen=IF054</span></li>
            <li><span class="badge bg-dark">/antrian/tamu/list?is_sudah=false</span></li>
            <li><span class="badge bg-dark">/antrian/tamu/list?kd_dosen=IF054</span></li>
        </ul>
    </div>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Get Detail Antrian Tamu</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/detail/{tamu_id}</span>, ganti nilai <b>tamu_id</b> dengan nilai tamu_id yang ada di get list antrian dan kirimkan menggunakan HTTP method <span class="badge bg-info">get</span>. Contohnya <span class="badge bg-dark">/antrian/tamu/detail/8</span>, hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "antrian": {
            "tamu_id": 8,
            "nama": "SUHAEFI",
            "alamat": "KP. Jadaria",
            "pihak_tujuan": "MINA ISMU RAHAYU, M.T",
            "dosen_id": 2,
            "kd_dosen": "IF054",
            "keperluan": "Urusan akademik",
            "tgl": "2024-06-24",
            "is_sudah": false,
            "created_at": "2024-06-04 19:49:51"
        }
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Add Antrian Tamu</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-primary">/antrian/tamu/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload dalam bentuk JSON dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nama": "Suhaefi",
    "alamat": "KP. Jadaria",
    "pihak_tujuan": "Mina Ismu Rahayu, M.T",
    "kd_dosen": "IF054",
    "tgl": "24-06-2024",
    "keperluan": "Urusan akademik"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Antrian Tamu</h5>
    <p>
        Untuk mengedit data antrian tamu, kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tamu_id": 7,
    "nama": "Yoga Pratama",
    "alamat": "Ujung Berung",
    "pihak_tujuan": "Mina Ismu Rahayu, M.T",
    "kd_dosen": "IF054",
    "tgl": "10-06-2024",
    "keperluan": "Perwalian"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Delete Antrian Tamu</h5>
    <p>
        Untk menghapus antrian tamu tertentu yang ada pada list antrian, kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tamu_id": 7
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(DSN) Get List Antrian Tamu</h5>
    <p>
        Digunakan untuk get list antrian tamu oleh dosen, kirimkan permintaan ke <span class="badge bg-dark">/antrian/list/tamu</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. API akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_antrian": [
            {
                "tamu_id": 8,
                "nama": "SUHAEFI",
                "alamat": "KP. Jadaria",
                "pihak_tujuan": "MINA ISMU RAHAYU, M.T",
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "keperluan": "Urusan akademik",
                "tgl": "2024-06-24",
                "is_sudah": false,
                "created_at": "2024-06-04 19:49:51"
            },
            {
                "tamu_id": 7,
                "nama": "YOGA PRATAMA",
                "alamat": "Ujung Berung",
                "pihak_tujuan": "MINA ISMU RAHAYU, M.T",
                "dosen_id": 2,
                "kd_dosen": "IF054",
                "keperluan": "Perwalian",
                "tgl": "2024-06-10",
                "is_sudah": true,
                "created_at": "2024-06-04 19:48:59"
            }
        ]
    }
}</code></pre>
    <div class="alert alert-warning">
        <p>
            Anda juga dapat menggunakan dua query parameter yang telah disediakan untuk memfilter antrian berdasarkan nilai <b>is_sudah</b>. Berikut adalah contoh penggunaan dari query parameter yang telah disediakan:
        </p>
        <ul>
            <li><span class="badge bg-dark">/antrian/list/tamu?is_sudah=false</span></li>
        </ul>
    </div>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(DSN) Update Status Antrian Tamu</h5>
    <p>
        Digunakan untuk mengubah status antrian tamu. Kirimkan permintaan ke <span class="badge bg-dark">/antrian/list/tamu</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tamu_id": 8,
    "is_sudah": true
}</code></pre>
</section>