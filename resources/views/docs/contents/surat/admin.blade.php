<section>
    <h5 class="mt-4 mb-3 fw-bold">(ADM) Get Arsip Surat Masuk dan Keluar</h5>
    <p>
        Digunakan untuk melihat list surat masuk dan surat keluar yang telah diarsipkan. Kirimkan permintaan ke <span class="badge bg-dark">/surat/arsip</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_arsip": {
            "surat_masuk": [
                {
                    "arsip_id": 5,
                    "surat_masuk_id": 6,
                    "surat_keluar_id": null,
                    "tgl_arsip": "2024-06-19",
                    "kd_lokasi": "rak1",
                    "lokasi_arsip": "Rak 1",
                    "surat_masuk": {
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
                        "kategori": {
                            "kategori_id": 2,
                            "nama": "Surat Pribadi"
                        },
                        "status": {
                            "status_id": 3,
                            "kd_status": 3,
                            "ket_status": "Selesai"
                        },
                        "disposisi": {
                            "disposisi_id": 3,
                            "surat_masuk_id": 6,
                            "tujuan_disposisi": "Marketing",
                            "catatan": "Dipahami<br>Koordinasikan",
                            "status_disposisi": 1
                        }
                    }
                }
            ],
            "surat_keluar": [
                {
                    "arsip_id": 4,
                    "surat_masuk_id": null,
                    "surat_keluar_id": 10,
                    "tgl_arsip": "2024-06-19",
                    "kd_lokasi": "rak2",
                    "lokasi_arsip": "Rak 2",
                    "surat_keluar": {
                        "surat_keluar_id": 10,
                        "nmr_agenda": "004",
                        "kode_sk": "221",
                        "nmr_sk": "HGKF-689/JYLC/221-KDB",
                        "tgl_keluar": "2024-06-30",
                        "tgl_sk": "2024-06-30",
                        "penerima_sk": "DESA CIBIRU WETAN",
                        "perihal_sk": "Undangan Rapat Diedit Lagi Lagi 2",
                        "lampiran_sk": "1 Lampiran",
                        "status_id": 4,
                        "tindakan": "Siap kirim lah",
                        "disposisi_user_id": 109,
                        "disposisi_nm_user": "Karyawan",
                        "ajukan_ke_user_id": 100,
                        "nama_file": "file_surat.pdf",
                        "berkas_kesalahan": null,
                        "status_arsip": 1,
                        "status": {
                            "status_id": 4,
                            "kd_status": 3,
                            "ket_status": "Berkas Siap Kirim"
                        }
                    }
                }
            ]
        }
    }
}</code></pre>
</section>

{{-- * Kategori --}}
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Kategori - Get List</h5>
    <p>
        Untuk melihat kategori yang telah ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/kategori/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kategori": [
            {
                "kategori_id": 2,
                "nama": "Surat Pribadi"
            },
            {
                "kategori_id": 1,
                "nama": "Surat Resmi"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Kategori - Add Kategori</h5>
    <p>
        Untuk menambahkan kategori surat, kirimkan permintaan ke <span class="badge bg-dark">/surat/kategori/add</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Kirimkan juga payload dalam bentuk JSON yang berisi nama kategorinya seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nama": "Surat Bisnis"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Kategori - Update atau Edit</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/surat/kategori/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan kirimkan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kategori_id": 4,
    "nama": "Surat Undangan"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Kategori - Hapus</h5>
    <p>
        Untuk menghapus kategori yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/kategori/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload yang berisi id dari kategori yang akan dihapus, seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kategori_id": 4
}</code></pre>
</section>

{{-- * Surat Keluar --}}
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Get List Surat</h5>
    <p>
        Untuk melihat list surat keluar dari yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
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
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Update atau Edit</h5>
    <p>
        Untuk mengubah data surat keluar yang sudah ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan data sebagai payload dalam bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 14,
    "tgl_keluar": "20-06-2024",
    "tgl_sk": "20-06-2024",
    "nmr_agenda": "099",
    "kode_sk": "523",
    "nmr_sk": "FSAF-571/BWTO/651-ABU",
    "penerima_sk": "DESA C",
    "perihal_sk": "Undangan F Diedit",
    "lampiran_sk": "2 Lampiran",
    "tindakan": "KIRIM BERKAS DARI ADMIN DIEDIT",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf",
    "disposisi_user_id": 109
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Hapus</h5>
    <p>
        Untuk menghapus surat keluar yang ada pada list, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai surat keluar id sebagai payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_keluar_id": 14
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Tambah atau Kirim Surat</h5>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan data sebagai payload dalam bentuk JSON seperti contoh di bawah ini, kemudian kirimkan ke <span class="badge bg-dark">/surat/keluar/add</span>. Contoh:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tgl_keluar": "20-06-2024",
    "tgl_sk": "20-06-2024",
    "nmr_agenda": "099",
    "kode_sk": "523",
    "nmr_sk": "FSAF-571/BWTO/651-ABU",
    "penerima_sk": "DESA C",
    "perihal_sk": "Undangan F",
    "lampiran_sk": "1 Lampiran",
    "tindakan": "KIRIM BERKAS DARI ADMIN",
    "berkas_kesalahan": "file.pdf",
    "nama_file": "file_surat.pdf",
    "disposisi_user_id": 109
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Get List Staff atau Target Disposisi</h5>
    <p>
        Untuk melihat daftar staff yang ada, atau target disposisi surat. Kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/staff/list?target=all</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
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
            {
                "staff_id": 3,
                "user_id": 109,
                "nama": "Karyawan",
                "jabatan": "Akademik"
            },
            {
                "staff_id": 2,
                "user_id": 108,
                "nama": "Eva Diah Novitasari",
                "jabatan": "Marketing"
            },
            {
                "staff_id": 1,
                "user_id": 97,
                "nama": "Rizky Septian",
                "jabatan": "Marketing"
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Rekap atau List Laporan</h5>
    <p>
        Untuk merekap atau melihat list surat keluar berdasarkan waktu dan status tertentu, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/rekap?date=18-06-2024&status_id=2</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> dan sesuai nilai pada query <span class="badge bg-secondary">date</span> dan <span class="badge bg-secondary">status_id</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tgl_sk": "20-06-2024",
        "rekap_surat_keluar": [
            {
                "surat_keluar_id": 14,
                "nmr_agenda": "099",
                "kode_sk": "523",
                "nmr_sk": "FSAF-571/BWTO/651-ABU",
                "tgl_keluar": "2024-06-20",
                "tgl_sk": "2024-06-20",
                "penerima_sk": "DESA C",
                "perihal_sk": "Undangan F Diedit",
                "lampiran_sk": "2 Lampiran",
                "status_id": 2,
                "tindakan": "KIRIM BERKAS DARI ADMIN DIEDIT",
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
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Keluar - Get List Status</h5>
    <p>
        Untuk melihat daftar status masuk yang tersedia, kirimkan permintaan ke <span class="badge bg-dark">/surat/keluar/status/list-by-admin</span> dengan menggunakan HTTP method <span class="badge bg-info">ge</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_status": [
            {
                "status_id": 1,
                "kd_status": 0,
                "ket_status": "Cek Ulang"
            },
            {
                "status_id": 2,
                "kd_status": 1,
                "ket_status": "Proses Pengecekan"
            },
            {
                "status_id": 4,
                "kd_status": 3,
                "ket_status": "Berkas Siap Kirim"
            },
            {
                "status_id": 5,
                "kd_status": 4,
                "ket_status": "Perbaiki"
            },
            {
                "status_id": 3,
                "kd_status": 2,
                "ket_status": "Berkas Dicek Wakil Ketua"
            }
        ]
    }
}</code></pre>
</section>

{{-- * Surat Masuk --}}
<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Get List Surat yang Telah Didisposisikan</h5>
    <p>
        Digunakan untuk melihat list surat masuk yang sudah didisposisikan. Kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/disposisi/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_disposisi": [
            {
                "disposisi_id": 3,
                "surat_masuk_id": 6,
                "tujuan_disposisi": "Marketing",
                "catatan": "Dipahami&lt;br&gt;Koordinasikan",
                "status_disposisi": 1,
                "surat_masuk": {
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
                    "kategori": {
                        "kategori_id": 2,
                        "nama": "Surat Pribadi"
                    },
                    "status": {
                        "status_id": 3,
                        "kd_status": 3,
                        "ket_status": "Selesai"
                    },
                    "arsip": {
                        "arsip_id": 5,
                        "surat_masuk_id": 6,
                        "surat_keluar_id": null,
                        "tgl_arsip": "2024-06-19",
                        "kd_lokasi": "rak1",
                        "lokasi_arsip": "Rak 1"
                    }
                }
            }
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Get Nomor Agenda</h5>
    <p>
        Digunakan untuk mendapat nomor agenda terbaru untuk surat masuk. Kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/generate/nomor-agenda</span> dengan menggunakan HTPT method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "nmr_agenda": "021"
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Tambah atau Kirim Surat</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nmr_agenda": "045",
    "kode_sm": "110",
    "nmr_sm": "ABC/123/102-B",
    "tgl_surat": "20-06-2024",
    "tgl_sm": "20-06-2024",
    "kategori_id": 2,
    "pengirim": "STMIK Bandung",
    "perihal_surat": "Test oleh Admin",
    "lampiran": "2 Lampiran",
    "tindakan": "CEK",
    "nama_file": "file.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Update atau Edit</h5>
    <p>
        Untuk mengubah atau edit data pada salah satu surat masuk, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_masuk_id": 7,
    "nmr_agenda": "045",
    "kode_sm": "110",
    "nmr_sm": "ABC/123/102-B",
    "tgl_surat": "20-06-2024",
    "tgl_sm": "20-06-2024",
    "kategori_id": 2,
    "pengirim": "STMIK Bandung",
    "perihal_surat": "Test oleh Admin Diedit",
    "lampiran": "1 Lampiran",
    "tindakan": "CEK DIEDIT",
    "nama_file": "file.pdf"
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Delete Surat</h5>
    <p>
        Untuk menghapus surat masuk yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai surat masuk id dalam payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "surat_masuk_id": 7
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Get List Surat</h5>
    <p>
        Untuk melihat daftar surat masuk yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_surat_masuk": [
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
                "status": {
                    "status_id": 1,
                    "kd_status": 1,
                    "ket_status": "Diproses"
                },
                "kategori": {
                    "kategori_id": 2,
                    "nama": "Surat Pribadi"
                }
            },
            ...
        ]
    }
}</code></pre>
    <p>
        Jika ingin melihat detail dari salah satu surat masuk yang ada pada list, tambahkan query <span class="badge bg-secondary">surat_masuk_id</span>. Contohnya <span class="badge bg-dark">/surat/masuk/list?surat_masuk_id=7</span>.
    </p>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Rekap Surat</h5>
    <p>
        Untuk merekap surat berdasarkan tanggal dan status, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/rekap?date=20-06-2024&status_id=1</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> dan sesuaikan nilai query untuk <span class="badge bg-secondary">date</span> dan <span class="badge bg-secondary">status_id</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tgl_sk": "20-06-2024",
        "rekap_surat_keluar": [
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
                "nama_file": "file.pdf"
            },
            ...
        ]
    }
}</code></pre>
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(ADM) Surat Masuk - Get List Status</h5>
    <p>
        Untuk melihat status surat masuk yang ada, kirimkan permintaan ke <span class="badge bg-dark">/surat/masuk/status/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_status": [
            {
                "status_id": 1,
                "kd_status": 1,
                "ket_status": "Diproses"
            },
            {
                "status_id": 2,
                "kd_status": 2,
                "ket_status": "Diajukan"
            },
            {
                "status_id": 3,
                "kd_status": 3,
                "ket_status": "Selesai"
            }
        ]
    }
}</code></pre>
</section>
