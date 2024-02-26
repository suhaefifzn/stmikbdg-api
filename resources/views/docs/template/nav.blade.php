<nav id="navbar-example2" class="navbar bg-dark-subtle px-3 mb-3 px-lg-5 fixed-top">

    <div class="d-flex align-items-center">
        <a class="navbar-brand" href="/">
            <span class="fw-bold fs-5">STMIK Bandung</span>
            <div style="font-size: 12px;">
                API Documentation
            </div>
        </a>
        {{-- Logout - Mobile --}}
        <div class="d-block d-md-none">
            <a class="ms-auto text-decoration-none btn btn-sm btn-primary" href="/docs/api/logout">Logout</a>
        </div>
    </div>

    <ul class="nav nav-pills">
        <li class="nav-item">
            <a class="nav-link" href="/docs/api/authentications">Auth</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="/docs/api/users">Users</a>
        </li>

        {{-- Menu untuk android --}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Android</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/docs/api/krs-mahasiswa">KRS - MHS</a></li>
                <li><a class="dropdown-item" href="/docs/api/krs-dosen-wali">KRS - DSN</a></li>
            </ul>
        </li>

        {{-- Menu untuk web --}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Web</a>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="/docs/api/kuesioner">Kuesioner</a></li>
                <li><a class="dropdown-item" href="/docs/api/data-mahasiswa">Data Mahasiswa</a></li>
                <li><a class="dropdown-item" href="/docs/api/acl">ACL (For Admin)</a></li>
            </ul>
        </li>

        {{-- Logout --}}
        <div class="d-none d-md-block align-self-center">
            <li class="nav-item btn btn-sm btn-primary ms-3">
                <a class="nav-link p-0 text-white" href="/docs/api/logout">Logout</a>
            </li>
        </div>
    </ul>
</nav>
