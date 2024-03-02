@extends('docs.template.index')
@section('docs_contents')
<h4 class="mt-4"><b>#</b> Kamus</h4>
<hr>
<p>
    <b>Last Updated:</b>  02-03-2024 WIB.
</p>
<p>
    Halaman ini digunakan untuk menjelaskan beberapa singkatan yang ada pada response API yang mungkin belum dimengerti.
</p>
<ul>
    <li><b>kd</b> - Setiap key atau nama properti yang diawali dengan kd memiliki arti sebagai kode. Misalnya kd_kampus, maksudnya adalah kode kampus.</li>
    <li><b>mhs</b> - Adalah singkatan untuk mahasiswa.</li>
    <li><b>jk</b> - Terdapat pada profil pengguna, yang berarti jenis kelamin. 'L' adalah laki-laki dan 'P' adalah perempuan.</li>
    <li><b>jur</b> - Singkatan untuk jurusan.</li>
    <li><b>jns</b> - Singkatan untuk jenis.</li>
    <li><b>tmp</b> - Singkatan untuk tempat.</li>
    <li><b>tgl</b> - Singkatan untuk tanggal.</li>
    <li><b>sts</b> - Singkatan untuk status.</li>
    <li><b>du_mulai</b> - Berisi waktu awal untuk pengajuan KRS dibuka.</li>
    <li><b>du_sampai</b> - Waktu akhir untuk pengajuan KRS.</li>
    <li><b>du_open</b> - Bernilai boolean, yang menentukan pengisian KRS dibuka atau tidak berdasarkan pada nilai du_mulai dan du_sampai.</li>
    <li><b>nmr</b> - Singkatan untuk nomor.</li>
    <li><b>mk</b> - Singkatan untuk matakuliah.</li>
    <li><b>nm</b> - Singkatan untuk untuk nama.</li>
    <li><b>kur</b> - Singkatan untuk jurusan.</li>
    <li><b>prev_page_url</b> - Berisi alamat yang mengarah ke halaman sebelumnya dari pagination.</li>
    <li><b>next_page_url</b> - Berisi alamat yang mengarah ke halaman berikutnya dari pagination.</li>
    <li><b>k</b> - Digunakan untuk keterangan.</li>
    <li><b>ket</b> - Digunakan untuk keterangan.</li>
    <li><b>jml</b> - Singkatan untuk jumlah.</li>
    <li><b>n</b> - Singkatan untuk nilai.</li>
    <li><b>pert</b> - Singkatan untuk pertemuan</li>
</ul>
<p>Selain itu terdapat singkatan untuk isi atau nilai dari properti yang dikategorikan akan sering digunakan, yaitu seperti nilai dari <b>sts_krs</b> atau status krs yang memiliki arti:</p>
<ul>
    <li><b>P</b> - Singkatan untuk 'Pengajuan' atau KRS sedang diajukan, pada tahap ini matakuliah yang dipilih tidak dapat diubah lagi karena sedang tahap pengajuan.</li>
    <li><b>D</b> - Singkatan untuk 'Draft'. KRS yang dengan status ini masih dapat mengubah daftar matakuliah. Juga, saat KRS ditolak akan berubah ke status ini.</li>
    <li><b>S</b> - Singkatan untuk 'Sah' atau disetujui. KRS dengan status ini telah disetujui dan tidak dapat diubah kecuali oleh dosen wali.</li>
</ul>
<p>
    Jika masih ada kebingungan atau yang belum dimengerti mengenai API bisa langsung ditanyakan dengan menghubungi kontak dari tim yang mengelola API atau Database (Back-End).
</p>
@endsection
