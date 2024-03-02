@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kelas Kuliah - Sisi Dosen</h4>
<hr>
<p>
    Route-route API pada halaman ini digunakan untuk mendapatkan jadwal kuliah untuk dosen, daftar kelas kuliah yang ditampilkan berdasarkan semua tahun ajan aktif. Selain melihat jadwal kuliah, digunakan juga untuk membuka kelas yang menghasilkan kode berupa PIN sebanyak 6 digit yang nantinya digunakan oleh mahasiswa untuk mengisi kehadiran.
</p>
<div class="m-2">
    <h5 class="mt-4">(MHS) Get Daftar Kelas Kuliah</h5>
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
                    "kd_kampus": "A",
                    "tahun_id": 351,
                    "jur_id": 27,
                    "mk_id": 62,
                    "kelas_kuliah": "A",
                    "semester": 2,
                    "jml_sks": 2,
                    "jns_mhs": "K",
                    "pengajar_id": 573,
                    "jadwal_kuliah1": "Rabu, 19:45-21:15",
                    "jadwal_kuliah2": null,
                    "jadwal_uts": null,
                    "jadwal_uas": null,
                    "nm_dosen": "Mina Ismu Rahayu, M.T",
                    "sts_kelas": "B",
                    "kjoin_kelas": false,
                    "join_kelas_kuliah_id": null,
                    "detail_matkul": {
                        "kd_mk": "KU1813",
                        "nm_mk": "HAKI &Etika Profesi",
                        "nm_jurusan": "SISTEM INFORMASI",
                        "kd_kur": "MIS120"
                    },
                    "detail_jadwal": {
                        "tanggal": "2024-02-28",
                        "jns_pert": "T1",
                        "jam": "19:45-21:15",
                        "kd_ruang": "",
                        "n_akhir": null,
                        "kjoin_kelas": false
                    }
                },
            ]
            // jadwal lain disembunyikan
        }
    }
}</code></pre>
    <p>
        Jika terdapat properti <b>Unknown</b> menandakan bahwa kelas kuliah yang ada di dalamnya belum memiliki jadwal, hal tersebut dapat dilihat juga pada nilai <b>detail_jadwal</b> yang masih berisi <b>null</b>.
    </p>
</div>
@endsection
