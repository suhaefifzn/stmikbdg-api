@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kuesioner</h4>
<hr>
<div class="m-2">
    <h5 class="mt-4">(MHS) - Get List Dosen Aktif</h5>
    <hr>
    <p>
        Kirim request menggunakan HTTP method <span class="badge bg-info">get</span> ke <span class="badge bg-dark">/kuesioner/dosen-aktif</span>. Jika berhasil akan memberikan response dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "dosen_aktif": [
            {
                "dosen_id": 714,
                "nm_dosen": "ERFIZAL FIKRI YUSMANSYAH, M.T",
                "kd_dosen": "EF",
                "gelar": "M.T"
            },
            {
                "dosen_id": 573,
                "nm_dosen": "Mina Ismu Rahayu, M.T",
                "kd_dosen": "IF054",
                "gelar": "M.T"
            },
            // dosen lain disembunyikan
        ]
    }
}</code></pre>
    <h5 class="mt-4">(MHS) - Get Matakuliah Diampu Oleh Dosen</h5>
    <hr>
    <p>
        Untuk mendapatkan daftar matakuliah yang diampu oleh dosen diperlukan nilai <b>dosen_id</b> yang didapat setelah memilih salah satu dosen aktif yang ada pada list di atas. Kemudian ganti <b>dosen_id</b> yang ada pada <span class="badge bg-dark">/kuesioner/dosen-aktif/dosen_id/matkul-diampu</span> dengan nilainya. Misalnya <span class="badge bg-dark">/kuesioner/dosen-aktif/573/matkul-diampu</span>, kirim dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "dosen": {
            "dosen_id": 573,
            "nama": "Mina Ismu Rahayu, M.T",
            "gelar": "M.T",
            "kd_dosen": "IF054",
            "matkul_diampu": [
                {
                    "mk_id": 85,
                    "nm_mk": "Paket Aplikasi"
                },
                {
                    "mk_id": 114,
                    "nm_mk": "Kecerdasan Buatan"
                },
                {
                    "mk_id": 134,
                    "nm_mk": "Metodologi Penelitian"
                },
                {
                    "mk_id": 130,
                    "nm_mk": "Pengolahan Citra"
                }
            ]
        }
    }
}</code></pre>
    <p>
        Daftar matkul diampu yang muncul diambil berdasarkan tahun ajaran aktif mahasiswa yang nantinya akan mengisi kuesioner. Kemudian disesuaikan dengan jenis mahasiswa tersebut apakah ia adalah mahasiswa kelas karyawan, reguler, atau eksekutif. Selanjutkan disesuaikan lagi dengan kampus mahasiswa tersebut berada. Sehingga matkul diampu yang muncul tidaklah banyak karena difilter berdasarkan tahun aktif dan user sebagai mahasiswa yang akan mengisi kuesionernya.
    </p>
</div>
@endsection
