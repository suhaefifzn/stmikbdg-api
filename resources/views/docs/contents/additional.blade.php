@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Additional Routes</h4>
<hr>
<p>
    Route-route tambahan dibuat dengan harapan untuk mempermudah akses keterbacaan data dari response yang diberikan. Misalnya seperti route untuk cek tahun ajaran aktif di menu KRS yang memiliki alternatifnya.
</p>
<div class="m-2">
    <h5 class="mt-4">(MHS) Get Semester Sekarang</h5>
    <hr>
    <p>
        Dibuat untuk melihat semester saat ini yang sedang berjalan berdasarkan pada tahun masuk mahasiswa. Kirimkan request ke <span class="badge bg-dark">/current-semester</span> dengan HTTP method <span class="badge bg-info">get</span>. Hasilnya adalah:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tahun": 2023,
        "smt": 2,
        "keterangan_smt": "Genap",
        "semester": 8
    }
}</code></pre>
    <h5 class="mt-4">(DSN, DEV, ADM) Get List Jurusan Aktif</h5>
    <hr>
    <p>
        Digunakan untuk melihat daftar jurusan aktif di kampus STMIK Bandung. Lakukan request ke <span class="badge bg-dark">/jurusan</span> dengan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jurusan_aktif": [
            {
                "jur_id": 23,
                "fak_id": 0,
                "kd_jur": "12",
                "nama_jurusan": "S1 - TEKNIK INFORMATIKA",
                "nm_singkat": "IFS1 ",
                "prodi": "S1",
                "k_aktif": true
            },
            {
                "jur_id": 27,
                "fak_id": 0,
                "kd_jur": "32",
                "nama_jurusan": "S1 - SISTEM INFORMASI",
                "nm_singkat": "MIS1 ",
                "prodi": "S1",
                "k_aktif": true
            }
        ]
    }
}</code></pre>
</div>
@endsection
