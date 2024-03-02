@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> SIKPS</h4>
<hr>
<div class="m-2">
    <p>
        Beberapa route di bawah saat ini masih dikategorikan sebagai tambahan yang mungkin dapat digunakan untuk kepentingan lain..
    </p>

    {{-- Route untuk get data mahasiswa--}}
    <h5 class="mt-4">(MHS) Get All Data Skripsi Mahasiswa</h5>
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
                "nim": "1220701",
                "nm_mhs": "RISKA KOMALASARI",
                "judul_skripsi": "Sistem Monitoring Evaluasi Kinerja SMKN 1 Subang Menggunakan Metode Naive Bayes\r\n",
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
@endsection
