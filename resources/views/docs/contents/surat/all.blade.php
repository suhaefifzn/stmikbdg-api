<section>
    <div class="alert alert-warning">
        <b>Note: </b> seluruh endpoints yang ada pada halaman ini dapat digunakan oleh semua role yang telah disediakan untuk sistem surat masuk dan keluar.
    </div>
</section>

<section>
    <h5 class="mt-4 mb-3 fw-bold">(ALL) Get Statistik Surat dan Pengguna Sistem</h5>
    <p>
        Untuk melihat jumlah surat masuk, surat keluar, disposisi, dan jumlah pengguna sistem, kirimkan permintaan ke <span class="badge bg-dark">/surat/statistik</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "statistik": {
            "total_surat_masuk": 2,
            "total_surat_keluar": 4,
            "total_disposisi": 1,
            "total_pengguna": 4
        }
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ALL) Get List Lokasi Arsip Disimpan</h5>
    <p>
        Digunakan untuk melihat list lokasi penyimpanan arsip yang tersedia. Kirimkan permintaan ke <span class="badge bg-dark">/surat/arsip/lokasi</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_lokasi_arsip": [
            {
                "arsip_lokasi_id": 1,
                "kd_lokasi": "rak1",
                "ket_lokasi": "Rak 1"
            },
            {
                "arsip_lokasi_id": 2,
                "kd_lokasi": "rak2",
                "ket_lokasi": "Rak 2"
            },
            {
                "arsip_lokasi_id": 3,
                "kd_lokasi": "rak3",
                "ket_lokasi": "Rak 3"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ALL) Surat Keluar - Get Nomor Agenda</h5>
    <p>
        Digunakan untuk generate nomor agenda paling baru, artinya jika surat keluar terakhir memiliki nomor agenda '001' maka API ini akan memberikan '002'. Kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/generate/nomor-agenda</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "nmr_agenda": "005"
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ALL) Surat Keluar - Get Nomor Surat Surat</h5>
    <p>
        Digunakan untuk generate nomor surat secara acak. Kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/generate/nomor-surat</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "nmr_surat": "NHRE-225/YXQG/823-WXK"
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ALL) Surat Masuk - Get Catatan Arsip</h5>
    <p>
        Digunakan untuk melihat pilihan catatan untuk pengarsipan surat. Kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/arsip/catatan</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_catatan_arsip": [
            {
                "arsip_catatan_id": 1,
                "kd_catatan": "Dipahami",
                "ket_catatan": "Pastikan semua instruksi di atas dipahami dan dilaksanakan dengan baik."
            },
            {
                "arsip_catatan_id": 2,
                "kd_catatan": "Arsip",
                "ket_catatan": "Simpan salinan seluruh dokumen dan laporan untuk arsip."
            },
            {
                "arsip_catatan_id": 3,
                "kd_catatan": "Koordinasikan",
                "ket_catatan": "Segera koordinasikan dengan bagian keuangan untuk ketersediaan anggaran."
            },
            {
                "arsip_catatan_id": 4,
                "kd_catatan": "Evaluasi",
                "ket_catatan": "Evaluasi segera kelayakan permohonan sesuai kebijakan perusahaan."
            }
        ]
    }
}</code></pre>
</section>
