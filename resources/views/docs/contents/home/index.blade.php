@extends('docs.template.index')
@section('docs_contents')
<div class="m-2">
    @php
        $apiBaseURL = (string) config('app.url') . 'api';
    @endphp
    <p>
        <b>API Base URL:</b> {{ $apiBaseURL }}
    </p>
    <hr>
    <ul class="nav nav-pills">
        <li class="nav-item">
            <div class="nav-link tab-link" style="cursor: pointer" data-tab="update">
                Log Update
            </div>
        </li>
        <li class="nav-item">
            <div class="nav-link tab-link" style="cursor: pointer" data-tab="penggunaan">
                Cara Penggunaan
            </div>
        </li>
        <li class="nav-item">
            <div class="nav-link tab-link" style="cursor: pointer" data-tab="lainnya">
                Lainnya
            </div>
        </li>
    </ul>
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

            console.log(tab);

            $.ajax({
                url: '/docs/api/home/tabs/' + tab,
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

        $('[data-tab="update"]').click();
    });
</script>
@endsection
