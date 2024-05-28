<section>
    <h5 class="mt-4 mb-3 fw-bold">(MHS) Kuesioner Perkuliahan - Get List Matkul Aktif</h5>
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
</section>

    {{-- Get pertanyaan kuesioner matkul --}}
    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Perkuliahan - Get List Pertanyaan By Kelas Kuliah Id</h5>
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

<section>
    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Perkuliahan - Kirim Jawaban</h5>
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
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Perkuliahan - Kirim Saran</h5>
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
</section>

<section>
    <h5 class="mt-5 mb-3 fw-bold">(MHS) Get Pilihan Jawaban</h5>
    <p>
        Untuk melihat daftar pilihan jawaban tersedia yang digunakan untuk input tipe radio. Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/pertanyaan/pilihan-jawaban</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pilihan_jawaban": [
            {
                "point_id": 5,
                "kd_point": "SS",
                "ket_point": "Sangat Setuju",
                "mutu": 5
            },
            {
                "point_id": 4,
                "kd_point": "S",
                "ket_point": "Setuju",
                "mutu": 4
            },
            {
                "point_id": 3,
                "kd_point": "N",
                "ket_point": "Netral",
                "mutu": 3
            },
            {
                "point_id": 2,
                "kd_point": "TS",
                "ket_point": "Tidak Setuju",
                "mutu": 2
            },
            {
                "point_id": 1,
                "kd_point": "STS",
                "ket_point": "Sangat Tidak Setuju",
                "mutu": 1
            }
        ]
    }
}</code></pre>
</section>

    {{-- Mahasiswa - Kuesioner Kegiatan --}}
   <!-- <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Kegiatan - Get List Kegiatan</h5>
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

    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Kegiatan - Get List Pertanyaan</h5>
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

    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Kegiatan - Kirim Jawaban</h5>
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

    <h5 class="mt-5 mb-3 fw-bold">(MHS) Kuesioner Kegiatan - Kirim Saran</h5>
    <p>
        Gunakan HTTP method <span class="badge bg-info">post</span> dan kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/kegiatan/pertanyaan/kirim-saran</span> dengan contoh payload seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "kuesioner_kegiatan_mahasiswa_id": 2,
    "saran": "Test isi saran kuesioner kegiatan"
}</code></pre>

    <h5 class="mt-5 mb-3 fw-bold">(MHS) Get All Pilihan Jawaban</h5>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/kuesioner/mahasiswa/pertanyaan/pilihan-jawaban</span> dengan HTTP method <span class="badge bg-info">get</span> untuk mendapatkan list pilihan jawaban kuesioner yang nantinya akan digunakan untuk memilih jawaban setiap pertanyaan. Response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pilihan_jawaban": [
            {
                "point_id": 1,
                "kd_point": "N",
                "ket_point": "Netral",
                "mutu": 3
            },
            {
                "point_id": 2,
                "kd_point": "STS",
                "ket_point": "Sangat Tidak Setuju",
                "mutu": 1
            },
            {
                "point_id": 3,
                "kd_point": "TS",
                "ket_point": "Tidak Setuju",
                "mutu": 2
            },
            {
                "point_id": 4,
                "kd_point": "S",
                "ket_point": "Setuju",
                "mutu": 4
            },
            {
                "point_id": 5,
                "kd_point": "SS",
                "ket_point": "Sangat Setuju",
                "mutu": 5
            }
        ]
    }
}</code></pre>
