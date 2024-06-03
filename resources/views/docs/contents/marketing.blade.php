@extends('docs.template.index')
@section('docs_contents')
<h4 class="fw-bold"><b>#</b> Marketing</h4>
<hr>
<div class="m-2">
    <h5 class="mt-5 fw-bold">(ADM) Get List Staf Marketing</h5>
    <hr>
    <p>
        Kirimkan permintaan ke <span class="badge bg-dark">/users?is_staff=true&job=is_marketing</span> dengan menggunakan HTTP method <span class="badge bg-info">get</span>. API akan memberikan response seperti:
    </p>
    <pre><code class="language-json bg-primary-subtle">{
    "status": "success",
    "data": {
        "users": [
            {
                "staff_id": 1,
                "user_id": 20,
                "nama": "Rizky Septian",
                "email": "rizky@simak.dev",
                "no_hp": "088802169536",
                "image": "http://stmikbdg-api.test/storage/users/images/college_student.png",
                "is_marketing": true
            }
        ]
    }
}</code></pre>
</div>
@endsection
