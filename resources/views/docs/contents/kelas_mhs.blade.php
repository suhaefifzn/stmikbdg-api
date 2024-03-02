@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kelas Kuliah - Sisi Mahasiswa</h4>
<hr>
<p>
    Route-route API pada halaman ini digunakan untuk mendapatkan jadwal kuliah untuk mahasiswa. Route jadwal kuliah dapat diakses jika status KRS telah sah/disetujui. Selain melihat jadwal kuliah, digunakan juga untuk mengisi kehadiran dengan mengirimkan PIN yang diperoleh setelah dosen yang mengampu mata kuliah membuka kelasnya.
</p>
<div class="m-2">
    <h5 class="mt-4">(MHS) Get Daftar Kelas Kuliah</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/kelas-kuliah/mahasiswa</span> menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil maka akan memberikan response dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kelas_kuliah": {
            "Selasa": [
                {
                    "kelas_kuliah_id": 3294,
                    "kd_kampus": "A",
                    "tahun_id": 348,
                    "jur_id": 23,
                    "mk_id": 137,
                    "kelas_kuliah": "A",
                    "semester": 2,
                    "jml_sks": 2,
                    "jns_mhs": "R",
                    "pengajar_id": 562,
                    "jadwal_kuliah1": "Selasa, 09:30-11:10",
                    "jadwal_kuliah2": null,
                    "jadwal_uts": null,
                    "jadwal_uas": null,
                    "nm_dosen": "Dani Pradana Kartaputra, S.Si., M.T",
                    "sts_kelas": "B",
                    "kjoin_kelas": true,
                    "join_kelas_kuliah_id": 3268,
                    "detail_matkul": {
                        "kd_mk": "KU1813",
                        "nm_mk": "KU1813",
                        "nm_jurusan": "TEKNIK INFORMATIKA",
                        "kd_kur": "IFS120"
                    },
                    "detail_jadwal": {
                        "tanggal": "2024-02-27",
                        "jns_pert": "T1",
                        "jam": "09:30-11:10",
                        "kd_ruang": "31",
                        "n_akhir": null,
                        "kjoin_kelas": true
                    }
                }
            ],
            "Jum'at": [
                {
                    "kelas_kuliah_id": 3293,
                    "kd_kampus": "A",
                    "tahun_id": 348,
                    "jur_id": 23,
                    "mk_id": 145,
                    "kelas_kuliah": "A",
                    "semester": 2,
                    "jml_sks": 3,
                    "jns_mhs": "R",
                    "pengajar_id": 708,
                    "jadwal_kuliah1": "Jum'at, 13:40-16:10",
                    "jadwal_kuliah2": null,
                    "jadwal_uts": null,
                    "jadwal_uas": null,
                    "nm_dosen": "M. RIZKI PRATAMA. S, S.Kom",
                    "sts_kelas": "B",
                    "kjoin_kelas": false,
                    "join_kelas_kuliah_id": null,
                    "detail_matkul": {
                        "kd_mk": "IF1002",
                        "nm_mk": "IF1002",
                        "nm_jurusan": "TEKNIK INFORMATIKA",
                        "kd_kur": "IFS120"
                    },
                    "detail_jadwal": {
                        "tanggal": "2024-03-01",
                        "jns_pert": "T1",
                        "jam": "13:40-16:10",
                        "kd_ruang": "11",
                        "n_akhir": null,
                        "kjoin_kelas": false
                    }
                }
            ],
            "Unknown": [
                {
                    "kelas_kuliah_id": 3295,
                    "kd_kampus": "A",
                    "tahun_id": 348,
                    "jur_id": 23,
                    "mk_id": 136,
                    "kelas_kuliah": "A",
                    "semester": 2,
                    "jml_sks": 6,
                    "jns_mhs": "R",
                    "pengajar_id": null,
                    "jadwal_kuliah1": null,
                    "jadwal_kuliah2": null,
                    "jadwal_uts": null,
                    "jadwal_uas": null,
                    "nm_dosen": "",
                    "sts_kelas": "B",
                    "kjoin_kelas": false,
                    "join_kelas_kuliah_id": null,
                    "detail_matkul": {
                        "kd_mk": "IF1800",
                        "nm_mk": "IF1800",
                        "nm_jurusan": "TEKNIK INFORMATIKA",
                        "kd_kur": "IFS120"
                    },
                    "detail_jadwal": null
                }
            ]
        }
    }
}</code></pre>
    <p>
        Properti <b>Unknown</b> menandakan bahwa kelas kuliah yang ada di dalamnya belum memiliki jadwal, hal tersebut dapat dilihat juga pada nilai <b>detail_jadwal</b> yang masih berisi <b>null</b>.
    </p>
</div>
@endsection
