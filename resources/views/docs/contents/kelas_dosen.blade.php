@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kelas Kuliah - Sisi Dosen</h4>
<hr>
<p>
    Route-route API pada halaman ini digunakan untuk mendapatkan jadwal kuliah untuk dosen, daftar kelas kuliah yang ditampilkan berdasarkan semua tahun ajan aktif. Selain melihat jadwal kuliah, digunakan juga untuk membuka kelas yang menghasilkan kode berupa PIN sebanyak 6 digit yang nantinya digunakan oleh mahasiswa untuk mengisi kehadiran.
</p>
<div class="m-2">
    <h5 class="mt-4">(DSN) Get Daftar Kelas Kuliah</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/kelas-kuliah/dosen</span> menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil maka akan memberikan response dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kelas_kuliah": {
            "Rabu": [
                {
                    "kelas_kuliah_id": 3349,
                    "tahun_id": 351,
                    "jur_id": 27,
                    "mk_id": 62,
                    "join_kelas_kuliah_id": null,
                    "kjoin_kelas": false,
                    "kelas_kuliah": "A",
                    "jns_mhs": "K",
                    "sts_kelas": "B",
                    "pengajar_id": 573,
                    "join_jur": null,
                    "jadwal": {
                        "kelas_kuliah_id": 3349,
                        "mk_id": 62,
                        "dosen_id": 573,
                        "tanggal": "2024-03-13",
                        "jns_pert": "T1",
                        "jam": "19:45-21:15",
                        "kd_ruang": "",
                        "nm_hari": "Rabu",
                        "tanggal_lokal": "13 Maret 2024"
                    },
                    "dosen": {
                        "dosen_id": 573,
                        "kd_dosen": "IF054",
                        "nm_dosen": "Mina Ismu Rahayu, M.T",
                        "gelar": "M.T"
                    },
                    "kelas_dibuka": false,
                    "matakuliah": {
                        "mk_id": 62,
                        "kur_id": 82,
                        "kd_mk": "KU1813",
                        "nm_mk": "HAKI &Etika Profesi",
                        "semester": 8,
                        "sks": 2,
                        "sts_mk": "A",
                        "smt": 2,
                        "kd_kur": "MIS120"
                    }
                },
                {
                    "kelas_kuliah_id": 3341,
                    "tahun_id": 351,
                    "jur_id": 27,
                    "mk_id": 47,
                    "join_kelas_kuliah_id": null,
                    "kjoin_kelas": false,
                    "kelas_kuliah": "A",
                    "jns_mhs": "K",
                    "sts_kelas": "B",
                    "pengajar_id": 573,
                    "join_jur": null,
                    "jadwal": {
                        "kelas_kuliah_id": 3341,
                        "mk_id": 47,
                        "dosen_id": 573,
                        "tanggal": "2024-03-13",
                        "jns_pert": "T1",
                        "jam": "18:15-19:45",
                        "kd_ruang": "",
                        "nm_hari": "Rabu",
                        "tanggal_lokal": "13 Maret 2024"
                    },
                    "dosen": {
                        "dosen_id": 573,
                        "kd_dosen": "IF054",
                        "nm_dosen": "Mina Ismu Rahayu, M.T",
                        "gelar": "M.T"
                    },
                    "kelas_dibuka": false,
                    "matakuliah": {
                        "mk_id": 47,
                        "kur_id": 82,
                        "kd_mk": "KD1621",
                        "nm_mk": "Pemrograman 6 ",
                        "semester": 6,
                        "sks": 3,
                        "sts_mk": "A",
                        "smt": 2,
                        "kd_kur": "MIS120"
                    }
                }
            ]
            // jadwal lain disembunyikan
        }
    }
}</code></pre>
    <p>
        Jika terdapat properti <b>Unknown</b> menandakan bahwa kelas kuliah yang ada di dalamnya belum memiliki jadwal, hal tersebut dapat dilihat juga pada nilai <b>jadwal</b> yang masih berisi <b>null</b>.
    </p>
    <p>
        Apabila ingin menampilkan nilai <b>kd_kur</b> atau kode kurikulum maka perhatikan juga nilai <b>kjoin_kelas</b>, jika <b>kjoin_kelas</b> bernilai true maka gunakanlah nilai <b>join_jur</b>. Jika false gunakan nilai <b>kd_kur</b>.
    </p>
    <p>
        Untuk mempermudah hasil response, Anda dapat memfilter jadwal berdasarkan hari tertentu saja. Tambahkan query parameter <span class="badge bg-secondary">hari</span> dan isi dengan nama hari dalam bahasa Indonesia. Misalnya ingin mendapatkan jadwal kelas kuliah di hari Rabu saja, maka kirim request ke <span class="badge bg-dark">/kelas-kuliah/dosen?hari=rabu</span>. Maka response akan menampilkan jadwal kuliah di hari Rabu saja.
    </p>
    <p>
        Nilai <b>kelas_dibuka</b> akan berubah menjadi true jika kelas yang dipilih dibuka oleh Dosen, setelah dibuka jangan lupa untuk mengirim request untuk mendapatkan PIN. Setelah PIN didapat, maka Mahasiswa dapat mengisi presensi dengan mengirimkan PIN yang sama dengan yang didapat oleh Dosen nantinya.
    </p>
</div>
@endsection
