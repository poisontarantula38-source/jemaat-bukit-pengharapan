<?php
$activePage = $activePage ?? '';
$pageTitle = $pageTitle ?? 'Jemaat Bukit Pengharapan';
$content = is_array($content ?? null) ? $content : [];
$navClass = static fn (string $page): string => $activePage === $page ? 'nav-link active' : 'nav-link';
$logoAsset = 'assets/images/logo-gpm-bukit-pengharapan.png';
$hasLogo = is_file(FCPATH . $logoAsset);
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Informasi Jemaat Bukit Pengharapan: profil, jadwal ibadah, warta, dan pelayanan.">
    <title><?= esc($pageTitle) ?> | Jemaat GPM Bukit Pengharapan</title>
    <link rel="icon" type="image/x-icon" href="<?= esc(base_url('favicon.ico?v=2')) ?>">
    <link rel="stylesheet" href="<?= esc(base_url('assets/vendor/bootstrap/bootstrap.min.css')) ?>">
    <style {csp-style-nonce}>
        :root {
            color-scheme: light;
            --page-bg: #FFFEFA;
            --surface-soft: #F5F1F6;
            --surface-blue: #E2EEF8;
            --brand-blue: #0d6efd;
            --brand-blue-dark: #0b5ed7;
            --brand-gold: #D8BD72;
            --text-main: #29232C;
            --text-muted: #5D5361;
            --line-soft: rgba(41, 35, 44, .12);
            --radius-card: 1rem;
            --radius-panel: 1.6rem;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 6rem;
        }

        @view-transition {
            navigation: auto;
        }

        ::view-transition-old(root) {
            animation: page-transition-out 160ms ease-out both;
        }

        ::view-transition-new(root) {
            animation: page-transition-in 320ms cubic-bezier(.2, .75, .25, 1) both;
        }

        @keyframes page-transition-out {
            to {
                opacity: 0;
                transform: translateY(-.35rem);
            }
        }

        @keyframes page-transition-in {
            from {
                opacity: 0;
                transform: translateY(.5rem);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            background: var(--page-bg);
            color: var(--text-main);
            font-family: Inter, "Segoe UI", sans-serif;
            font-size: 1rem;
            line-height: 1.65;
            text-rendering: optimizeLegibility;
        }

        p {
            text-wrap: pretty;
        }

        ::selection {
            background: var(--brand-blue);
            color: #fff;
        }

        a {
            text-underline-offset: .2em;
        }

        a:focus-visible,
        button:focus-visible {
            outline: 3px solid var(--brand-blue-dark);
            outline-offset: 3px;
            box-shadow: none;
        }

        .skip-link {
            position: fixed;
            z-index: 1100;
            top: .75rem;
            left: .75rem;
            transform: translateY(-160%);
            border-radius: .5rem;
            background: #fff;
            color: var(--brand-blue-dark);
            padding: .65rem 1rem;
        }

        .skip-link:focus {
            transform: translateY(0);
        }

        .site-navbar {
            background: rgba(255, 254, 250, .96);
            border-bottom: 1px solid var(--line-soft);
            box-shadow: 0 5px 18px rgba(41, 35, 44, .05);
            backdrop-filter: blur(12px);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
            max-width: 24rem;
            color: var(--text-main);
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.25;
            white-space: normal;
        }

        .brand-logo {
            flex: 0 0 3rem;
            width: 3rem;
            height: 3rem;
            object-fit: contain;
        }

        .brand-copy {
            display: flex;
            flex-direction: column;
            gap: .1rem;
        }

        .brand-name {
            font-size: .9rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .brand-subtitle {
            color: var(--text-muted);
            font-size: .78rem;
            font-weight: 400;
        }

        .navbar-brand:hover {
            color: var(--brand-blue-dark);
        }

        .navbar-nav .nav-link {
            border-radius: .5rem;
            color: var(--text-main);
            font-weight: 700;
            padding-inline: .8rem;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active,
        .navbar-nav .nav-link.show {
            background: var(--surface-blue);
            color: var(--brand-blue-dark);
        }

        .navbar-nav .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: .35rem;
            border: 0;
            background: transparent;
            font: inherit;
            font-weight: 700;
        }

        .dropdown-menu {
            min-width: 15rem;
            border-color: var(--line-soft);
            border-radius: .75rem;
            box-shadow: 0 12px 30px rgba(41, 35, 44, .12);
            padding: .5rem;
        }

        .dropdown-header {
            color: var(--text-muted);
            font-weight: 700;
        }

        .dropdown-item {
            border-radius: .4rem;
            color: var(--text-main);
        }

        .dropdown-item:hover,
        .dropdown-item:focus {
            background: var(--surface-blue);
            color: var(--brand-blue-dark);
        }

        .dropdown-item.submenu-item {
            padding-left: 1.75rem;
        }

        .menu-trigger,
        .offcanvas-close {
            border-color: var(--brand-blue);
            color: var(--brand-blue-dark);
            font-weight: 700;
        }

        .offcanvas-close {
            border-color: var(--line-soft);
            color: var(--text-main);
            font-weight: 600;
        }

        .offcanvas {
            background: var(--page-bg);
        }

        .offcanvas-title {
            max-width: 16rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .mobile-nav-group {
            margin: .25rem 0 .75rem 1rem;
            padding-left: .5rem;
            border-left: 1px solid var(--line-soft);
        }

        .mobile-nav-group .nav-link {
            display: flex;
            min-height: 2.75rem;
            align-items: center;
        }

        .mobile-nav-subgroup {
            margin-left: .75rem;
            padding-left: .5rem;
            border-left: 1px solid var(--line-soft);
        }

        .page-main {
            min-height: 65vh;
        }

        .hero-wrap {
            padding-block: 1.5rem 2rem;
        }

        .hero-panel {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            border-radius: var(--radius-panel);
            background: var(--brand-blue);
            color: #fff;
            padding: 2rem 1.5rem;
            min-height: 24rem;
        }

        .hero-panel--photo::before {
            position: absolute;
            z-index: 1;
            inset: 0;
            background: rgba(15, 34, 56, .55);
            content: "";
        }

        .hero-image {
            position: absolute;
            z-index: 0;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center 52%;
        }

        .hero-panel .row {
            position: relative;
            z-index: 2;
        }

        .church-photo {
            overflow: hidden;
            margin: 0;
            border-radius: var(--radius-panel);
            background: var(--surface-blue);
        }

        .church-photo img {
            display: block;
            width: 100%;
            max-height: 34rem;
            object-fit: cover;
            object-position: center 52%;
        }

        .church-photo figcaption {
            padding: .8rem 1rem;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .hero-title {
            max-width: 15ch;
            margin: 0 0 1rem;
            font-size: 2.5rem;
            font-weight: 700;
            line-height: 1.1;
        }

        .hero-copy {
            max-width: 60ch;
            margin-bottom: 1.5rem;
            color: #fff;
            font-size: 1.1rem;
        }

        .hero-actions .btn {
            min-height: 2.9rem;
            border-radius: .5rem;
            font-weight: 700;
        }

        .hero-primary {
            background: #fff;
            border-color: #fff;
            color: var(--brand-blue-dark);
        }

        .hero-primary:hover {
            background: var(--surface-blue);
            border-color: var(--surface-blue);
            color: var(--brand-blue-dark);
        }

        .hero-secondary {
            border-color: rgba(255, 255, 255, .8);
            color: #fff;
        }

        .hero-secondary:hover {
            background: rgba(255, 255, 255, .14);
            border-color: #fff;
            color: #fff;
        }

        .section-block {
            padding-block: 2.75rem;
        }

        .section-heading {
            max-width: 24ch;
            margin-bottom: .6rem;
            font-size: 2rem;
            font-weight: 700;
            line-height: 1.2;
        }

        .section-intro {
            max-width: 68ch;
            margin-bottom: 1.5rem;
            color: var(--text-muted);
        }

        .info-card,
        .portal-card,
        .detail-card {
            height: 100%;
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-card);
            background: #fff;
            box-shadow: 0 10px 28px rgba(41, 35, 44, .06);
            transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
        }

        .info-card:hover,
        .portal-card:hover,
        .detail-card:hover {
            border-color: rgba(13, 110, 253, .3);
            box-shadow: 0 14px 32px rgba(41, 35, 44, .1);
            transform: translateY(-2px);
        }

        .info-card .card-body,
        .portal-card .card-body,
        .detail-card .card-body {
            padding: 1.35rem;
        }

        .surface-title {
            margin-bottom: 1rem;
            font-size: 1.35rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .empty-state {
            margin-top: 1rem;
        }

        .empty-state h3,
        .empty-state h4 {
            margin-bottom: .35rem;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .empty-state p {
            margin: 0;
            color: var(--text-main);
        }

        .detail-header {
            padding-block: 2rem 1rem;
        }

        .detail-title {
            max-width: 22ch;
            margin-bottom: .75rem;
            font-size: 2.35rem;
            font-weight: 700;
            line-height: 1.15;
        }

        .portal-card {
            color: inherit;
            text-decoration: none;
        }

        .portal-card:hover,
        .portal-card:focus-visible {
            color: inherit;
        }

        .portal-card .card-title {
            color: var(--text-main);
            font-size: 1.15rem;
            font-weight: 700;
        }

        .portal-card .card-text {
            color: var(--text-muted);
        }

        .portal-link {
            color: var(--brand-blue-dark);
            font-weight: 700;
            text-decoration: underline;
            text-underline-offset: .2em;
        }

        .schedule-table {
            --bs-table-bg: transparent;
            --bs-table-color: var(--text-main);
            margin-bottom: 0;
        }

        .schedule-table th,
        .schedule-table td {
            padding: .8rem .65rem;
            vertical-align: middle;
        }

        .schedule-table th {
            font-size: .9rem;
            font-weight: 700;
            white-space: nowrap;
        }

        .article-entry + .article-entry {
            border-top: 1px solid var(--line-soft);
            margin-top: 1.25rem;
            padding-top: 1.25rem;
        }

        .article-entry time,
        .article-category {
            color: var(--text-muted);
            font-size: .9rem;
        }

        .article-photo {
            display: block;
            width: min(100%, 56rem);
            max-height: 32rem;
            margin-bottom: 1.5rem;
            border-radius: var(--radius-card);
            object-fit: cover;
        }

        .contact-band {
            background: var(--surface-soft);
        }

        .contact-band a:not(.contact-link) {
            color: var(--brand-blue-dark);
        }

        .contact-empty {
            margin-top: 0;
            padding: 1.25rem;
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-card);
            background: var(--page-bg);
        }

        .contact-map-frame {
            overflow: hidden;
            aspect-ratio: 4 / 3;
            border-radius: var(--radius-card);
            background: var(--surface-blue);
        }

        .contact-map-frame iframe {
            display: block;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .contact-link {
            border-color: var(--brand-blue-dark);
            color: var(--brand-blue-dark);
        }

        .contact-link:hover {
            border-color: var(--brand-blue-dark);
            background: var(--brand-blue-dark);
            color: #fff;
        }

        .gallery-feature {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(18rem, 1fr);
            align-items: end;
            gap: 2rem;
        }

        .gallery-feature-image,
        .gallery-card-image {
            display: block;
            overflow: hidden;
            border-radius: var(--radius-card);
            background: var(--surface-blue);
        }

        .gallery-feature-image {
            height: 22.5rem;
        }

        .gallery-feature-image img,
        .gallery-card-image img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 220ms ease-out;
        }

        .gallery-feature-image:hover img,
        .gallery-card-image:hover img {
            transform: scale(1.025);
        }

        .gallery-feature-copy {
            max-width: 34rem;
            padding-block: 1.25rem;
        }

        .gallery-feature-copy .surface-title {
            margin-bottom: .6rem;
        }

        .gallery-feature-date {
            color: var(--text-muted);
            font-size: .9rem;
        }

        .gallery-archive {
            margin-top: 3.5rem;
        }

        .gallery-archive .section-heading {
            margin-bottom: 1.5rem;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 1.5rem 1.1rem;
        }

        [hidden] {
            display: none !important;
        }

        .gallery-card {
            min-width: 0;
            margin: 0;
        }

        .gallery-card-image {
            aspect-ratio: 4 / 3;
        }

        .gallery-card figcaption {
            margin-top: .65rem;
            color: var(--text-main);
            font-weight: 600;
        }

        .site-footer {
            border-top: 0;
            background: var(--brand-blue-dark);
            color: #fff;
            padding-block: 2.75rem 1rem;
        }

        .site-footer a {
            color: #fff;
            text-underline-offset: .2em;
        }

        .footer-heading {
            margin-bottom: .75rem;
            color: #fff;
            font-size: 1rem;
            font-weight: 700;
        }

        .footer-links {
            display: grid;
            gap: .35rem;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .footer-bottom {
            margin-top: 2rem;
            border-top: 1px solid rgba(255, 255, 255, .3);
            padding-top: .9rem;
            color: rgba(255, 255, 255, .9);
            font-size: .9rem;
        }

        @media (min-width: 768px) {
            .hero-wrap {
                padding-block: 2rem 2.5rem;
            }

            .hero-panel {
                padding: 3.5rem;
            }

            .section-block {
                padding-block: 3.5rem;
            }

            .info-card .card-body,
            .portal-card .card-body,
            .detail-card .card-body {
                padding: 1.75rem;
            }
        }

        @media (min-width: 992px) {
            .hero-title {
                font-size: 3.25rem;
            }

            .detail-title {
                font-size: 2.75rem;
            }
        }

        @media (max-width: 575.98px) {
            .hero-actions .btn {
                width: 100%;
            }

            .section-heading {
                font-size: 1.75rem;
            }

            .detail-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 991.98px) {
            .gallery-feature {
                grid-template-columns: minmax(0, 1fr);
                gap: 1rem;
            }

            .gallery-feature-image {
                height: auto;
                aspect-ratio: 16 / 10;
            }

            .gallery-feature-copy {
                max-width: 65ch;
                padding-block: .5rem;
            }

            .gallery-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 399.98px) {
            .gallery-grid {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            @view-transition {
                navigation: none;
            }

            html {
                scroll-behavior: auto;
            }

            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
<a class="skip-link" href="#konten-utama">Lewati ke konten utama</a>

<header class="site-navbar sticky-top">
    <nav class="navbar navbar-expand-lg container py-2" aria-label="Navigasi utama">
        <a class="navbar-brand" href="<?= esc(site_url('/')) ?>">
            <?php if ($hasLogo): ?>
                <img class="brand-logo" src="<?= esc(base_url($logoAsset)) ?>" alt="" width="48" height="48">
            <?php endif; ?>
            <span class="brand-copy">
                <span class="brand-name">Jemaat GPM Bukit Pengharapan</span>
                <span class="brand-subtitle">Gereja Protestan Maluku</span>
            </span>
        </a>

        <div class="d-none d-lg-flex ms-auto">
            <ul class="navbar-nav align-items-lg-center gap-lg-1">
                <li class="nav-item"><a class="<?= esc($navClass('beranda')) ?>" href="<?= esc(site_url('/')) ?>" <?= $activePage === 'beranda' ? 'aria-current="page"' : '' ?>>Beranda</a></li>
                <li class="nav-item dropdown">
                    <button class="<?= esc($navClass('profil')) ?> dropdown-toggle" id="menuProfilDesktop" type="button" data-bs-toggle="dropdown" aria-expanded="false" <?= $activePage === 'profil' ? 'aria-current="page"' : '' ?>>Profil</button>
                    <ul class="dropdown-menu" aria-labelledby="menuProfilDesktop">
                        <li><a class="dropdown-item<?= ($activeSubpage ?? '') === 'sejarah' ? ' active' : '' ?>" href="<?= esc(site_url('profil/sejarah')) ?>" <?= ($activeSubpage ?? '') === 'sejarah' ? 'aria-current="page"' : '' ?>>Sejarah Jemaat</a></li>
                        <li><a class="dropdown-item<?= ($activeSubpage ?? '') === 'visi-misi' ? ' active' : '' ?>" href="<?= esc(site_url('profil/visi-misi')) ?>" <?= ($activeSubpage ?? '') === 'visi-misi' ? 'aria-current="page"' : '' ?>>Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item<?= ($activeSubpage ?? '') === 'struktur-majelis' ? ' active' : '' ?>" href="<?= esc(site_url('profil/struktur-majelis')) ?>" <?= ($activeSubpage ?? '') === 'struktur-majelis' ? 'aria-current="page"' : '' ?>>Struktur Majelis</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <button class="<?= esc($navClass('pelayanan')) ?> dropdown-toggle" id="menuPelayananDesktop" type="button" data-bs-toggle="dropdown" aria-expanded="false">Pelayanan</button>
                    <ul class="dropdown-menu" aria-labelledby="menuPelayananDesktop">
                        <li><a class="dropdown-item<?= ($activeSubpage ?? '') === 'wadah' ? ' active' : '' ?>" href="<?= esc(site_url('pelayanan/wadah')) ?>" <?= ($activeSubpage ?? '') === 'wadah' ? 'aria-current="page"' : '' ?>>Wadah Pelayanan</a></li>
                        <li><a class="dropdown-item<?= ($activeSubpage ?? '') === 'sektor-unit' ? ' active' : '' ?>" href="<?= esc(site_url('pelayanan/sektor-unit')) ?>" <?= ($activeSubpage ?? '') === 'sektor-unit' ? 'aria-current="page"' : '' ?>>Sektor &amp; Unit Pelayanan</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="<?= esc($navClass('jadwal')) ?>" href="<?= esc(site_url('jadwal')) ?>" <?= $activePage === 'jadwal' ? 'aria-current="page"' : '' ?>>Jadwal Ibadah</a></li>
                <li class="nav-item"><a class="<?= esc($navClass('warta')) ?>" href="<?= esc(site_url('warta')) ?>" <?= $activePage === 'warta' ? 'aria-current="page"' : '' ?>>Warta &amp; Berita</a></li>
                <li class="nav-item"><a class="<?= esc($navClass('galeri')) ?>" href="<?= esc(site_url('galeri')) ?>" <?= $activePage === 'galeri' ? 'aria-current="page"' : '' ?>>Galeri</a></li>
                <li class="nav-item"><a class="<?= esc($navClass('kontak')) ?>" href="<?= esc(site_url('kontak')) ?>" <?= $activePage === 'kontak' ? 'aria-current="page"' : '' ?>>Kontak</a></li>
            </ul>
        </div>

        <button class="btn menu-trigger d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuSeluler" aria-controls="menuSeluler" aria-expanded="false">Menu</button>
    </nav>
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="menuSeluler" aria-labelledby="judulMenuSeluler">
    <div class="offcanvas-header border-bottom">
        <h2 class="offcanvas-title" id="judulMenuSeluler">Jemaat GPM Bukit Pengharapan</h2>
        <button type="button" class="btn offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Tutup menu">Tutup</button>
    </div>
    <nav class="offcanvas-body" aria-label="Navigasi seluler">
        <ul class="navbar-nav gap-1">
            <li class="nav-item"><a class="<?= esc($navClass('beranda')) ?>" href="<?= esc(site_url('/')) ?>">Beranda</a></li>
            <li class="nav-item">
                <button class="nav-link dropdown-toggle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#profilSeluler" aria-expanded="<?= $activePage === 'profil' ? 'true' : 'false' ?>" aria-controls="profilSeluler">Profil</button>
                <div class="collapse<?= $activePage === 'profil' ? ' show' : '' ?> mobile-nav-group" id="profilSeluler">
                    <a class="nav-link" href="<?= esc(site_url('profil/sejarah')) ?>" <?= ($activeSubpage ?? '') === 'sejarah' ? 'aria-current="page"' : '' ?>>Sejarah Jemaat</a>
                    <a class="nav-link" href="<?= esc(site_url('profil/visi-misi')) ?>" <?= ($activeSubpage ?? '') === 'visi-misi' ? 'aria-current="page"' : '' ?>>Visi &amp; Misi</a>
                    <a class="nav-link" href="<?= esc(site_url('profil/struktur-majelis')) ?>" <?= ($activeSubpage ?? '') === 'struktur-majelis' ? 'aria-current="page"' : '' ?>>Struktur Majelis</a>
                </div>
            </li>
            <li class="nav-item">
                <button class="nav-link dropdown-toggle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#pelayananSeluler" aria-expanded="<?= $activePage === 'pelayanan' ? 'true' : 'false' ?>" aria-controls="pelayananSeluler">Pelayanan</button>
                <div class="collapse<?= $activePage === 'pelayanan' ? ' show' : '' ?> mobile-nav-group" id="pelayananSeluler">
                    <a class="nav-link" href="<?= esc(site_url('pelayanan/wadah')) ?>" <?= ($activeSubpage ?? '') === 'wadah' ? 'aria-current="page"' : '' ?>>Wadah Pelayanan</a>
                    <a class="nav-link" href="<?= esc(site_url('pelayanan/sektor-unit')) ?>" <?= ($activeSubpage ?? '') === 'sektor-unit' ? 'aria-current="page"' : '' ?>>Sektor &amp; Unit Pelayanan</a>
                </div>
            </li>
            <li class="nav-item"><a class="<?= esc($navClass('jadwal')) ?>" href="<?= esc(site_url('jadwal')) ?>">Jadwal Ibadah</a></li>
            <li class="nav-item"><a class="<?= esc($navClass('warta')) ?>" href="<?= esc(site_url('warta')) ?>">Warta &amp; Berita</a></li>
            <li class="nav-item"><a class="<?= esc($navClass('galeri')) ?>" href="<?= esc(site_url('galeri')) ?>">Galeri</a></li>
            <li class="nav-item"><a class="<?= esc($navClass('kontak')) ?>" href="<?= esc(site_url('kontak')) ?>">Kontak</a></li>
        </ul>
    </nav>
</div>

<main class="page-main" id="konten-utama">
    <?= $this->renderSection('content') ?>
</main>

<?php if ($activePage !== 'kontak'): ?>
    <section class="section-block contact-band" id="kontak" aria-labelledby="judul-kontak-global">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <h2 class="section-heading" id="judul-kontak-global"><?= esc($content['shared_contact_heading'] ?? 'Kontak Jemaat') ?></h2>
                    <p class="section-intro mb-0"><?= nl2br(esc($content['shared_contact_intro'] ?? 'Gereja Elpido, Latuhalat, Ambon')) ?></p>
                </div>
                <div class="col-lg-7">
                    <p class="mb-2"><a href="mailto:<?= esc(config('Email')->recipients) ?>"><?= esc(config('Email')->recipients) ?></a></p>
                    <a class="btn contact-link fw-bold" href="<?= esc(site_url('kontak')) ?>"><?= esc($content['shared_contact_button'] ?? 'Lihat peta & kirim pesan') ?></a>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<?= $this->include('partials/footer') ?>

<script src="<?= esc(base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js')) ?>"></script>
<script {csp-script-nonce}>
    const mobileMenu = document.getElementById('menuSeluler');
    const menuTrigger = document.querySelector('[data-bs-target="#menuSeluler"]');

    mobileMenu.addEventListener('show.bs.offcanvas', () => menuTrigger.setAttribute('aria-expanded', 'true'));
    mobileMenu.addEventListener('hidden.bs.offcanvas', () => menuTrigger.setAttribute('aria-expanded', 'false'));
    mobileMenu.querySelectorAll('a[href]').forEach((link) => {
        link.addEventListener('click', () => window.bootstrap.Offcanvas.getOrCreateInstance(mobileMenu).hide());
    });
</script>
</body>
</html>