@extends('docs.template.index')
@section('docs_contents')
<h4><b>#</b> KRS - Sisi Mahasiswa</h4>
<hr>
<div class="m-2">
    <p>
        KRS terbagi menjadi dua sisi, yaitu sisi untuk mahasiswa dan dosen. Sisi mahasiswa adalah melakukan pengajuan KRS pada batas waktu tertentu sesuai dengan tahun ajaran dan semester aktif.
    </p>
    <h5 class="mt-4">(MHS) Cek Tahun Ajaran Aktif</h5>
    <hr>
    <p>
        Digunakan untuk mendapatkan tahun ajaran aktif dan juga berisi informasi mengenai dibuka atau ditutupnya pengajuan KRS mahasiswa. Lakukan permintaan ke <span class="badge bg-dark">/krs/tahun-ajaran</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, jika permintaan berhasil maka akan memberikan response dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tahun_ajaran": {
            "tahun_id": 335,
            "jur_id": 23,
            "tahun": 2023,
            "smt": 1,
            "jns_mhs": "R",
            "uraian": "IFS1 / 2023 / Ganjil / Reguler",
            "pmb_mulai": "2023-09-25",
            "pmb_sampai": "2023-09-25",
            "du_mulai": "2023-09-25",
            "du_sampai": "2023-09-25",
            "tgl_kuliah": "2023-09-25",
            "tgl_uts": null,
            "tgl_uas": null,
            "sts_ta": "B",
            "kd_kampus": "A",
            "buka_semua_mk": false,
            "du_open": false
        }
    }
}</code></pre>
    <p>
        Perhatikan nilai <b>du_open</b>, jika nilainya adalah <b>false</b> maka pengajuan KRS mahasiswa ditutup dan jika nilainya adalah <b>true</b> berarti pengajuan KRS mahasiswa dibuka. Untuk melihat informasi batas waktu pengajuan KRS dapat diketahui pada nilai <b>du_mulai</b> dan <b>du_sampai</b>.
    </p>
    <p>
        Perhatikan nilai <b>smt</b>, jika nilainya adalah <b>1 berarti semester ganjil</b>, jika <b>2 berarti semester genap</b>, dan jika <b>3 berarti semester tambahan</b>. Kemudian gunakan nilai pada <b>tahun_id</b> untuk mendapatkan list matakuliah yang aktif di tahun ajaran tersebut dan <b>tahun_id</b> juga digunakan sebagai payload bersamaan dengan daftar matakuliah yang dipilih sebagai pengajuan KRS nantinya.
    </p>

    {{-- Check Pengajuan KRS Terakhir --}}
    <h5 class="mt-4">(MHS) Cek Pengajuan KRS Terkahir (Alternatif Cek Tahun Ajaran)</h5>
    <hr>
    <p>
        Kami menambahkan alternatif lain untuk memeriksa apakah pengajuan KRS mahasiswa dapat dilakukan atau tidak. cara ini merupakan alternatif dari cek tahun ajaran aktif. Lakukan request ke <span class="badge bg-dark">/krs/check</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil maka responsenya adalah:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Hanya Dosen Wali yang dapat mengembalikan status KRS yang sudah disetujui",
    "data": {
        "krs": {
            "open": false,
            "krs_id": 4169,
            "sts_krs": "S",
            "keterangan_status": "Disetujui",
            "nmr_krs": "020/12/KR1/23",
            "semester": 7
        },
        "tahun_ajaran": {
            "tahun_id": 335,
            "tahun": 2023,
            "du_open": false,
            "du_sampai": "2023-09-25"
        }
    }
}</code></pre>
    <p>
        Perhatikan nilai <b>open</b> yang terdapat pada bagian <b>krs</b>, nilai <b>open</b> tersebut menandakan bahwa krs dibuka atau tidak. Jika <b>false</b> berarti ditutup, jika <b>true</b> berarti dibuka. Di bagian <b>krs</b> juga Anda dapat melihat beberapa informasi mengenai pengajuan KRS terakhir.
    </p>
    <p>
        Apabila nilai dari <b>sts_krs</b> adalah <b>S</b> berarti KRS disetujui dan KRS tidak bisa diubah kecuali oleh dosen wali. Jika <b>D</b> berarti KRS berstatus sebagai draft atau saat pengajuan ditolak <b>sts_krs</b> juga akan menjadi <b>D</b>, pada status <b>D</b> mahasiswa dapat mengirim ulang pengajuan KRS baru selama dalam batas waktu pengajuan. Terakhir, jika <b>sts_krs</b> bernilai <b>P</b> berarti KRS telah diajukan dan sedang tahap review, pada tahap ini KRS ditutup sehingga mahasiswa tidak dapat mengajukan lagi sampai statusnya berubah.
    </p>
    <h5 class="mt-4">(MHS) Cek Matakuliah Aktif</h5>
    <hr>
    <p>
        Matakuliah aktif ditentukan berdasarkan tahun ajaran aktif, maka untuk mendapatkan daftar matakuliah diperlukan nilai <b>tahun_id</b> yang diperoleh dari dua cara sebelumnya. Kirim request ke <span class="badge bg-dark">/krs/mata-kuliah</span> dan tambahkan query atau parameter <span class="badge bg-secondary">tahun_id</span> sehingga menjadi <span class="badge bg-dark">/krs/mata-kuliah?tahun_id=335</span> untuk mendapatkan seluruh daftar matakuliah. Tambahkan <span class="badge bg-secondary">semester</span> apabila ingin mendapatkan daftar matakuliah di semester tertentu, seperti <span class="badge bg-dark">/krs/mata-kuliah?tahun_id=335&semester=7</span>, kemudian gunakan HTTP method <span class="badge bg-info">get</span>. Jika sukses maka akan mengembalikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "matkul_per_semester": [
            {
                "ipk": 2.9,
                "semester": 7,
                "ipk_dari_total_sks": 20,
                "total_nilai_A": 5,
                "total_nilai_B": 1,
                "total_nilai_C": 0,
                "total_nilai_D": 0,
                "total_nilai_E": 2,
                "mata_kuliah": [
                    {
                        "mk_id": 145,
                        "kur_id": 83,
                        "jur_id": 23,
                        "kd_mk": "IF1002",
                        "nm_mk": "Cloud Computing",
                        "nm_intl": null,
                        "semester": 7,
                        "sks": 3,
                        "jns_materi": "0",
                        "k_un": false,
                        "nmr_urut": 65,
                        "sts_mk": "A",
                        "jml_pertemuan": 1,
                        "smt": 2,
                        "tahun_id": 348,
                        "kd_kampus": "A",
                        "jns_mhs": "R",
                        "jur_id_penyelenggara": 23,
                        "sts_ta": "B",
                        "nilai_akhir": null,
                        "krs": {
                            "is_aktif": true,
                            "is_checked": true
                        }
                    },
                    // matkul lain disembunyikan
                ]
            }
        ]
    }
}</code></pre>
    <p>
        Lihatlah nilai <b>is_aktif</b> yang berada dalam properti <b>krs</b> di matakuliah Data Mining pada contoh response di atas. Jika nilai <b>is_aktif</b> adalah <b>true</b> maka matakuliah tersebut dapat dipilih untuk pengajuan KRS jika <b>false</b> maka berlaku sebaliknya. Kemudian jika nilai <b>is_checked</b> adalah <b>true</b> maka matakuliah tersebut telah dipilih dalam pengajuan KRS di tahun ajaran aktif.
    </p>

    {{-- Menyimpan KRS sebagai draft --}}
    <h5 class="mt-4">(MHS) KRS Mahasiswa - Draft (D)</h5>
    <hr>
    <p>
        Jika masih ragu mahasiswa dapat menyimpan pengajuan KRS dengan status sebagai draft sebelum benar-benar diajukan. Simpan nilai <b>mk_id</b> berdasarkan pada matakuliah yang dipilih dalam bentuk array of objects dan juga nilai <b>tahun_id</b> berdasarkan tahun ajaran aktif sebagai payload dalam format JSON seperti berikut ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tahun_id": 335, // id tahun ajaran - krs dibuka
    "pengajuan_catatan": "Draft KRS", // optional
    "mata_kuliah": [
        {
            "mk_id": 129
        },
        {
            "mk_id": 130
        }
    ]
}</code></pre>
    <p>
        Kemudian kirim request menggunakan HTTP method <span class="badge bg-info">post</span> ke <span class="badge bg-dark">/krs/mata-kuliah/draft</span>.
    </p>
    <p>
        Apabila matakuliah berhasil disimpan sebagai draft KRS. Maka akan mengembalikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Berhasil menyimpan KRS sebagai draft",
    "data": {
        "krs_id": 4550
    }
}</code></pre>

    {{-- Mengirim Pengajuan KRS - Status P --}}
    <h5 class="mt-4">(MHS) KRS Mahasiswa - Pengajuan (P)</h5>
    <hr>
    <p>
        Kirim data berupa payload yang sama seperti mengirim KRS sebagai draft di atas. Gunakan HTTP method <span class="badge bg-info">post</span> dan kirim payload ke <span class="badge bg-dark">/krs/mata-kuliah/pengajuan</span>. Jika berhasil akan mendapat response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Berhasil mengirim pengajuan KRS",
    "data": {
        "krs_id": 4550
    }
}</code></pre>
    <p>
        Perlu diingat bahwa KRS yang telah diajukan tidak dapat diubah lagi. KRS baru dapat diubah jika dosen wali menolak pengajuan tersebut dan masih dalam batas waktu pengajuan KRS yang telah ditentukan.
    </p>

    {{-- Melihat draft krs --}}
    <h5 class="mt-4">(MHS) Get Draft KRS Matakuliah</h5>
    <hr>
    <p>
        Untuk melihat matakuliah pada KRS yang disimpan sebagai draft kirimkan request ke <span class="badge bg-dark">/krs/mata-kuliah/draft</span> menggunakan HTTP method <span class="badge bg-info">get</span>. Draft KRS hanya bisa dilihat apabila pengisian KRS masih dibuka. Berikut adalah response yang diberikan:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "krs": {
            "krs_id": 4615,
            "tahun_id": 348,
            "nmr_krs": "015/12/KR2/23",
            "tanggal": "2024-01-12",
            "mhs_id": 15534,
            "semester": 8,
            "sts_krs": "D",
            "kd_kampus": "A",
            "dosen_id": null,
            "pengajuan_catatan": null,
            "ditolak_tanggal": null,
            "ditolak_alasan": null,
            "ditolak_stlh_sah": null,
            "kd_chanel": "A",
            "krs_matkul": [
                {
                    "krs_mk_id": 35018,
                    "krs_id": 4615,
                    "mk_id": 136,
                    "sts_mk_krs": "A",
                    "detail_matkul": {
                        "kd_mk": "IF1800",
                        "nm_mk": "Skripsi",
                        "semester": 8,
                        "sts_mk": "A",
                        "kd_kur": "IFS120"
                    }
                },
                // matkul lain disembunyikan
            ]
        }
    }
}</code></pre>
</div>
@endsection
