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
                "tamu_id": 2,
                "nama": "Suhaefi",
                "alamat": "KP. Jadaria",
                "pihak_tujuan": "Rena Wijaya, S.Kom",
                "keperluan": "Mabar Valorant",
                "tgl": "2024-05-27",
                "created_at": "2024-05-27 18:58:08"
            }
        ]
    }
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Get Detail Antrian Tamu</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/detail/{tamu_id}</span>, ganti nilai <b>tamu_id</b> dengan nilai tamu_id yang ada di get list antrian dan kirimkan menggunakan HTTP method <span class="badge bg-info">get</span>. Contohnya <span class="badge bg-dark">/antrian/tamu/detail/2</span>, hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "antrian": {
            "tamu_id": 2,
            "nama": "Suhaefi",
            "alamat": "KP. Jadaria",
            "pihak_tujuan": "Rena Wijaya, S.Kom",
            "keperluan": "Mabar Valorant",
            "tgl": "2024-05-27",
            "created_at": "2024-05-27 18:58:08"
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
    "pihak_tujuan": "Rena Wijaya, S.Kom",
    "tgl": "27-05-2024",
    "keperluan": "Mabar Valorant"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Update Data Antrian Tamu</h5>
    <p>
        Untuk mengedit data antrian tamu, kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tamu_id": 2,
    "nama": "Yoga Pratama",
    "alamat": "Ujung Berung",
    "pihak_tujuan": "Rena Wijaya, S.Kom",
    "tgl": "27-05-2024",
    "keperluan": "Mabar Valorant"
}</code></pre>
</section>
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Delete Antrian Tamu</h5>
    <p>
        Untk menghapus antrian tamu tertentu yang ada pada list antrian, kirimkan permintaan ke <span class="badge bg-dark">/antrian/tamu/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tamu_id": 2
}</code></pre>
</section>