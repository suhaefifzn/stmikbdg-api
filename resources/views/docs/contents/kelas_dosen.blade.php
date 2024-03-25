@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kelas Kuliah - Sisi Dosen</h4>
<hr>
<p>
    Route-route API pada halaman ini digunakan untuk mendapatkan jadwal kuliah untuk dosen, daftar kelas kuliah yang ditampilkan berdasarkan semua tahun ajan aktif. Selain melihat jadwal kuliah, digunakan juga untuk membuka kelas yang menghasilkan kode berupa PIN sebanyak 6 digit yang nantinya digunakan oleh mahasiswa untuk mengisi kehadiran.
</p>
<div class="m-2">
    <h5 class="mt-4">(DSN) Get Daftar Kelas Kuliah</h5>
    <hr>
    <p>
        Lakukan permintaan ke <span class="badge bg-dark">/kelas-kuliah/dosen</span> menggunakan HTTP method <span class="badge bg-info">get</span>. Jika berhasil maka akan memberikan response dalam format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "kelas_kuliah": {
            "Selasa": [
                {
                    "kelas_kuliah_id": 3268,
                    "tahun_id": 349,
                    "jur_id": 27,
                    "mk_id": 62,
                    "join_kelas_kuliah_id": null,
                    "kjoin_kelas": false,
                    "kelas_kuliah": "A",
                    "jns_mhs": "R",
                    "sts_kelas": "B",
                    "pengajar_id": 562,
                    "join_jur": null,
                    "riwayat_pertemuan": [
                        {
                            "pertemuan_id": 79,
                            "jns_pert": "T1",
                            "create_time": "2024-03-19 13:35:06"
                        }
                    ],
                    "jadwal": {
                        "kelas_kuliah_id": 3268,
                        "mk_id": 62,
                        "dosen_id": 562,
                        "tanggal": "2024-03-19",
                        "jns_pert": "T1",
                        "jam": "09:30-11:10",
                        "kd_ruang": "31",
                        "nm_hari": "Selasa",
                        "tanggal_lokal": "19 Maret 2024"
                    },
                    "dosen": {
                        "dosen_id": 562,
                        "kd_dosen": "IF043",
                        "nm_dosen": "Dani Pradana Kartaputra, S.Si., M.T",
                        "gelar": ", S.Si., M.T"
                    },
                    "kelas_dibuka": false,
                    "matakuliah": {
                        "mk_id": 62,
                        "kur_id": 82,
                        "kd_mk": "KU1813",
                        "nm_mk": "HAKI &Etika Profesi",
                        "semester": 8,
                        "sks": 2,
                        "sts_mk": "A",
                        "smt": 2,
                        "kd_kur": "MIS120"
                    }
                }
                // jadwal lain disembunyikan
            ]
        }
    }
}</code></pre>
    <p>
        Jika terdapat properti <b>Unknown</b> menandakan bahwa kelas kuliah yang ada di dalamnya belum memiliki jadwal, hal tersebut dapat dilihat juga pada nilai <b>jadwal</b> yang masih berisi <b>null</b>.
    </p>
    <p>
        Jika nilai <b>kjoin_kelas</b> adalah true, maka kelas kuliah tersebut digabungkan dengan kelas kuliah lain, kelasnya dapat dilihat pada nilai <b>join_kelas_kuliah_id</b>. Mengikuti tampilan pada aplikasi yang telah ada, maka wajib menampilkan nilai <b>join_jur</b> untuk menandakan bahwa kelas kuliah tersebut digabung dengan kelas lain.
    </p>
    <p>
        Perhatikan juga nilai <b>jns_mhs</b>, jika nilainya 'R' jadwal tersebut untuk kelas mahasiswa reguler, 'E' untuk eksekutif, dan 'K' untuk kelas mahasiswa karyawan.
    </p>
    <p>
        Untuk mempermudah hasil response, Anda dapat memfilter jadwal berdasarkan hari tertentu saja. Tambahkan query parameter <span class="badge bg-secondary">hari</span> dan isi dengan nama hari dalam bahasa Indonesia. Misalnya ingin mendapatkan jadwal kelas kuliah di hari Rabu saja, maka kirim request ke <span class="badge bg-dark">/kelas-kuliah/dosen?hari=rabu</span>. Maka response akan menampilkan jadwal kuliah di hari Rabu saja.
    </p>
    <p>
        Nilai <b>kelas_dibuka</b> akan berubah menjadi true jika kelas yang dipilih dibuka oleh Dosen, setelah dibuka jangan lupa untuk mengirim request untuk mendapatkan PIN. Setelah PIN didapat, maka Mahasiswa dapat mengisi presensi dengan mengirimkan PIN yang sama dengan yang didapat oleh Dosen nantinya.
    </p>
    <h5 class="mt-4">(DSN) Buka Kelas Kuliah - Single PIN</h5>
    <hr>
    <p>
        Kirimkan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/open/{kelas_kuliah_id}</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>kelas_kuliah_id</b> dengan nilai id dari kelas kuliah yang akan dibuka. Kelas hanya dapat dibuka pada tanggal yang sama dengan yang telah ditentukan. Misalnya <span class="badge bg-dark">/kelas-kuliah/dosen/open/3268</span>.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pertemuan": {
            "kelas_kuliah_id_dibuka": "3294-3268",
            "tanggal": "2024-03-19",
            "presensi": {
                "qrcode_value": "http://stmikbdg-api.test/api/kelas-kuliah/mahasiswa/presensi/qrcode?kelas=3294-3268&pin=758496",
                "pin": "758496"
            }
        }
    }
}</code></pre>
    <p>
        Apabila kelas berhasil dibuka maka API akan memberikan response yang berisi PIN untuk kehadiran mahasiswa. Nilai pada <b>qrcode_value</b> digunakan sebagai nilai yang disimpan dalam gambar QR yang dibuat di Front-End dan digunakan pada sisi mahasiswa, jadi saat mahasiswa berhasil melakukan scan QR Code kirimlah request pada URL yang menjadi nilai pada <b>qrcode_value</b> dan API akan memberikan response yang memberikan pesan PIN berhasil dikirim atau tidak. Saat ini pengisian presensi hanya mendukung jenis single PIN saja, artinya semua mahasiswa pada kelas kuliah tersebut mengisi kehadiran menggunakan PIN yang sama.
    </p>
    <h5 class="mt-4">(DSN) Get Daftar Kehadiran Mahasiswa Saat Kelas Dibuka</h5>
    <hr>
    <p>
        Kirimkan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/open/{kelas_kuliah_id}/presensi</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>kelas_kuliah_id</b> dengan id kelas kuliah yang sedang dibuka. Misalnya, <span class="badge bg-dark">/kelas-kuliah/dosen/open/3268/presensi</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jumlah_mahasiswa": 59,
        "jumlah_mahasiswa_hadir": 1,
        "jumlah_mahasiswa_belum_hadir": 59,
        "presensi_mahasiswa": [
            {
                "pertemuan_id": 80,
                "mhs_id": 15534,
                "nim": "1220001",
                "nm_mhs": "SUHAEFI FAUZIAN",
                "masuk": "2024-03-19 14:59:13"
            },
            // mahasiswa lain disembunyikan
        ]
    }
}</code></pre>
    <p>
        Jika mahasiswa telah berhasil mengirim PIN presensi maka nilai <b>masuk</b> akan terisi dengan waktu mahasiswa tersebut mengirim PIN. Jika nilainya null maka mahasiswa tersebut belum mengirim PIN presensi atau tidak hadir.
    </p>
    <p>
        Untuk memantau kehadiran secara berkala, pada sisi Front-End gunakanlah metode polling client atau mengirim request secara berkala untuk memantau nilainya, dan saat terdapat nilai yang berbeda maka perbaharuilah bagian yang termasuk nilai itu saja, gunakanlah nilai yang unik dari setiap mahasiswa untuk mengubah bagian tersebut (<b>nim</b>).  Misalnya, terus lakukan request setiap 2 detik dan lakukan pengecekan terhadap nilai <b>masuk</b> pada response yang diberikan dengan response sebelumnya atau pada response yang telah ditampilkan ke pengguna, apabila nilai pada response berbeda maka perbaharuilah. Proses tersebut akan terus berlangsung hingga dosen menutup kelasnya.
    </p>
    <h5 class="mt-4">(DSN) Tutup Kelas Kuliah</h5>
    <hr>
    <p>
        Lakukan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/close/{kelas_kuliah_id}</span> menggunakan HTTP method <span class="badge bg-info">info</span>, ganti <b>kelas_kuliah_id</b> dengan id kelas kuliah yang akan ditutup. Misalnya, <span class="badge bg-dark">/kelas-kuliah/dosen/close/3268</span> jika berhasil akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Kelas kuliah berhasil ditutup",
    "data": {
        "jumlah_mahasiswa": 59,
        "jumlah_mahasiswa_hadir": 1,
        "jumlah_mahasiswa_belum_hadir": 59,
        "presensi_mahasiswa": [
            {
                "pertemuan_id": 80,
                "mhs_id": 15534,
                "nim": "1220001",
                "nm_mhs": "SUHAEFI FAUZIAN",
                "masuk": "2024-03-19 14:59:13"
            },
            {
                "pertemuan_id": 80,
                "mhs_id": 15680,
                "nim": "1220313",
                "nm_mhs": "YOGA PRATAMA ",
                "masuk": null
            },
            // mahasiswa lain disembunyikan
        ]
    }
}</code></pre>
    <p>
        Dalam response tersebut terdapat daftar presensi mahasiswa dan setelah kelas ditutup mahasiswa tidak bisa mengirim PIN presensi.
    </p>

    {{-- Menghapus presensi mahasiswa --}}
    <h5 class="mt-4">(DSN) Hapus Kehadiran Mahasiswa Pada Pertemuan Kelas Kuliah</h5>
    <hr>
    <p>
        Dosen dapat menghapus status kehadiran mahasiswa pada pertemuan tertentu apabila kehadiran dari mahasiswa tersebut tidaklah valid, contohnya mahasiswa hanya mengirim PIN presensi tetapi tidak hadir di kelas. Untuk menghapusnya, kirimkam permintaan ke <span class="badge bg-dark">/kelas-kuliah/dosen/presensi-mahasiswa</span> dengan menggunakan HTTP method <span class="badge bg-info">delete</span> dan juga sertakan payload dalam body dengan format JSON seperti berikut:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "pertemuan_id": 80,
    "mhs_id": 15534
}</code></pre>
    <p>
        Jika berhasil akan memberikan response dengan status sukses seperti di bawah ini:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Berhasil menghapus presensi mahasiswa"
}</code></pre>
    <h5 class="mt-4">(DSN) Get Unique PIN per Mahasiswa untuk Presensi</h5>
    <hr>
    <p>
        Untuk mendapatkan PIN acak yang bersifat unik atau hanya berlaku untuk satu mahasiswa saja, hal pertama yang harus dilakukan adalah membuka kelas terlebih dahulu. Setelah kelas berhasil dibuka yang ditandai response berisi pertemuan dan presensi dengan tipe pin single, kemudian barulah tambahkan query parameter <b>unique_pin</b> dengan nilai <b>true</b> pada url yang sama untuk mendapatkan PIN unique. Sehingga url yang dikirim menjadi seperti <span class="badge bg-dark">/kelas-kuliah/dosen/open/3248?unique_pin=true</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pertemuan": {
            "kelas_kuliah_id_dibuka": "3248-3272",
            "tanggal": "2024-03-25",
            "presensi": {
                "qrcode_value": "http://stmikbdg-api.test/api/kelas-kuliah/mahasiswa/presensi/qrcode?kelas=3248-3272&pin=049314&unique_pin=true",
                "pin": "049314",
                "type_pin": "unique"
            }
        }
    }
}</code></pre>
    <p>
        Nilai <b>qrcode_value</b> dan <b>pin</b> saling berkaitan, maka pin hanya dapat digunakan satu mahasiswa saja. Pada sisi client gunakanlah metode polling request ke url yang digunakan untuk meminta unique PIN, jika nilai PIN yang diberikan pada response-nya masih sama, itu menandakan bahwa belum ada mahasiswa yang menggunakan PIN tersebut, jika nilai PIN pada response telah berubah berarti PIN sebelumnya telah digunakan oleh mahasiswa.
    </p>
    <p>
        Dengan mengetahui perubahan PIN pada proses sebelumnya, maka pada sisi client atau Front-End Anda dapat melanjutkan untuk mengirim permintaan daftar presensi mahasiswa apabila PIN telah berubah.
    </p>
</div>
@endsection
