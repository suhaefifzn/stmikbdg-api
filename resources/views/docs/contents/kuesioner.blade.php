@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kuesioner</h4>
<hr>
<div class="m-2">
    <h5 class="mt-4">(MHS) Kuesioner Perkuliahan - Get List Matkul Aktif</h5>
    <hr>
    <p>
        Kirim permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/perkuliahan/list-matkul</span> untuk mendapatkan daftar mata kuliah mahasiswa berdasarkan KRS terakhir yang disetujui dan tahun ajaran aktif mahasiswa tersebut. Jika berhasil atau mahasiswa telah memenuhi KRS di tahun ajaran aktif, maka akan memberikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tahun_id": 280,
        "tahun": 2022,
        "list_matkul": [
            {
                "krs_mk_id": 22037,
                "krs_id": 2928,
                "mk_id": 114,
                "kelas_kuliah_id": 2164,
                "pengajar": {
                    "dosen_id": 573,
                    "nm_dosen": "Mina Ismu Rahayu, M.T",
                    "gelar": "M.T"
                },
                "kuesioner_open": true,
                "sts_isi_kuesioner": false,
                "matakuliah": {
                    "mk_id": 114,
                    "kur_id": 83,
                    "jur_id": 23,
                    "kd_mk": "IF1507",
                    "nm_mk": "Kecerdasan Buatan",
                    "semester": 5,
                    "sks": 2,
                    "kd_kur": "IFS120",
                    "smt": 1
                }
            }
            // matkul lain disembunyikan
        ]
    }
}</code></pre>
    <p>
        Nilai <b>kuesioner_open</b> digunakan untuk mengetahui kuesioner untuk matkul tersebut dibuka atau tidak, jika dibuka maka akan bernilai true dan jika ditutup maka bernilai false.
    </p>
    <p>
        Nilai <b>sts_isi_kuesioner</b> atau status mengisi kuesioner, yang menandakan bahwa Mahasiswa telah mengisi kuesioner untuk matkul tersebut atau belum berdasarkan nilainya. Jika nilainya true berarti telah mengisi dan mahasiswa tidak bisa mengisi ulang kuesioner untuk matkul tersebut, jika false maka belum mengisi kuesioner.
    </p>

    {{-- Get pertanyaan kuesioner matkul --}}
    <h5 class="mt-5">(MHS) Kuesioner Perkuliahan - Get List Pertanyaan By Kelas Kuliah Id</h5>
    <hr>
    <p>
        Kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-info">get</span> ke <span class="badge bg-dark">/kuesioner/mahasiswa/perkuliahan/pertanyaan?kelas_kuliah_id={nilai_kelas_kuliah_id}</span>, ganti <b>nilai_kelas_kuliah_id</b> dengan nilai <b>kelas_kuliah_id</b> yang ada pada response get list matkul seperti contoh response di atas, sehingga menjadi <span class="badge bg-dark">/kuesioner/mahasiswa/perkuliahan/pertanyaan?kelas_kuliah_id=2164</span>.
    </p>
    <p>
        Jika mahasiswa telah mengisi kuesioner untuk matkul di kelas kuliah tersebut maka tidak akan mendapatkan response berisi daftar pertanyaan, jika belum pernah mengisi maka akan mendapatkan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kuesioner": {
            "kuesioner_perkuliahan_id": 1,
            "kelas_kuliah_id": 2164,
            "tahun_id": 280,
            "mk_id": 114,
            "pengajar_id": 573,
            "kd_mk": "IF1507",
            "nm_mk": "Kecerdasan Buatan",
            "kd_dosen": "IF054",
            "nm_dosen": "Mina Ismu Rahayu, M.T",
            "list_pertanyaan": {
                "Teknologi Pembelajaran": [
                    {
                        "pertanyaan_id": 1,
                        "jenis_pertanyaan_id": 1,
                        "kelompok_pertanyaan_id": 1,
                        "kd_jenis_pertanyaan": "P",
                        "jenis": "Perkuliahan",
                        "kelompok": "Teknologi Pembelajaran",
                        "pertanyaan": "Apakah seluruh proses perkuliahan telah memiliki aktivitas kelas pembelajaran online (Google Meet / Zoom / Discord) pada saat jam perkuliahan?"
                    }
                    // pertanyaan lain disembunyikan
                ],
                "Kegiatan Awal Pembelajaran": [
                    {
                        "pertanyaan_id": 5,
                        "jenis_pertanyaan_id": 1,
                        "kelompok_pertanyaan_id": 2,
                        "kd_jenis_pertanyaan": "P",
                        "jenis": "Perkuliahan",
                        "kelompok": "Kegiatan Awal Pembelajaran",
                        "pertanyaan": "Dosen menjelaskan silabus awal perkuliahan"
                    }
                    // pertanyaan lain disembunyikan
                ],
                "Pelaksanaan Pembelajaran": [
                    {
                        "pertanyaan_id": 11,
                        "jenis_pertanyaan_id": 1,
                        "kelompok_pertanyaan_id": 3,
                        "kd_jenis_pertanyaan": "P",
                        "jenis": "Perkuliahan",
                        "kelompok": "Pelaksanaan Pembelajaran",
                        "pertanyaan": "Dosen memberikan motivasi belajar kepada mahasiswa"
                    }
                    // pertanyaan lain disembunyikan
                ]
            }
        }
    }
}</code></pre>
    <h5 class="mt-5">(MHS) Kuesioner Perkuliahan - Kirim Jawaban</h5>
    <hr>
    <p>
        Kirimkan jawaban untuk setiap pertanyaan yang diberikan ke <span class="badge bg-dark">/kuesioner/mahasiswa/perkuliahan/pertanyaan/kirim-jawaban</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>, dengan menggunakan format payload body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kuesioner_perkuliahan_id": 1,
    "kelas_kuliah_id": 2164,
    "list_jawaban": [
        {
            "pertanyaan_id": 1,
            "jawaban": "SS"
        },
        {
            "pertanyaan_id": 2,
            "jawaban": "SS"
        },
        {
            "pertanyaan_id": 3,
            "jawaban": "SS"
        }
        ,
        {
            "pertanyaan_id": 4,
            "jawaban": "S"
        }
        // jawaban lain disembunyikan
    ]
}</code></pre>
    <p>
        Nilai <b>pertanyaan_id</b> didapatkan dari list pertanyaan yang ada pada response saat get pertanyaan untuk kuesioner perkuliahan dan nilai <b>jawaban</b> merupakan kode point yang dipilih oleh mahasiswa, nilai kode point tersebut didapat dari API untuk get daftar point pada property <b>kd_point</b>.
    </p>
    <p>
        Semua pertanyaan yang tersedia haruslah dijawab. Apabila berhasil mengirim jawaban maka API akan memberikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Jawaban berhasil dikirim",
    "data": {
        "kuesioner": {
            "kuesioner_perkuliahan_mahasiswa_id": 1
        }
    }
}</code></pre>
    <p>
        Simpanlah nilai <b>kuesioner_perkuliahan_mahasiswa_id</b> pada response yang diberikan dengan baik karena akan diperlukan saat mengirim saran.
    </p>

    <h5 class="mt-5">(MHS) Kuesioner Perkuliahan - Kirim Saran</h5>
    <hr>
    <p>
        Setelah berhasil mengirim jawaban, mahasiswa dapat mengirim saran untuk matakuliah tersebut. Kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-info">post</span> ke <span class="badge bg-dark">/kuesioner/mahasiswa/perkuliahan/pertanyaan/kirim-saran</span> dengan menyertakan payload body seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kuesioner_perkuliahan_mahasiswa_id": 1,
    "saran": "Test isi saran"
}</code></pre>
    <p>
        Isilah <b>kuesioner_perkuliahan_mahasiswa_id</b> dengan nilai yang didapat pada response di kirim jawaban kuesioner perkuliahan. Jika saran berhasil dikirim maka akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Saran berhasil dikirim"
}</code></pre>

    {{-- Mahasiswa - Kuesioner Kegiatan --}}
    <h5 class="mt-5">(MHS) Kuesioner Kegiatan - Get List Kegiatan</h5>
    <hr>
    <p>
        Kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-info">get</span> ke <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/list</span>. Response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kuesioner_kegiatan": [
            {
                "kuesioner_kegiatan_id": 1,
                "tahun": 2024,
                "tanggal_mulai": "2024-01-19",
                "tanggal_akhir": "2024-02-20",
                "organisasi": "HIMA SISTEM INFORMASI",
                "kegiatan": "INSPIRA-SI SPORT",
                "sts_isi_kuesioner": false
            }
        ]
    }
}</code></pre>
    <p>
        Perhatikan nilai <b>sts_isi_kuesioner</b>, jika nilainya adalah false berarti belum pernah mengisi kuesioner tersebut, jika true berarti sudah mengisi kuesioner.
    </p>

    <h5 class="mt-5">(MHS) Kuesioner Kegiatan - Get List Pertanyaan</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/pertanyaan</span> dan tambahkan query parameter <span class="badge bg-secondary">kuesioner_kegiatan_id</span> menggunakan HTTP method <span class="badge bg-info">get</span>. Contohnya menjadi seperti ini, <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/pertanyaan?kuesioner_kegiatan_id=1</span>.
    </p>
    <p>
        Apabila mahasiswa belum pernah mengisi kuesioner maka pertanyaan akan berhasil didapat dalam response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kuesioner": {
            "kuesioner_kegiatan_id": 1,
            "tahun": 2024,
            "tanggal_mulai": "2024-01-19",
            "tanggal_akhir": "2024-02-20",
            "organisasi": "HIMA SISTEM INFORMASI",
            "kegiatan": "INSPIRA-SI SPORT",
            "list_pertanyaan": {
                "Kepuasan Mahasiswa": [
                    {
                        "pertanyaan_id": 23,
                        "jenis_pertanyaan_id": 2,
                        "kelompok_pertanyaan_id": 4,
                        "kd_jenis_pertanyaan": "K",
                        "jenis": "Kegiatan Kampus",
                        "kelompok": "Kepuasan Mahasiswa",
                        "pertanyaan": "Apakah anda setuju kegiatan ini bermanfaat bagi anda?"
                    },
                    // pertanyaan lain disembunyikan
                ]
            }
        }
    }
}</code></pre>

    <h5 class="mt-5">(MHS) Kuesioner Kegiatan - Kirim Jawaban</h5>
    <hr>
    <p>
        Kirimkan jawaban dalam format JSON yang disimpan sebagai payload body seperti contoh di bawah, kemudian kirimkan ke <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/pertanyaan/kirim-jawaban</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kuesioner_kegiatan_id": 1,
    "list_jawaban": [
        {
            "pertanyaan_id":23,
            "jawaban": "S"
        },
        {
            "pertanyaan_id":24,
            "jawaban": "N"
        },
        // jawaban lain disembunyikan
    ]
}</code></pre>

    <h5 class="mt-5">(MHS) Kuesioner Kegiatan - Kirim Saran</h5>
    <hr>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/pertanyaan/kirim-saran</span> dengan contoh payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kuesioner_kegiatan_mahasiswa_id": 2,
    "saran": "Test isi saran kuesioner kegiatan"
}</code></pre>

    {{-- Admin - kuesioner perkuliahan --}}
    <h5 class="mt-5">(ADM) Kuesioner Perkuliahan - Get Tahun Ajaran</h5>
    <hr>
    <p>
        Kirim permintaan ke <span class="badge bg-dark">/kuesioner/tahun-ajaran</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil API akan memberikan response berisi list tahun ajaran aktif seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "tahun_ajaran": [
            {
                "tahun_id": 280,
                "jur_id": 23,
                "tahun": 2022,
                "smt": 1,
                "jns_mhs": "R",
                "kd_kampus": "A",
                "sts_ta": "B",
                "uraian": "IFS1 / 2022 / Ganjil / Reguler",
                "ket_smt": "Ganjil",
                "ket_jns_mhs": "Reguler",
                "detail_jurusan": {
                    "jur_id": 23,
                    "fak_id": 0,
                    "kd_jur": "12",
                    "nama_jurusan": "S1 - TEKNIK INFORMATIKA",
                    "nm_singkat": "IFS1 ",
                    "prodi": "S1",
                    "k_aktif": true
                },
                "detail_kampus": {
                    "kd_kampus": "A",
                    "lokasi": "Kampus Utama",
                    "alamat": "Cikutra"
                },
                "kuesioner_open": true
            }
            // tahun ajaran aktif lainnya disembunyikan
        ]
    }
}</code></pre>
    <p>
        Perhatikan nilai <b>kuesioner_open</b>, jika isinya adalah true maka kuesioner perkuliahan pada tahun tersebut telah dibuka, artinya setiap mahasiswa yang berada pada tahun ajaran tersebut sudah dapat mengisi kuesioner setiap matkul yang ada pada tahun ajaran itu. Dan jika isinya adalah false, berarti kuesioner perkuliahan pada tahun ajaran tersebut belum dibuka dan mahasiswa tidak dapat mengisi kuesioner.
    </p>

    <h5 class="mt-5">(ADM) Kuesioner Perkuliahan - Get Matkul Berdasarkan Tahun Ajaran</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/perkuliahan</span> dan tambahkan query parameter <span class="badge bg-secondary">tahun_id</span>, <span class="badge bg-secondary">jns_mhs</span>, dan <span class="badge bg-secondary">kd_kampus</span>. Nilai-nilai query parameter tersebut diperoleh dari tahun ajaran yang tersedia pada response get tahun ajaran untuk kuesioner. Contoh penggunaannya menjadi seperti <span class="badge bg-dark">/kuesioner/perkuliahan?tahun_id=280&jns_mhs=R&kd_kampus=A</span>, jika permintaan berhasil API akan memberikan response seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "matakuliah": [
            {
                "mk_id": 81,
                "kur_id": 83,
                "jur_id": 23,
                "tahun_id": 280,
                "kd_mk": "IF1101",
                "nm_mk": "Fisika 1",
                "semester": 1,
                "sks": 2,
                "kd_kampus": "A",
                "jns_mhs": "R",
                "detail_kuesioner": {
                    "tahun_id": 280,
                    "kelas_kuliah_ids": "2146",
                    "total_mahasiswa": 29,
                    "kelas_kuliah": "A",
                    "sts_open": false
                },
                "detail_dosen": {
                    "dosen_id": 581,
                    "kd_dosen": "IF062",
                    "nm_dosen": "KOESHANDOYO ARIONO,",
                    "gelar": "S.T"
                }
            },
            {
                "mk_id": 82,
                "kur_id": 83,
                "jur_id": 23,
                "tahun_id": 280,
                "kd_mk": "IF1102",
                "nm_mk": "Pengantar Teknik Informatika",
                "semester": 1,
                "sks": 2,
                "kd_kampus": "A",
                "jns_mhs": "R",
                "detail_kuesioner": {
                    "tahun_id": 280,
                    "kelas_kuliah_ids": "2147",
                    "total_mahasiswa": 26,
                    "kelas_kuliah": "A",
                    "sts_open": false
                },
                "detail_dosen": {
                    "dosen_id": 706,
                    "kd_dosen": "KA   ",
                    "nm_dosen": "Khoirida Aelani, M.T.",
                    "gelar": "M.T"
                }
            }
            // matkul lain disembunyikan
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Kuesioner Perkuliahan - Buka Kuesioner</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/perkuliahan/open</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload dalam body dengan format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tahun_id": 280
}</code></pre>

    <h5 class="mt-5">(ADM) Kuesioner Kegiatan - Add Kuesioner</h5>
    <hr>
    <p>
        Berdasarkan hasil diskusi, bahwa kegiatan kampus tidak berdasarkan pada tahun ajaran dan belum terdapat tabel yang menyimpan data kegiatan tersebut. Sehingga untuk membuka kuesionernya perlu ditambahkan data kegiatan kampus terlebih dahulu.
    </p>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/kegiatan/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span> dan sertakan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "tanggal_mulai": "19-01-2024",
    "tanggal_akhir": "20-02-2024",
    "organisasi": "HIMA SISTEM INFORMASI",
    "kegiatan": "INSPIRA-SI SPORT"
}</code></pre>

    <h5 class="mt-5">(ADM) Kuesioner Kegiatan - Get List Kuesioner</h5>
    <hr>
    <p>
        Untuk mendapatkan list kuesioner kegiatan yang telah ada, kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/kegiatan/list</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kuesioner_kegiatan": [
            {
                "kuesioner_kegiatan_id": 1,
                "tahun": 2024,
                "tanggal_mulai": "2024-01-19",
                "tanggal_akhir": "2024-02-20",
                "organisasi": "HIMA SISTEM INFORMASI",
                "kegiatan": "INSPIRA-SI SPORT"
            },
            // kuesioner kegiatan lain disembunyikan
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Get List Jenis Pertanyaan</h5>
    <hr>
    <p>
        Untuk melihat jenis pertanyaan yang tersedia, kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/pertanyaan/jenis</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jenis_pertanyaan": [
            {
                "jenis_pertanyaan_id": 1,
                "nama": "Perkuliahan",
                "kd_jenis_pertanyaan": "P"
            },
            {
                "jenis_pertanyaan_id": 2,
                "nama": "Kegiatan Kampus",
                "kd_jenis_pertanyaan": "K"
            }
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Get List Kelompok Pertanyaan</h5>
    <hr>
    <p>
        Setelah jenis, pertanyaan-pertanyaan yang ada dikelompokkan lagi menjadi beberapa bagian. Untuk melihat terdapat kelompok pertanyaan apa saja, kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/pertanyaan/kelompok</span> dan tambahkan query parameter <span class="badge bg-secondary">jenis_id</span>. Query parameter <span class="badge bg-secondary">jenis_id</span> didapat dari list jenis pertanyaan. Contoh lengkapnya adalah seperti <span class="badge bg-dark">/kuesioner/pertanyaan/kelompok?jenis_id=1</span>. Response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kelompok_pertanyaan": [
            {
                "kelompok_pertanyaan_id": 1,
                "jenis_pertanyaan_id": 1,
                "jenis": "Perkuliahan",
                "nama": "Teknologi Pembelajaran"
            },
            {
                "kelompok_pertanyaan_id": 2,
                "jenis_pertanyaan_id": 1,
                "jenis": "Perkuliahan",
                "nama": "Kegiatan Awal Pembelajaran"
            },
            {
                "kelompok_pertanyaan_id": 3,
                "jenis_pertanyaan_id": 1,
                "jenis": "Perkuliahan",
                "nama": "Pelaksanaan Pembelajaran"
            }
        ]
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Get List Pertanyaan</h5>
    <hr>
    <p>
        Untuk melihat list pertanyaan yang tersedia, kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/pertanyaan</span> dengan menyertakan query parameter <span class="badge bg-secondary">jenis_id</span>. Kirimkan menggunakan HTTP method <span class="badge bg-info">get</span>. Contohnya, <span class="badge bg-dark">/kuesioner/pertanyaan?jenis_id=2</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "list_pertanyaan": {
            "Kepuasan Mahasiswa": [
                {
                    "pertanyaan_id": 23,
                    "jenis_pertanyaan_id": 2,
                    "kelompok_pertanyaan_id": 4,
                    "kd_jenis_pertanyaan": "K",
                    "jenis": "Kegiatan Kampus",
                    "kelompok": "Kepuasan Mahasiswa",
                    "pertanyaan": "Apakah anda setuju kegiatan ini bermanfaat bagi anda?"
                },
                // pertanyaan lain disembunyikan
            ]
        }
    }
}</code></pre>

    <h5 class="mt-5">(ADM) Get Detail Pertanyaan</h5>
    <hr>
    <p>
        Untuk mendapatkan detail pertanyaan tertentu, kirimkan permintaan dengan menggunakan HTTP method <span class="badge bg-info">get</span> ke <span class="badge bg-dark">/kuesioner/pertanyaan/detail/{pertanyaan_id}</span>. Ganti <b>{pertanyaan_id}</b> dengan nilai <b>pertanyaan_id</b> yang didapat saat get list pertanyaan. Seperti <span class="badge bg-dark">/kuesioner/pertanyaan/detail/23</span>, hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pertanyaan": {
            "pertanyaan_id": 23,
            "jenis_pertanyaan_id": 2,
            "kelompok_pertanyaan_id": 4,
            "kd_jenis_pertanyaan": "K",
            "jenis": "Kegiatan Kampus",
            "kelompok": "Kepuasan Mahasiswa",
            "pertanyaan": "Apakah anda setuju kegiatan ini bermanfaat bagi anda?"
        }
    }
}</code></pre>


    <h5 class="mt-5">(ADM) Add Pertanyaan</h5>
    <hr>
    <p>
        Untuk menambahkan pertanyaan baru, kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/pertanyaan/add</span> dengan menggunakan HTTP method <span class="badge bg-info">post</span>. Kirimkan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "jenis_pertanyaan_id": 2,
    "kelompok_pertanyaan_id": 4,
    "pertanyaan": "Seberapa setuju Anda bahwa kegiatan kampus memberikan manfaat yang sebanding dengan waktu dan energi yang Anda investasikan?"
}</code></pre>

    <h5 class="mt-5">(ADM) Edit Pertanyaan</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/pertanyaan/edit</span> dengan menggunakan HTTP method <span class="badge bg-info">put</span>. Kirimkan payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "pertanyaan_id": 33,
    "jenis_pertanyaan_id": 2,
    "kelompok_pertanyaan_id": 4,
    "pertanyaan": "Ada pertanyaan buat diedit gak?"
}</code></pre>
</div>
@endsection
