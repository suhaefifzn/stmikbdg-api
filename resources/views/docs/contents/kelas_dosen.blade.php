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
            "Rabu": [
                {
                    "kelas_kuliah_id": 3318,
                    "tahun_id": 350,
                    "jur_id": 23,
                    "mk_id": 137,
                    "join_kelas_kuliah_id": 3349,
                    "kjoin_kelas": true,
                    "kelas_kuliah": "A",
                    "jns_mhs": "K",
                    "sts_kelas": "B",
                    "pengajar_id": 573,
                    "join_jur": "MIS1",
                    "dosen": {
                        "dosen_id": 573,
                        "kd_dosen": "IF054",
                        "nm_dosen": "Mina Ismu Rahayu, M.T",
                        "gelar": "M.T"
                    },
                    "matakuliah": {
                        "mk_id": 137,
                        "kur_id": 83,
                        "kd_mk": "KU1813",
                        "nm_mk": "HAKI &Etika Profesi",
                        "semester": 8,
                        "sks": 2,
                        "sts_mk": "A",
                        "smt": 2,
                        "kd_kur": "IFS120"
                    },
                    "jadwal": {
                        "kelas_kuliah_id": 3349,
                        "mk_id": 62,
                        "dosen_id": 573,
                        "tanggal": "2024-03-13",
                        "jns_pert": "T1",
                        "jam": "19:45-21:15",
                        "kd_ruang": "",
                        "nm_hari": "Rabu",
                        "tanggal_lokal": "13 Maret 2024"
                    },
                    "kelas_dibuka": false
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
    <h5 class="mt-4">(DSN) Buka Kelas Kuliah</h5>
    <hr>
    <p>
        Kirimkan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/open/{kelas_kuliah_id}</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>kelas_kuliah_id</b> dengan nilai id dari kelas kuliah yang akan dibuka. Kelas hanya dapat dibuka pada tanggal yang sama dengan yang telah ditentukan. Misalnya <span class="badge bg-dark">/kelas-kuliah/dosen/open/3251</span>.
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "pertemuan": {
            "kelas_kuliah_id": "3251-3275",
            "tanggal": "2024-03-14",
            "presensi": {
                "qrcode_value": "http://stmikbdg-api.test/api/kelas-kuliah/mahasiswa/presensi/qrcode?kelas=3251-3275&pin=351670",
                "pin": "351670"
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
        Kirimkan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/open/{kelas_kuliah_id}/presensi</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>, ganti <b>kelas_kuliah_id</b> dengan id kelas kuliah yang sedang dibuka. Misalnya, <span class="badge bg-dark">/kelas-kuliah/dosen/open/3251/presensi</span>. Hasilnya:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "jumlah_mahasiswa": 22,
        "jumlah_mahasiswa_hadir": 1,
        "jumlah_mahasiswa_belum_hadir": 21,
        "presensi_mahasiswa": [
            {
                "pertemuan_id": 42,
                "nim": "3220701",
                "nm_mhs": "JAYA LAZUARDI",
                "masuk": "2024-03-14 17:01:42"
            }
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
        Lakukan request ke <span class="badge bg-dark">/kelas-kuliah/dosen/close/{kelas_kuliah_id}</span> menggunakan HTTP method <span class="badge bg-info">info</span>, ganti <b>kelas_kuliah_id</b> dengan id kelas kuliah yang akan ditutup. Misalnya, <span class="badge bg-dark">/kelas-kuliah/dosen/close/3251</span> jika berhasil akan memberikan response:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "message": "Kelas kuliah berhasil ditutup",
    "data": {
        "jumlah_mahasiswa": 22,
        "jumlah_mahasiswa_hadir": 1,
        "jumlah_mahasiswa_belum_hadir": 21,
        "presensi_mahasiswa": [
            {
                "pertemuan_id": 42,
                "nim": "3220008",
                "nm_mhs": "MUHAMMAD RAIHAN AL GHIFARI",
                "masuk": null
            },
            {
                "pertemuan_id": 42,
                "nim": "3220701",
                "nm_mhs": "JAYA LAZUARDI",
                "masuk": "2024-03-14 17:01:42"
            }
            // mahasiswa lain disembunyikan
        ]
    }
}</code></pre>
    <p>
        Dalam response tersebut terdapat daftar presensi mahasiswa dan setelah kelas ditutup mahasiswa tidak bisa mengirim PIN presensi.
    </p>
</div>
@endsection
