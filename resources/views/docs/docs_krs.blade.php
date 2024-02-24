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
                "ipk": 4,
                "semester": 7,
                "ipk_per_semester_dari_total_sks": 3,
                "mata_kuliah": [
                    {
                        "mk_id": 139,
                        "kur_id": 83,
                        "jur_id": 23,
                        "kd_mk": "SI1507",
                        "nm_mk": "Data Mining(*)",
                        "nm_intl": null,
                        "semester": 7,
                        "sks": 3,
                        "jns_materi": "0",
                        "k_un": false,
                        "nmr_urut": 59,
                        "sts_mk": "A",
                        "jml_pertemuan": 1,
                        "smt": 1,
                        "tahun_id": 335,
                        "kd_kampus": "A",
                        "jns_mhs": "R",
                        "jur_id_penyelenggara": 23,
                        "sts_ta": "B",
                        "nilai_akhir": {
                            "nilai": "A",
                            "mutu": 4
                        },
                        "krs": {
                            "is_aktif": true,
                            "is_checked": false
                        }
                    },
                    // Matakuliah lain disembunyikan
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
</div>

{{-- Untuk KRS - Sisi Doen --}}
<h4 class="mt-4"><b>#</b> KRS - Sisi Dosen</h4>
<hr>
<p>
    Alamat API yang ada di bawah ini merupakan bagian yang digunakan oleh user yang terautentikasi sebagai dosen. Digunakan untuk melihat dan update KRS terbaru dari mahasiswa.
</p>
<div class="m-2">
    <h5 class="mt-4">(DSN WALI) Get List KRS Mahasiswa</h5>
    <hr>
    <p>
        Digunakan untuk mendapatkan daftar KRS terbaru yang mahasiswa ajukan. Daftar krs yang muncul adalah milik mahasiswa yang dosen walinya adalah user yang terautentikasi sebagai dosen wali yang bersangkutan. Daftar yang muncul diurutkan berdasarkan pengajuan KRS paling baru.
    </p>
    <p>
        Lakukan request ke <span class="badge bg-dark">/krs/mahasiswa/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span> untuk mendapatkan semua daftar KRS. Tambahkan query <span class="badge bg-secondary">search</span> jika ingin mendapatkan KRS berdasarkan NIM atau nama mahasiswa. Contohnya <span class="badge bg-dark">/krs/mahasiswa/list?search=1220001</span>.
    </p>
    <p>Hasilnya adalah:</p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_krs_mahasiswa": [
            {
                "mhs_id": 15534,
                "angkatan_id": 76,
                "dosen_id": 573,
                "jur_id": 23,
                "nim": "1220001",
                "nama": "SUHAEFI FAUZIAN",
                "jns_mhs": "R",
                "sts_mhs": "A",
                "kd_kampus": "A ",
                "kelas": "A",
                "jk": "L",
                "masuk_tahun": 2020,
                "krs_id_last": 4550,
                "tanggal_krs": "2024-02-20"
            }
        ]
    }
}</code></pre>
    <p>
        Apabila ingin membagi data mahasiswa menjadi per halaman atau memiliki pagination, maka tambahkan <span class="badge bg-secondary">page</span> dan isi nilainya dengan halaman keberapa yang akan didapatkan datanya. Untuk defaultnya mohon isikan dengan nilai <b>1</b>. Sehingga secara penuh urlnya menjadi <span class="badge bg-dark">/krs/mahasiswa/list?page=1</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
        "status": "success",
        "data": {
            "list_krs_mahasiswa": [
                {
                    "mhs_id": 15534,
                    "angkatan_id": 76,
                    "dosen_id": 573,
                    "jur_id": 23,
                    "nim": "1220001",
                    "nama": "SUHAEFI FAUZIAN",
                    "jns_mhs": "R",
                    "sts_mhs": "A",
                    "kd_kampus": "A ",
                    "kelas": "A",
                    "jk": "L",
                    "masuk_tahun": 2020,
                    "krs_id_last": 4550,
                    "tanggal_krs": "2024-02-20"
                },
                // mahasiswa lain disembunyikan
            ]
        },
        "meta": {
            "page": 1,
            "per_page": 10,
            "total_pages": 17,
            "total_items": 166,
            "prev_page_url": null,
            "next_page_url": "http://stmikbdg-api.test/api/krs/mahasiswa/list?page=2"
        }
}</code></pre>
    <p>
        Jika menambahkan <span class="badge bg-secondary">page</span> maka akan terdapat properti baru pada response yang bernama <b>meta</b>. Properti tersebut menyimpan informasi mengenai posisi halaman, total item per halaman, total halaman, hingga, url ke halaman berikutnya.
    </p>
    <h5 class="mt-4">(DSN WALI) Get Detail KRS Mahasiswa Menggunakan mhs_id and krs_id</h5>
    <hr>
    <p>
        Untuk mendapatkan detail dari satu KRS mahasiswa lakukan permintaan ke <span class="badge bg-dark">/krs/mahasiswa</span> dan tambahkan query pada urlnya, yaitu <span class="badge bg-secondary">mhs_id</span> yang nilainya dipilih dari list di atas dan <span class="badge bg-secondary">krs_id</span> yang merupakan nilai dari <b>krs_last_id</b> yang dipilih dari list di atas yang berada pada object yang sama dengan <b>mhs_id</b>-nya.
    </p>
    <p>
        Sehingga lengkapnya adalah seperti <span class="badge bg-dark">/krs/mahasiswa?mhs_id=15534&krs_id=4550</span> kemudian kirim menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil, response yang diberikan adalah sebagai berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "mahasiswa": {
            "mhs_id": 15534,
            "nim": "1220001",
            "nama": "SUHAEFI FAUZIAN",
            "jurusan": {
                "jur_id": 23,
                "nama_jurusan": "S1 - TEKNIK INFORMATIKA",
                "nm_singkat": "IFS1 "
            },
            "krs": {
                "krs_id": 4550,
                "tahun_id": 335,
                "nmr_krs": "089/12/KR1/23",
                "tanggal": "2024-02-20",
                "semester": 7,
                "sts_krs": "P",
                "kd_kampus": "A",
                "kd_chanel": "A",
                "pengajuan_catatan": "Draft KRS",
                "ditolak_tanggal": null,
                "ditolak_alasan": null,
                "ditolak_stlh_sah": null,
                "krs_matkul": [
                    {
                        "krs_mk_id": 34658,
                        "mk_id": 129,
                        "sts_mk_krs": "A",
                        "tgl_perubahan": "2024-02-20",
                        "kd_mk": "IF1709",
                        "nm_mk": "Analisis Numerik",
                        "sks": 2,
                        "k_disetujui": false
                    },
                    {
                        "krs_mk_id": 34659,
                        "mk_id": 130,
                        "sts_mk_krs": "A",
                        "tgl_perubahan": "2024-02-20",
                        "kd_mk": "IF1710",
                        "nm_mk": "Pengolahan Citra",
                        "sks": 3,
                        "k_disetujui": false
                    }
                ]
            }
        }
    }
}</code></pre>
    <p>
        Kemudian nilai <b>mhs_id</b>, <b>krs_id</b>, dan setiap <b>krs_mk_id</b> yang terdapat pada <b>krs_matkul</b> nantinya digunakan untuk memperbaharui KRS mahasiswa tersebut.
    </p>

    {{-- Update pengajuan KRS mahasiswa oleh dosen wali --}}
    <h5 class="mt-4">(DSN WALI) Update KRS Mahasiswa</h5>
    <hr>
    <p>
        Gunakan nilai <b>mhs_id, krs_id</b>, dan <b>krs_id</b> yang didapat setelah get detail mahasiswa untuk memperbaharui KRS mahasiswa. Status KRS yang telah diajukan ditandai dengan <b>sts_krs</b> bernilai <b>P</b>. Berikutnya adalah beberapa status yang dapat digunakan untuk memperbaharui KRS mahasiswa.
    </p>
    <ul>
        <li><b>D</b> -> draft atau saat ditolak</li>
        <li><b>S</b> -> sah atau sudah disetujui</li>
    </ul>
    <p>
        Untuk memperbaharuinya lakukan request ke <span class="badge bg-dark">/krs/mahasiswa</span> menggunakan HTTP method <span class="badge bg-info">put</span> dengan mengirim payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "mhs_id": 15534,
    "krs_id": 4550,
    "sts_krs": "D", // D = Draft; S = Setujui;
    "ditolak_alasan": "Matkul terlalu sedikit", // Jika sts_krs D maka alasan wajib diisi
    "krs_matkul": [
        {
            "krs_mk_id": 34658,
            "k_disetujui": true
        },
        {
            "krs_mk_id": 34659,
            "k_disetujui": true
        }
    ]
}</code></pre>
    <p>
        Jika request berhasil maka akan memberikan response seperti di bawah ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "KRS mahasiswa berhasil diperbaharui",
    "data": {
        "krs_id": 4550
    }
}</code></pre>
</div>
