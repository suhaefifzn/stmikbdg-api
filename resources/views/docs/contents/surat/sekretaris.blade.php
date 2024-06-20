{{-- * Surat Keluar --}}
<section>
    <h5 class="mt-4 mb-3 fw-bold">(SCT) Surat Keluar - Tambah atau Kirim Surat</h5>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan data sebagai payload dalam bentuk JSON seperti contoh di bawah ini, kemudian kirimkan ke <span class="badge bg-dark">/surat/keluar/add</span>. Contoh:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tgl_keluar": "20-06-2024",
    "tgl_sk": "20-06-2024",
    "nmr_agenda": "010",
    "kode_sk": "253",
    "nmr_sk": "SPAU-577/BWOO/6ASD",
    "penerima_sk": "DESA DESAAN",
    "perihal_sk": "Undangan Dari Sekretaris",
    "lampiran_sk": "2 Lampiran",
    "tindakan": "KIRIM BERKAS DARI SEKRETARIS",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Keluar - Get List Surat</h5>
    <p>
        Untuk melihat list surat keluar milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="languagejson bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_surat_keluar": [
            {
                "surat_keluar_id": 14,
                "nmr_agenda": "099",
                "kode_sk": "523",
                "nmr_sk": "FSAF-571/BWTO/651-ABU",
                "tgl_keluar": "2024-06-20",
                "tgl_sk": "2024-06-20",
                "penerima_sk": "DESA C",
                "perihal_sk": "Undangan F",
                "lampiran_sk": "1 Lampiran",
                "status_id": 2,
                "tindakan": "KIRIM BERKAS DARI ADMIN",
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
        Untuk melihat detail dari salah satu surat keluar yang ada dalam list, kirimkan permintaan ke endpoint yang sama dan tambahkan query <span class="badge bg-secondary">surat_keluar_id</span>. Contohnya adalah <span class="badge bg-dark">/surat/keluar/list?surat_keluar_id=14</span>.
    </p>
    <p>
        Jika ingin mendapatkan list surat keluar untuk diperiksa gunakan query <span class="badge bg-secondary">is_periksa</span> dengan nilai <b>true</b>, misalnya <span class="badge bg-dark">/surat/keluar/list?is_periksa=true</span>. Kemudian, jika ingin melihat detail dari surat yang akan diperiksa tambahkan lagi query <span class="badge bg-secondary">surat_keluar_id</span>, sehingga untuk melihat detail dari surat yang akan diperika seperti <span class="badge bg-dark">/surat/keluar/list?is_periksa=true&surat_keluar_id=15</span>.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Keluar - Get List Status</h5>
    <p>
        Untuk melihat status surat keluar yang dapat digunakan untuk menindak surat tersebut, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/status/update-by-sekretaris</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_status": [
            {
                "status_id": 3,
                "kd_status": 2,
                "ket_status": "Berkas Dicek Wakil Ketua"
            },
            {
                "status_id": 5,
                "kd_status": 4,
                "ket_status": "Perbaiki"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Keluar - Update Status atau Menindak Surat</h5>
    <p>
        Untuk memperbarui status dari surat atau menindak surat tersebut, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/status/update-by-sekretaris</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti di bawah ini.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 12,
    "status_id": 5,
    "tindakan": "Perbaiki lagi",
    "ajukan_ke_user_id": 100
}</code></pre>
    <p>
        Jika nilai <b>status_id</b> yang dipilih dari list status adalah 3 maka nilai <b>tindakan</b> dan nilai <b>ajukan_ke_user_id</b> wajib diisi. Jika <b>status_id</b> adalah 5, maka hanya nilai <b>tindakan</b> yang wajib diisi.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Keluar - Update Surat Milik Sendiri</h5>
    <p>
        Untuk memperbarui data surat keluar milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 15,
    "tgl_keluar": "21-06-2024",
    "tgl_sk": "21-06-2024",
    "nmr_agenda": "029",
    "kode_sk": "251",
    "nmr_sk": "HGAS/JY89C/251-KDC",
    "penerima_sk": "DESA CIBIRU TES",
    "perihal_sk": "Undangan Rapat Diedit Lagi Lagi Secretary",
    "lampiran_sk": "1 Lampiran",
    "tindakan": "KIRIM BERKAS DIEDIT",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Keluar - Tambahkan ke Arsip (Surat Milik Sendiri)</h5>
    <p>
        Untuk menambahkan surat ke arsip, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/arsip</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>. Dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 10,
    "kd_lokasi": "rak2",
    "lokasi_arsip": "Rak 2"
}</code></pre>
    <p>
        Mengenai nilai <b>kd_lokasi</b> dan <b>lokasi_arsip</b> dapat dicari pada tab menu All.
    </p>
</section>

{{-- * Surat Masuk --}}
<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Masuk - Get List Surat</h5>
    <p>
        Untuk melihat list surat masuk milik sendiri, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="languagejson bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_surat_masuk": [
            {
                "surat_masuk_id": 6,
                "nmr_agenda": "020",
                "kode_sm": "100",
                "nmr_sm": "XYZ/123/102-B",
                "tgl_surat": "2024-06-18",
                "tgl_sm": "2024-06-18",
                "kategori_id": 2,
                "pengirim": "STMIK Bandung",
                "perihal_surat": "Test",
                "lampiran": "2 Lampiran",
                "status_id": 3,
                "status_baca": 0,
                "tindakan": "Dilihat",
                "disposisi_ke_user_id": 108,
                "disposisi_ke_nm_user": "Eva Diah Novita Sari",
                "ajukan_ke_user_id": 100,
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
        Untuk melihat detail dari salah satu surat masuk yang ada dalam list, kirimkan permintaan ke endpoint yang sama dan tambahkan query <span class="badge bg-secondary">surat_masuk_id</span>. Contohnya adalah <span class="badge bg-dark">/surat/masuk/list?surat_masuk_id=6</span>.
    </p>
    <p>
        Jika ingin mendapatkan list surat masuk untuk diperiksa gunakan query <span class="badge bg-secondary">is_periksa</span> dengan nilai <b>true</b>, misalnya <span class="badge bg-dark">/surat/masuk/list?is_periksa=true</span>. Kemudian, jika ingin melihat detail dari surat yang akan diperiksa tambahkan lagi query <span class="badge bg-secondary">surat_masuk_id</span>, sehingga untuk melihat detail dari surat yang akan diperika seperti <span class="badge bg-dark">/surat/masuk/list?is_periksa=true&surat_masuk_id=7</span>.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Masuk - Get List Wakil Ketua</h5>
    <p>
        Untuk mendapatkan daftar wakil ketua yang digunakan untuk target pengajuan, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/staff/wk?target=is_wk</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_staff": [
            {
                "staff_id": 8,
                "user_id": 100,
                "nama": "Dani Pradana, M.T",
                "jabatan": "Wakil Ketua"
            },
            ...
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Masuk - Update Status atau Ajukan Surat</h5>
    <p>
        Untuk mengajukan surat ke wakil ketua, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/ajukan</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_masuk_id": 6,
    "ajukan_ke_user_id": 100
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Masuk - Tambahkan ke Arsip (Surat Milik Sendiri)</h5>
    <p>
        Untuk memindahkan surat ke arsip, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/arsip</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language- json bg-primary-subtle">{
    "surat_masuk_id": 6,
    "kd_lokasi": "rak1",
    "lokasi_arsip": "Rak 1"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(SCT) Surat Masuk - History</h5>
    <p>
        Untuk melihat history atau riwayat surat masuk yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/riwayat</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
        "status": "success",
        "data": {
        "riwayat_surat": [
            {
                "surat_masuk_id": 7,
                "nmr_agenda": "045",
                "kode_sm": "110",
                "nmr_sm": "ABC/123/102-B",
                "tgl_surat": "2024-06-20",
                "tgl_sm": "2024-06-20",
                "kategori_id": 2,
                "pengirim": "STMIK Bandung",
                "perihal_surat": "Test oleh Admin Diedit",
                "lampiran": "1 Lampiran",
                "status_id": 1,
                "status_baca": 0,
                "tindakan": "CEK DIEDIT",
                "disposisi_ke_user_id": null,
                "disposisi_ke_nm_user": null,
                "ajukan_ke_user_id": null,
                "nama_file": "file.pdf",
                "disposisi": null,
                "status": {
                    "status_id": 1,
                    "kd_status": 1,
                    "ket_status": "Diproses"
                }
            },
            ...
        ]
    }
}</code></pre>
</section>
