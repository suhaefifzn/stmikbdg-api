@extends('docs.template.index')
@section('docs_contents')
<h4 class="fw-bold"><b>#</b> Kuesioner</h4>
<hr>
<div class="m-2">
    <ul class="nav nav-pills">
        <li class="nav-item">
            <div class="nav-link tab-link" style="cursor: pointer" data-tab="admin">Admin</div>
        </li>
        <li class="nav-item">
            <div class="nav-link tab-link" style="cursor: pointer" data-tab="mahasiswa">Mahasiswa</div>
        </li>
    </ul>
    <hr>
    <div class="alert alert-warning">
        <b>Note:</b> masih belum selesai.
    </div>
    <hr>
    <div id="tabContent"></div>
</div>

<script>
    $(document).ready(() => {
        $('.tab-link').removeClass('active');

        $('.tab-link').on('click', (e) => {
            e.preventDefault();
            const target = e.target;
            const tab = $(target).data('tab');

            $.ajax({
                url: '/docs/api/kuesioner/tabs/' + tab,
                type: 'GET',
                beforeSend: () => {
                    $('#tabContent').html('<p>Loading...</p>');
                    $('.tab-link').removeClass('active');
                },
                success: (response) => {
                    $(target).addClass('active');
                    $('#tabContent').html(response.content);

                    $('pre code').each(function(i, block) {
                        hljs.highlightBlock(block);
                    });
                },
                error: (xhr) => {
                    $('#tabContent').html('<p>Error loading content.</p>')
                }
            })
        });

        $('[data-tab="admin"]').click();
    });
</script>
@endsection
