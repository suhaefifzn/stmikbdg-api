{{-- * Surat Keluar --}}
<section>
    <h5 class="mt-4 mb-3 fw-bold">(STF) Surat Keluar - Tambah atau Kirim Surat</h5>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan data sebagai payload dalam bentuk JSON seperti contoh di bawah ini, kemudian kirimkan ke <span class="badge bg-dark">/surat/keluar/add</span>. Contoh:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tgl_keluar": "20-06-2024",
    "tgl_sk": "20-06-2024",
    "nmr_agenda": "090",
    "kode_sk": "283",
    "nmr_sk": "AHYO/O75T/98JH",
    "penerima_sk": "DESA PANAS",
    "perihal_sk": "Undangan Rapat Karyawan",
    "lampiran_sk": "2 Lampiran",
    "tindakan": "KIRIM BERKAS",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Keluar - Get List Surat</h5>
    <p>
        Untuk melihat list surat keluar milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="languagejson bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_surat_keluar": [
            {
                "surat_keluar_id": 17,
                "nmr_agenda": "090",
                "kode_sk": "283",
                "nmr_sk": "AHYO/O75T/98JH",
                "tgl_keluar": "2024-06-20",
                "tgl_sk": "2024-06-20",
                "penerima_sk": "DESA PANAS",
                "perihal_sk": "Undangan Rapat Karyawan",
                "lampiran_sk": "2 Lampiran",
                "status_id": 2,
                "tindakan": "KIRIM BERKAS",
                "disposisi_user_id": 109,
                "disposisi_nm_user": "Karyawan",
                "ajukan_ke_user_id": null,
                "nama_file": "file_surat.pdf",
                "berkas_kesalahan": "file.pdf",
                "status_arsip": 0,
                "status": {
                    "status_id": 2,
                    "kd_status": 1,
                    "ket_status": "Proses Pengecekan"
                }
            },
            ...
        ]
    }
}</code></pre>
    <p>
        Untuk melihat detail dari salah satu surat keluar yang ada dalam list, kirimkan permintaan ke endpoint yang sama dan tambahkan query <span class="badge bg-secondary">surat_keluar_id</span>. Contohnya adalah <span class="badge bg-dark">/surat/keluar/list?surat_keluar_id=17</span>.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Keluar - Update Surat Milik Sendiri</h5>
    <p>
        Untuk memperbarui data surat keluar milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 17,
    "tgl_keluar": "30-06-2024",
    "tgl_sk": "30-06-2024",
    "nmr_agenda": "004",
    "kode_sk": "221",
    "nmr_sk": "HGKF-689/JYLC/221-KDB",
    "penerima_sk": "DESA KEPANASAN",
    "perihal_sk": "Undangan Rapat Diedit Lagi Lagi 2",
    "lampiran_sk": "1 Lampiran",
    "tindakan": "KIRIM BERKAS DIEDIT",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Keluar - Tambahkan ke Arsip (Surat Milik Sendiri)</h5>
    <p>
        Untuk menambahkan surat ke arsip, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/arsip</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>. Dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 17,
    "kd_lokasi": "rak2",
    "lokasi_arsip": "Rak 2"
}</code></pre>
    <p>
        Mengenai nilai <b>kd_lokasi</b> dan <b>lokasi_arsip</b> dapat dicari pada tab menu All.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Masuk - Get List Surat</h5>
    <p>
        Untuk melihat list surat masuk milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="languagejson bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_surat_masuk": [
            {
                "surat_masuk_id": 20,
                "nmr_agenda": "001",
                "kode_sm": "100",
                "nmr_sm": "AKI31/1SG54/SAK13",
                "tgl_surat": "2024-06-20",
                "tgl_sm": "2024-06-20",
                "kategori_id": 2,
                "pengirim": "STMIK Bandung",
                "perihal_surat": "Test",
                "lampiran": "2 Lampiran",
                "status_id": 3,
                "status_baca": 1,
                "tindakan": "SIP LAH",
                "disposisi_ke_user_id": 109,
                "disposisi_ke_nm_user": "Karyawan",
                "ajukan_ke_user_id": 109,
                "nama_file": "file.pdf",
                "status": {
                    "status_id": 3,
                    "kd_status": 3,
                    "ket_status": "Selesai"
                },
                "kategori": {
                    "kategori_id": 2,
                    "nama": "Surat Pribadi"
                }
            }
        ]
    }
}</code></pre>
    <p>
        Untuk melihat detail dari salah satu surat masuk yang ada dalam list, kirimkan permintaan ke endpoint yang sama dan tambahkan query <span class="badge bg-secondary">surat_masuk_id</span>. Contohnya adalah <span class="badge bg-dark">/surat/masuk/list?surat_masuk_id=20</span>.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Masuk - Terima Surat</h5>
    <p>
        Untuk memverifikasi bahwa surat telah diterima, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/terima</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan nilai <b>surat_masuk_id</b> yang diterima dalam payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_masuk_id": 20
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(STF) Surat Masuk - Tambahkan ke Arsip (Surat Milik Sendiri)</h5>
    <p>
        Untuk memindahkan surat ke arsip, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/arsip</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language- json bg-primary-subtle">{
    "surat_masuk_id": 20,
    "kd_lokasi": "rak1",
    "lokasi_arsip": "Rak 1"
}</code></pre>
</section>
