<div class="m-2">
    <p>
        Beberapa route di bawah saat ini masih dikategorikan sebagai tambahan yang mungkin dapat digunakan untuk kepentingan lain. Akan tetapi saat ini route-route tersebut hanya dapat diakses oleh user dengan role developer.
    </p>

    {{-- Route untuk get data mahasiswa--}}
    <h5 class="mt-4">(DEV) Get All Data Mahasiswa</h5>
    <hr>
    <p>
        Digunakan untuk mendapatkan semua data mahasiswa yang ada database saat ini. Lakukan request ke ke <span class="badge bg-dark">/all/mahasiswa</span>. Jika ingin mendapatkan data mahasiswa yang telah memiliki judul skripsi tambahkan query <span class="badge bg-secondary">skripsi</span> dengan nilai <b>true</b> dan atau jika ingin mendapatkan data mahasiswa berdasarkan tahun masuk, maka tambahkan query <span class="badge bg-secondary">tahun_masuk</span>.
    </p>
    <p>
        Contoh untuk mendapatkan data mahasiswa yang memiliki judul skripsi dan tahun masuknya adalah 2020, maka lakukan permintaan ke <span class="badge bg-dark">/all/mahasiswa?skripsi=true&tahun_masuk=2020</span>. Hasilnya adalah:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "mahasiswa": [
            {
                "mhs_id": 15537,
                "angkatan_id": 76,
                "nim": "1220701",
                "nirm": null,
                "nmr_daftar": null,
                "nm_mhs": "RISKA KOMALASARI",
                "alamat": "DUSUN CIRACA XXXXXXX XXXXXXXX",
                "tmp_lahir": "Subang",
                "tgl_lahir": "1996-03-01",
                "jk": "P",
                "nmr_hp": "0823xxxxxxx",
                "nmr_telp": null,
                "email": "riskaxxxxx@gmail.com",
                "dosen_id": 573,
                "jur_id": 23,
                "jns_mhs": "E",
                "sts_mhs": "A",
                "wn": "I",
                "kd_agama": "1",
                "sts_kawin": "T",
                "gol_darah": null,
                "hobi": null,
                "nmr_ktp": "32131xxxxxxxxxx",
                "k_pindahan": true,
                "masuk_semester": 1,
                "semester_last": 1,
                "test_tanggal": null,
                "test_link": null,
                "test_pwd": null,
                "test_nilai": null,
                "test_diterima": null,
                "kd_kampus": "A ",
                "kelas": "A",
                "krs_id_last": 3402,
                "tgl_daftar": "2020-05-20",
                "masuk_tahun": 2020,
                "kur_id_lulus": null,
                "no_regis": "667-GEL-2020-1-2020",
                "tgl_lulus": "2023-08-23",
                "nomor_skripsi": null,
                "judul_skripsi": "Sistem Monitoring Evaluasi Kinerja SMKN 1 Subang Menggunakan Metode Naive Bayes\r\n",
                "sts_konil": 2,
                "sks_konil": 89,
                "nomor_ijazah": "023xxxxx",
                "nomor_pin": "55201xxxxx",
                "nomor_transkrip": "023./12/STMIK-BDG/032/X/2023",
                "judul_skripsi_intl": null,
                "tgl_wisuda": "2023-10-09",
                "dosen_id_pembimbing": null,
                "predikat": null
            },
            // mahasiswa lain disembunyikan
        ]
    }
}</code></pre>
    <p>
        Kemudian jika ingin data mahasiswa tersebut diekspor ke file excel paka tambahkan query <span class="badge bg-secondary">download</span> dengan nilai <b>true</b>. Sehingga secara keseluruhan menjadi <span class="badge bg-dark">/all/mahasiswa?skripsi=true&tahun_masuk=2020&download=true</span>.
    </p>
</div>
