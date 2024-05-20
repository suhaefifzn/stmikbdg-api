@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> SIKPS - Sistem Deteksi Kemiripan Proposal Skripsi</h4>
<hr>
<div class="m-2">
    <h5 class="mt-5"> (MHS) Add Hasil Deteksi</h5>
    <hr>
    <p>
        Kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-info">post</span> ke <span class="badge bg-dark">/sikps/mahasiswa/deteksi/hasil/add</span> untuk menyimpan hasil deteksi. Kirimkan juga payload berupa JSON dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "nim": "1220001",
    "nm_mhs": "Suhaefi Fauzian",
    "judul": "Test Add Hasil Deteksi",
    "file": "proposal.pdf",
    "persentase_kemiripan": 2.5
}</code></pre>

    <h5 class="mt-5">(MHS) Get List Hasil Deteksi</h5>
    <hr>
    <p>
        Untuk melihat semua hasil deteksi milik mahasiswa sendiri, kirimkan permintaan ke <span class="badge bg-dark">/sikps/mahasiswa/deteksi/hasil/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "hasil_deteksi": [
            {
                "similarity_id": 4,
                "nim": "1220001",
                "nm_mhs": "Suhaefi Fauzian",
                "judul": "Test Add Hasil Deteksi",
                "file": "proposal.pdf",
                "persentase_kemiripan": "2.5",
                "created_at": "2024-05-18 11:18:51"
            },
            // hasil deteksi lainnya...
        ]
    }
}</code></pre>

    <h5 class="mt-5">(MHS) Update Proposal Hasil Deteksi</h5>
    <hr>
    <p>
        Untuk memperbarui proposal yang pernah dideteksi kemiripannya, kirimkan permintaan ke <span class="badge bg-dark">/sikps/mahasiswa/deteksi/hasil/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload dalam bentuk JSON dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "similarity_id": 4,
    "judul": "Test Edit Proposal Hasil Deteksi",
    "file": "proposal_edit.pdf",
    "persentase_kemiripan": 3.6
}</code></pre>

    <h5 class="mt-5">(MHS) Delete Proposal yang Telah Dideteksi</h5>
    <hr>
    <p>
        Untuk menghapus proposal yang pernah dideteksi, kirimkan permintaan ke <span class="badge bg-dark">/sikps/mahasiswa/deteksi/hasil/delete</span>. Gunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan payload dalam bentuk JSON dengan format:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "similarity_id": 4
}</code></pre>

    <h5 class="mt-5">(MHS) Get List Fingerprints</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/sikps/mahasiswa/deteksi/fingerprints/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "fingerprints": [
            {
                "fingerprint_id": 4,
                "judul": "ANALISIS KUALITAS LAYANAN WEBSITE LMS STMIK BANDUNG TERHADAP KEPUASAN PENGGUNA MENGGUNAKAN METODE WEBQUAL 4.0 DAN IMPORTANCE PERFORMANCE ANALYSIS (IPA)",
                "file_dokumen": "http://103.119.67.164:18088/sikps/proposal/153/stream",
                "n_gram": "NUJLEg3KFPTkBuqx4CxtGLFfkaQU7PnyCEU4W0EShG98CmNBgTp5nqQZDmzXaN6qGw6GS7m9L6fvUqda4BC7hwgriahtSfNEfvWe5qWE0rZwVcVReYZaucukSmH2TQEd",
                "hashing": "WzN?JijQ%EWtbBtqM,H&bkG&eAwmVrpwm4i+b?}%(x95R4P(Tj-TLDpj(JnK:7VC-RJtVu[Kw7{1uk(fFizqG-=YKxG;tu9}F6cWx=tun}uCEzCw.{J$u+P)i3x{dmNr",
                "winnowing": "1jbau7WzeZawdYyUx8Tv67uwXvMYcNL54Z1Vf0N7eqBWcP6gbWip8rx4LYXYW6BA4CLkYqHTDtvYArbXnqCmbS8E2KnS1JGQcxxZgK7SjXVnK3C8DCg8DyFAvg62fPcN",
                "fingerprint": "ua8]Ggp,H+Hnuq)8@C?7yyBWABUUcjC!@7)7x.HX!2[j8pD/:Cx89F(7mTy0899}T-VMGCM7#!]iTC*_+#gC4:y,r2PigMG*tTt@(9xviyH19X;LN:WFqXF+wmU9pbG)",
                "total_fingerprint": 128,
                "total_ngram": 128,
                "total_hash": 128,
                "total_window": 128,
                "is_generated": false,
                "created_at": "2024-05-20 07:05:26.126"
            },
            // list fingerprint lainnya ...
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Add Fingerprint Proposal</h5>
    <hr>
    <p>
        Untuk menambahkan hasil proposal yang telah diubah menjadi fingerprint, kirimkan permintaan ke <span class="badge bg-dark">/sikps/deteksi/fingerprints/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload dalam bentuk JSON dengan format seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "fingerprints": [
        {
            "judul": "ANALISIS KUALITAS LAYANAN WEBSITE LMS STMIK BANDUNG TERHADAP KEPUASAN PENGGUNA MENGGUNAKAN METODE WEBQUAL 4.0 DAN IMPORTANCE PERFORMANCE ANALYSIS (IPA)",
            "file_dokumen": "http://103.119.67.164:18088/sikps/proposal/153/stream",
            "n_gram": "NUJLEg3KFPTkBuqx4CxtGLFfkaQU7PnyCEU4W0EShG98CmNBgTp5nqQZDmzXaN6qGw6GS7m9L6fvUqda4BC7hwgriahtSfNEfvWe5qWE0rZwVcVReYZaucukSmH2TQEd",
            "hashing": "WzN?JijQ%EWtbBtqM,H&bkG&eAwmVrpwm4i+b?}%(x95R4P(Tj-TLDpj(JnK:7VC-RJtVu[Kw7{1uk(fFizqG-=YKxG;tu9}F6cWx=tun}uCEzCw.{J$u+P)i3x{dmNr",
            "winnowing": "1jbau7WzeZawdYyUx8Tv67uwXvMYcNL54Z1Vf0N7eqBWcP6gbWip8rx4LYXYW6BA4CLkYqHTDtvYArbXnqCmbS8E2KnS1JGQcxxZgK7SjXVnK3C8DCg8DyFAvg62fPcN",
            "fingerprint": "ua8]Ggp,H+Hnuq)8@C?7yyBWABUUcjC!@7)7x.HX!2[j8pD/:Cx89F(7mTy0899}T-VMGCM7#!]iTC*_+#gC4:y,r2PigMG*tTt@(9xviyH19X;LN:WFqXF+wmU9pbG)",
            "total_fingerprint": 128,
            "total_ngram": 128,
            "total_hash": 128,
            "total_window": 128,
            "is_generated": false,
            "created_at": "2024-05-18 04:49:10.326"
        }
    ]
}</code></pre>
    <p>
        Nilai <b>is_generated</b> digunakan untuk menentukan apakah fingerprint berasal dari list proposal yang terdapat pada API SIKPS yang berjalan sekarang atau bukan. Jika bukan berarti proposal ditambahkan secara manual melalui fitur tambah proposal di sistem deteksi langsung, maka isikan <b>is_generated</b> dengan nilai false. Jika setiap fingerprint berasal dari API SIKPS maka isikan nilai <b>is_generated</b> dengan nilai true.
    </p>
    <p>
        Isilah nilai <b>created_at</b> dengan waktu yang merupakan timestamp dan telah diformat ke ISO 8601. Jika menggunakan Laravel, cara cepatnya cukup isikan dengan <b>now()</b>.
    </p>

    <h5 class="mt-4">(ADM) Get List Fingerprint Proposal Tersedia</h5>
    <hr>
    <p>
        Untk melihat semua fingerprint proposal yang tersedia, kirimkan permintaan ke <span class="badge bg-dark">/sikps/deteksi/fingerprints/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya akan seperti:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "fingerprints": [
            {
                "fingerprint_id": 4,
                "judul": "ANALISIS KUALITAS LAYANAN WEBSITE LMS STMIK BANDUNG TERHADAP KEPUASAN PENGGUNA MENGGUNAKAN METODE WEBQUAL 4.0 DAN IMPORTANCE PERFORMANCE ANALYSIS (IPA)",
                "file_dokumen": "http://103.119.67.164:18088/sikps/proposal/153/stream",
                "n_gram": "NUJLEg3KFPTkBuqx4CxtGLFfkaQU7PnyCEU4W0EShG98CmNBgTp5nqQZDmzXaN6qGw6GS7m9L6fvUqda4BC7hwgriahtSfNEfvWe5qWE0rZwVcVReYZaucukSmH2TQEd",
                "hashing": "WzN?JijQ%EWtbBtqM,H&bkG&eAwmVrpwm4i+b?}%(x95R4P(Tj-TLDpj(JnK:7VC-RJtVu[Kw7{1uk(fFizqG-=YKxG;tu9}F6cWx=tun}uCEzCw.{J$u+P)i3x{dmNr",
                "winnowing": "1jbau7WzeZawdYyUx8Tv67uwXvMYcNL54Z1Vf0N7eqBWcP6gbWip8rx4LYXYW6BA4CLkYqHTDtvYArbXnqCmbS8E2KnS1JGQcxxZgK7SjXVnK3C8DCg8DyFAvg62fPcN",
                "fingerprint": "ua8]Ggp,H+Hnuq)8@C?7yyBWABUUcjC!@7)7x.HX!2[j8pD/:Cx89F(7mTy0899}T-VMGCM7#!]iTC*_+#gC4:y,r2PigMG*tTt@(9xviyH19X;LN:WFqXF+wmU9pbG)",
                "total_fingerprint": 128,
                "total_ngram": 128,
                "total_hash": 128,
                "total_window": 128,
                "is_generated": false,
                "created_at": "2024-05-18 04:49:10.326"
            },
            // data fingerprint lainnya ...
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Update Fingerprint Proposal</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/sikps/deteksi/fingerprints/update</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span> dan sertakan payload dalam bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "fingerprint_id": 4,
    "judul": "Test Edit Fingerprint Proposal",
    "file_dokumen": "test_edit.pdf",
    "n_gram": "NUJLEg3KFPTkBuqx4CxtGLFfkaQU7PnyCEU4W0EShG98CmNBgTp5nqQZDmzXaN6qGw6GS7m9L6fvUqda4BC7hwgriahtSfNEfvWe5qWE0rZwVcVReYZaucukSmH2TQEd",
    "hashing": "WzN?JijQ%EWtbBtqM,H&bkG&eAwmVrpwm4i+b?}%(x95R4P(Tj-TLDpj(JnK:7VC-RJtVu[Kw7{1uk(fFizqG-=YKxG;tu9}F6cWx=tun}uCEzCw.{J$u+P)i3x{dmNr",
    "winnowing": "1jbau7WzeZawdYyUx8Tv67uwXvMYcNL54Z1Vf0N7eqBWcP6gbWip8rx4LYXYW6BA4CLkYqHTDtvYArbXnqCmbS8E2KnS1JGQcxxZgK7SjXVnK3C8DCg8DyFAvg62fPcN",
    "fingerprint": "ua8]Ggp,H+Hnuq)8@C?7yyBWABUUcjC!@7)7x.HX!2[j8pD/:Cx89F(7mTy0899}T-VMGCM7#!]iTC*_+#gC4:y,r2PigMG*tTt@(9xviyH19X;LN:WFqXF+wmU9pbG)",
    "total_fingerprint": 128,
    "total_ngram": 128,
    "total_hash": 128,
    "total_window": 128
}</code></pre>
    <p>
        Apabila <b>file_dokumen</b> tidak berubah maka jangan sertakan nilai tersebut.
    </p>

    <h5 class="mt-5">(ADM) Hapus Fingerprint Proposal</h5>
    <hr>
    <p>
        Untuk menghapus fingerprint proposal yang tersedia pada list, kirimkan permintaan ke <span class="badge bg-dark">/sikps/deteksi/fingerprints/delete</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan sertakan nilai <b>fingerprint_id</b> sebagai payload dalam bentuk JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "fingerprint_id": 4
}</code></pre>

    <h5 class="mt-5">(ADM) Get List Hasil Deteksi Mahasiswa</h5>
    <hr>
    <p>
        Untuk melihat semua riwayat hasil deteksi mahasiswa, kirimkan permintaan ke <span class="badge bg-dark">/sikps/deteksi/riwayat</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "hasil_deteksi": [
            {
                "similarity_id": 4,
                "nim": "1220001",
                "nm_mhs": "Suhaefi Fauzian",
                "judul": "Test Add Hasil Deteksi",
                "file": "proposal.pdf",
                "persentase_kemiripan": "2.5",
                "created_at": "2024-05-18 11:18:51"
            }
        ]
    }
}</code></pre>
</div>
@endsection
