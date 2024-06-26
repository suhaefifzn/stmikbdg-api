<p>
@php
    $apiBaseURL = config('app.url') . 'api';
@endphp
</p>

<div class="alert alert-info">
    <span class="fw-bold fs-6">Cara Penggunaan:</span>
    <p>
        Base URL merupakan url utama dari API. Agar request yang dilakukan sesuai dengan yang dibutuhkan maka tambahkan dengan URI atau beberapa endpoint yang ada pada menu ini. Misalnya jika ingin melakukan login untuk meminta access token, maka alamat yang digunakan untuk meminta ke API menjadi <b>Base URL + endpoint</b>, sehingga url yang digunakan untuk login adalah <b>{{ $apiBaseURL }}/authentications</b>.
    </p>
    <p>
        Gunakan HTTP method yang sesuai untuk setiap akses yang diminta dan kirimkan payload jika diharuskan. HTTP method dan payload yang digunakan dapat dilihat pada masing-masing endpoint yang telah dikelompokkan sebagai menu pada navigasi di situs ini.
    </p>
    <p>
        Pada API ini hanya ada 4 HTTP method yang digunakan, yaitu <b>GET</b> untuk mendapatkan data, <b>POST</b> untuk mengirim data, <b>PUT</b> untuk mengubah data, <b>DELETE</b> untuk menghapus data.
    </p>
</div>
