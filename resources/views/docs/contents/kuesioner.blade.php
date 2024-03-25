@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kuesioner</h4>
<hr>
<div class="m-2">
    <h5 class="mt-4">(MHS) Get List Matkul Berdasarkan KRS Terakhir dan Tahun Ajaran Aktif</h5>
    <hr>
    <p>
        Kirim permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/mata-kuliah</span> untuk mendapatkan daftar mata kuliah mahasiswa berdasarkan KRS terakhir yang disetujui dan tahun ajaran aktif mahasiswa tersebut. Jika berhasil atau mahasiswa telah memenuhi KRS di tahun ajaran aktif, maka akan memberikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tahun_id": 348,
        "tahun": 2023,
        "list_matkul": [
            {
                "krs_mk_id": 35018,
                "krs_id": 4615,
                "mk_id": 136,
                "kelas_kuliah_id": 3295,
                "pengajar": null,
                "sts_isi_kuesioner": false,
                "matakuliah": {
                    "mk_id": 136,
                    "kur_id": 83,
                    "jur_id": 23,
                    "kd_mk": "IF1800",
                    "nm_mk": "Skripsi",
                    "semester": 8,
                    "sks": 6,
                    "kd_kur": "IFS120",
                    "smt": 2
                }
            }
            // matkul lain disembunyikan
        ]
    }
}</code></pre>
    <p>Setiap mata kuliah memiliki nilai <b>sts_isi_kuesioner</b> atau status mengisi kuesioner, yang menandakan bahwa Mahasiswa telah mengisi kuesioner untuk matkul tersebut atau belum berdasarkan nilainya. Jika nilainya true berarti telah mengisi dan false belum mengisi kuesioner.</p>
</div>
@endsection
