<?php
$logoAsset = 'assets/images/logo-gpm-bukit-pengharapan.png';
$pageContent = is_array($content ?? null) ? $content : [];
$heroPhotoAsset = (string) ($pageContent['hero_image_path'] ?? 'assets/images/gereja-bukit-pengharapan-udara.jpg');
$facadePhotoAsset = (string) ($pageContent['profile_image_path'] ?? 'assets/images/gereja-bukit-pengharapan-depan.jpg');
$hasLogo = is_file(FCPATH . $logoAsset);
$hasHeroPhoto = is_file(FCPATH . $heroPhotoAsset);
$hasFacadePhoto = is_file(FCPATH . $facadePhotoAsset);
$copy = static fn (string $key, string $fallback): string => (string) ($pageContent[$key] ?? $fallback);
$profileIntroParagraphs = preg_split('/\R{2,}/u', trim($copy('profile_intro', 'Kenali Jemaat GPM Bukit Pengharapan melalui sejarah, visi-misi, dan susunan majelis. Temukan juga informasi ibadah dan wadah pelayanan yang menjadi bagian dari kehidupan jemaat.'))) ?: [];
$providedBeritaSorotan = $beritaSorotan ?? null;
$beritaSorotan = is_array($providedBeritaSorotan) ? $providedBeritaSorotan : [];
$providedFotoGaleri = $fotoGaleri ?? null;
$fotoGaleri = is_array($providedFotoGaleri) ? $providedFotoGaleri : [];
$sectionOrder = is_array($sectionOrder ?? null) ? $sectionOrder : ['hero', 'profile', 'info', 'gallery', 'portal'];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Informasi ibadah, warta, dan kontak Jemaat GPM Bukit Pengharapan.">
    <title>Beranda | Jemaat GPM Bukit Pengharapan</title>
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

        .hero-actions .btn:focus-visible {
            outline-color: #fff;
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
            background: rgba(255, 254, 250, .94);
            border-bottom: 1px solid var(--line-soft);
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
        .navbar-nav .nav-link[aria-current="page"] {
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

        .menu-trigger {
            border-color: var(--brand-blue);
            color: var(--brand-blue-dark);
            font-weight: 700;
        }

        .offcanvas {
            background: var(--page-bg);
        }

        .offcanvas-title {
            max-width: 16rem;
            font-size: 1.1rem;
            font-weight: 700;
        }

        .offcanvas-close {
            border-color: var(--line-soft);
            color: var(--text-main);
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

        .profile-hook-frame {
            position: relative;
            width: 100%;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: var(--radius-card);
            background: var(--surface-blue);
        }

        .profile-hook-photo {
            position: absolute;
            inset: 0;
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .profile-hook-placeholder {
            display: grid;
            width: 100%;
            aspect-ratio: 1;
            place-items: center;
            border: 1px dashed rgba(11, 94, 215, .38);
            border-radius: var(--radius-card);
            background: var(--surface-blue);
            color: var(--brand-blue-dark);
            padding: 1.5rem;
            text-align: center;
        }

        .profile-hook-copy {
            max-width: none;
        }

        .profile-hook-copy .section-heading {
            max-width: none;
        }

        .profile-hook-copy .section-intro {
            max-width: none;
            margin-bottom: 1.15rem;
        }

        .profile-hook-copy .btn {
            min-height: 2.8rem;
            border-radius: .5rem;
            font-weight: 700;
        }

        .profile-cta {
            border-color: var(--brand-blue-dark);
            color: var(--brand-blue-dark);
        }

        .profile-cta:hover {
            border-color: var(--brand-blue-dark);
            background: var(--brand-blue-dark);
            color: #fff;
        }

        .contact-empty {
            margin-top: 0;
            padding: 1.25rem;
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-card);
            background: var(--page-bg);
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

        .news-mosaic {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            grid-auto-rows: 10rem;
            gap: .6rem;
        }

        .news-tile {
            position: relative;
            display: flex;
            min-width: 0;
            overflow: hidden;
            align-items: flex-end;
            border-radius: var(--radius-card);
            background: var(--brand-blue-dark);
            color: #fff;
            text-decoration: none;
        }

        .news-tile:first-child {
            grid-column: span 2;
            grid-row: span 2;
        }

        .news-tile:nth-child(2) {
            grid-row: span 2;
        }

        .news-tile img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 280ms ease;
        }

        .news-tile:hover img,
        .news-tile:focus-visible img {
            transform: scale(1.035);
        }

        .news-tile::after {
            position: absolute;
            inset: 35% 0 0;
            background: linear-gradient(transparent, rgba(20, 28, 38, .82));
            content: "";
        }

        .news-tile-copy {
            position: relative;
            z-index: 1;
            padding: 1rem;
        }

        .news-category {
            display: inline-block;
            margin-bottom: .4rem;
            border-radius: .25rem;
            background: #fff;
            color: var(--brand-blue-dark);
            padding: .12rem .45rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .news-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            line-height: 1.3;
        }

        .news-tile:first-child .news-title {
            font-size: 1.4rem;
        }

        .news-date {
            display: block;
            margin-top: .35rem;
            color: rgba(255, 255, 255, .9);
            font-size: .8rem;
        }

        .majelis-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }

        .majelis-photo {
            overflow: hidden;
            margin: 0;
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-card);
            background: #fff;
        }

        .majelis-photo img {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 5;
            object-fit: cover;
        }

        .majelis-photo figcaption {
            padding: .85rem;
        }

        .majelis-photo strong,
        .majelis-photo span {
            display: block;
        }

        .majelis-photo span {
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
        .portal-card {
            height: 100%;
            border: 1px solid var(--line-soft);
            border-radius: var(--radius-card);
            background: #fff;
            box-shadow: 0 10px 28px rgba(41, 35, 44, .06);
            transition: border-color 180ms ease, box-shadow 180ms ease, transform 180ms ease;
        }

        .info-card:hover,
        .portal-card:hover {
            border-color: rgba(13, 110, 253, .3);
            box-shadow: 0 14px 32px rgba(41, 35, 44, .1);
            transform: translateY(-2px);
        }

        .info-card .card-body,
        .portal-card .card-body {
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

        .empty-state h3 {
            margin-bottom: .35rem;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .empty-state p {
            margin: 0;
            color: var(--text-main);
        }

        .portal-card {
            color: inherit;
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

        .contact-band {
            background: var(--surface-soft);
        }

        .contact-band a:not(.contact-link) {
            color: var(--brand-blue-dark);
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
            .portal-card .card-body {
                padding: 1.75rem;
            }

            .majelis-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .news-mosaic {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                grid-auto-rows: 11rem;
            }

            .news-tile:first-child {
                grid-column: span 2;
                grid-row: span 1;
            }

            .news-tile:nth-child(2) {
                grid-row: span 1;
            }
        }

        @media (min-width: 1200px) {
            .majelis-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }

        @media (max-width: 399.98px) {
            .news-mosaic {
                grid-template-columns: 1fr;
                grid-auto-rows: 13rem;
            }

            .news-tile:first-child {
                grid-column: auto;
                grid-row: auto;
            }
        }

        @media (min-width: 992px) {
            .hero-title {
                font-size: 3.25rem;
            }
        }

        @media (max-width: 575.98px) {
            .hero-actions .btn {
                width: 100%;
            }

            .section-heading {
                font-size: 1.75rem;
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
        <a class="navbar-brand" href="#beranda">
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
                <li class="nav-item"><a class="nav-link" href="#beranda" aria-current="page">Beranda</a></li>
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" id="menuProfilDesktop" type="button" data-bs-toggle="dropdown" aria-expanded="false">Profil</button>
                    <ul class="dropdown-menu" aria-labelledby="menuProfilDesktop">
                        <li><a class="dropdown-item" href="<?= esc(site_url('profil/sejarah')) ?>">Sejarah Jemaat</a></li>
                        <li><a class="dropdown-item" href="<?= esc(site_url('profil/visi-misi')) ?>">Visi &amp; Misi</a></li>
                        <li><a class="dropdown-item" href="<?= esc(site_url('profil/struktur-majelis')) ?>">Struktur Majelis</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle" id="menuPelayananDesktop" type="button" data-bs-toggle="dropdown" aria-expanded="false">Pelayanan</button>
                    <ul class="dropdown-menu" aria-labelledby="menuPelayananDesktop">
                        <li><a class="dropdown-item" href="<?= esc(site_url('pelayanan/wadah')) ?>">Wadah Pelayanan</a></li>
                        <li><a class="dropdown-item" href="<?= esc(site_url('pelayanan/sektor-unit')) ?>">Sektor &amp; Unit Pelayanan</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('jadwal')) ?>">Jadwal Ibadah</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('warta')) ?>">Warta &amp; Berita</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('galeri')) ?>">Galeri</a></li>
                <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('kontak')) ?>">Kontak</a></li>
            </ul>
        </div>

        <button class="btn menu-trigger d-lg-none ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#menuSeluler" aria-controls="menuSeluler" aria-expanded="false">
            Menu
        </button>
    </nav>
</header>

<div class="offcanvas offcanvas-end" tabindex="-1" id="menuSeluler" aria-labelledby="judulMenuSeluler">
    <div class="offcanvas-header border-bottom">
        <h2 class="offcanvas-title" id="judulMenuSeluler">Jemaat GPM Bukit Pengharapan</h2>
        <button type="button" class="btn offcanvas-close" data-bs-dismiss="offcanvas" aria-label="Tutup menu">Tutup</button>
    </div>
    <nav class="offcanvas-body" aria-label="Navigasi seluler">
        <ul class="navbar-nav gap-1">
            <li class="nav-item"><a class="nav-link" href="#beranda" aria-current="page">Beranda</a></li>
            <li class="nav-item">
                <button class="nav-link dropdown-toggle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#profilSeluler" aria-expanded="false" aria-controls="profilSeluler">Profil</button>
                <div class="collapse mobile-nav-group" id="profilSeluler">
                    <a class="nav-link" href="<?= esc(site_url('profil/sejarah')) ?>">Sejarah Jemaat</a>
                    <a class="nav-link" href="<?= esc(site_url('profil/visi-misi')) ?>">Visi &amp; Misi</a>
                    <a class="nav-link" href="<?= esc(site_url('profil/struktur-majelis')) ?>">Struktur Majelis</a>
                </div>
            </li>
            <li class="nav-item">
                <button class="nav-link dropdown-toggle w-100 text-start" type="button" data-bs-toggle="collapse" data-bs-target="#pelayananSeluler" aria-expanded="false" aria-controls="pelayananSeluler">Pelayanan</button>
                <div class="collapse mobile-nav-group" id="pelayananSeluler">
                    <a class="nav-link" href="<?= esc(site_url('pelayanan/wadah')) ?>">Wadah Pelayanan</a>
                    <a class="nav-link" href="<?= esc(site_url('pelayanan/sektor-unit')) ?>">Sektor &amp; Unit Pelayanan</a>
                </div>
            </li>
            <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('jadwal')) ?>">Jadwal Ibadah</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('warta')) ?>">Warta &amp; Berita</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('galeri')) ?>">Galeri</a></li>
            <li class="nav-item"><a class="nav-link" href="<?= esc(site_url('kontak')) ?>">Kontak</a></li>
        </ul>
    </nav>
</div>

<main id="konten-utama">
    <?php ob_start(); ?>
    <section class="hero-wrap" id="beranda" data-preview-section="hero" aria-labelledby="judul-beranda">
        <div class="container">
            <div class="hero-panel<?= $hasHeroPhoto ? ' hero-panel--photo' : '' ?>">
                <?php if ($hasHeroPhoto): ?>
                    <img class="hero-image" src="<?= esc(base_url($heroPhotoAsset)) ?>" alt="Tampak udara gedung Jemaat GPM Bukit Pengharapan" data-live-image="hero_image" fetchpriority="high">
                <?php endif; ?>
                <div class="row align-items-center g-4">
                    <div class="col-lg-9">
                        <h1 class="hero-title" id="judul-beranda" data-live-preview="hero_title"><?= esc($copy('hero_title', 'Jemaat GPM Bukit Pengharapan')) ?></h1>
                        <p class="hero-copy" data-live-preview="hero_intro"><?= nl2br(esc($copy('hero_intro', 'Selamat datang. Temukan informasi ibadah, warta jemaat, dan informasi kunjungan di sini.'))) ?></p>
                        <div class="hero-actions d-flex flex-column flex-sm-row gap-2">
                            <a class="btn hero-primary" href="#jadwal-ibadah">Lihat jadwal ibadah</a>
                            <a class="btn hero-secondary" href="#portal-jemaat">Jelajahi portal</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $homeSections['hero'] = ob_get_clean();
    ob_start();
    ?>
    <section class="section-block pt-0" id="profil-singkat" data-preview-section="profile" aria-labelledby="judul-profil-singkat">
        <div class="container">
            <div class="row g-4 g-lg-5 align-items-start">
                <div class="col-10 col-sm-8 col-lg-4 mx-auto mx-lg-0">
                    <?php if ($hasFacadePhoto): ?>
                        <div class="profile-hook-frame">
                            <img class="profile-hook-photo" src="<?= esc(base_url($facadePhotoAsset)) ?>" alt="Tampak depan gedung Jemaat GPM Bukit Pengharapan" data-live-image="profile_image" loading="lazy" width="1448" height="1086">
                        </div>
                    <?php else: ?>
                        <div class="profile-hook-placeholder" role="img" aria-label="Foto tampak depan gereja belum tersedia">Foto gedung gereja akan ditampilkan di sini.</div>
                    <?php endif; ?>
                </div>
                <div class="col-12 col-lg-8">
                    <div class="profile-hook-copy">
                        <h2 class="section-heading" id="judul-profil-singkat" data-live-preview="profile_heading"><?= esc($copy('profile_heading', 'Mengenal Jemaat')) ?></h2>
                        <div data-live-preview="profile_intro" data-live-preview-format="paragraphs">
                            <?php foreach ($profileIntroParagraphs as $paragraph): ?>
                                <p class="section-intro"><?= nl2br(esc($paragraph)) ?></p>
                            <?php endforeach; ?>
                        </div>
                        <a class="btn profile-cta" href="<?= esc(site_url('profil/sejarah')) ?>">Baca sejarah jemaat</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    $homeSections['profile'] = ob_get_clean();
    ob_start();
    ?>
    <section class="section-block pt-4" data-preview-section="info" aria-labelledby="judul-informasi">
        <div class="container">
            <div class="row align-items-end g-3 mb-4">
                <div class="col-lg-8">
                    <h2 class="section-heading" id="judul-informasi" data-live-preview="info_heading"><?= esc($copy('info_heading', 'Informasi jemaat')) ?></h2>
                    <p class="section-intro mb-0" data-live-preview="info_intro"><?= nl2br(esc($copy('info_intro', 'Jadwal ibadah dan kabar terbaru dalam satu tempat.'))) ?></p>
                </div>
            </div>

            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4" id="jadwal-ibadah">
                    <article class="card info-card" aria-labelledby="judul-jadwal">
                        <div class="card-body">
                            <h3 class="surface-title" id="judul-jadwal" data-live-preview="schedule_heading"><?= esc($copy('schedule_heading', 'Jadwal Ibadah')) ?></h3>
                            <div class="empty-state" role="status">
                                <h4 data-live-preview="schedule_empty_heading"><?= esc($copy('schedule_empty_heading', 'Jadwal belum tersedia')) ?></h4>
                                <p data-live-preview="schedule_empty_text"><?= nl2br(esc($copy('schedule_empty_text', 'Jadwal ibadah resmi belum dipublikasikan.'))) ?></p>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="col-lg-8" id="warta-jemaat">
                    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                        <div>
                            <h3 class="surface-title mb-1" id="judul-warta-terbaru" data-live-preview="warta_heading"><?= esc($copy('warta_heading', 'Sorotan Warta & Berita')) ?></h3>
                            <p class="section-intro mb-0" data-live-preview="warta_intro"><?= nl2br(esc($copy('warta_intro', 'Kabar terbaru dari kehidupan jemaat.'))) ?></p>
                        </div>
                        <a class="portal-link flex-shrink-0" href="<?= esc(site_url('warta')) ?>">Semua warta</a>
                    </div>
                    <?php if ($beritaSorotan === []): ?>
                        <div class="empty-state" role="status">
                            <h4 data-live-preview="warta_empty_heading"><?= esc($copy('warta_empty_heading', 'Belum ada warta yang dipublikasikan')) ?></h4>
                            <p data-live-preview="warta_empty_text"><?= nl2br(esc($copy('warta_empty_text', 'Sorotan berita akan muncul di sini setelah ada publikasi resmi.'))) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="news-mosaic" aria-label="Sorotan berita jemaat">
                            <?php foreach (array_slice($beritaSorotan, 0, 4) as $item): ?>
                                <?php
                                $imagePath = (string) ($item['foto'] ?? '');
                                $hasItemImage = $imagePath !== '' && is_file(FCPATH . $imagePath);
                                ?>
                                <a class="news-tile" href="<?= esc(site_url('warta/' . ($item['slug'] ?? ''))) ?>">
                                    <?php if ($hasItemImage): ?>
                                        <img src="<?= esc(base_url($imagePath)) ?>" alt="" loading="lazy">
                                    <?php endif; ?>
                                    <span class="news-tile-copy">
                                        <?php if (! empty($item['kategori'])): ?>
                                            <span class="news-category"><?= esc($item['kategori']) ?></span>
                                        <?php endif; ?>
                                        <span class="news-title"><?= esc($item['judul'] ?? 'Warta Jemaat') ?></span>
                                        <?php if (! empty($item['tanggal'])): ?>
                                            <time class="news-date" datetime="<?= esc($item['tanggal_iso'] ?? '', 'attr') ?>"><?= esc($item['tanggal']) ?></time>
                                        <?php endif; ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    $homeSections['info'] = ob_get_clean();
    ob_start();
    ?>
    <section class="section-block" id="galeri-jemaat" data-preview-section="gallery" aria-labelledby="judul-galeri">
        <div class="container">
            <div class="d-flex justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <h2 class="section-heading" id="judul-galeri" data-live-preview="gallery_heading"><?= esc($copy('gallery_heading', 'Galeri Jemaat')) ?></h2>
                    <p class="section-intro mb-0" data-live-preview="gallery_intro"><?= nl2br(esc($copy('gallery_intro', 'Dokumentasi kegiatan dan persekutuan Jemaat Bukit Pengharapan.'))) ?></p>
                </div>
                <a class="portal-link flex-shrink-0" href="<?= esc(site_url('galeri')) ?>">Lihat semua foto</a>
            </div>
            <?php if ($fotoGaleri === []): ?>
                <div class="empty-state" role="status" data-live-gallery-empty>
                    <h3 data-live-preview="gallery_empty_heading"><?= esc($copy('gallery_empty_heading', 'Belum ada foto galeri')) ?></h3>
                    <p data-live-preview="gallery_empty_text"><?= nl2br(esc($copy('gallery_empty_text', 'Foto kegiatan jemaat akan ditampilkan di sini setelah diunggah.'))) ?></p>
                </div>
            <?php endif; ?>
                <div class="majelis-grid" data-live-gallery <?= $fotoGaleri === [] ? 'hidden' : '' ?>>
                    <?php foreach (array_slice($fotoGaleri, 0, 5) as $foto): ?>
                        <?php
                        $photoPath = 'uploads/gallery/' . (string) ($foto['filename'] ?? '');
                        if (! is_file(FCPATH . $photoPath)) {
                            continue;
                        }
                        ?>
                        <figure class="majelis-photo">
                            <img src="<?= esc(base_url($photoPath)) ?>" alt="<?= esc($foto['alt_text'] ?? '') ?>" loading="lazy">
                            <figcaption>
                                <strong><?= esc($foto['caption'] ?? '') ?></strong>
                            </figcaption>
                        </figure>
                    <?php endforeach; ?>
                </div>
        </div>
    </section>

    <?php
    $homeSections['gallery'] = ob_get_clean();
    ob_start();
    ?>
    <section class="section-block pt-0" id="portal-jemaat" data-preview-section="portal" aria-labelledby="judul-portal">
        <div class="container">
            <div class="row align-items-end g-3 mb-4">
                <div class="col-lg-8">
                    <h2 class="section-heading" id="judul-portal" data-live-preview="portal_heading"><?= esc($copy('portal_heading', 'Jelajahi portal jemaat')) ?></h2>
                    <p class="section-intro mb-0" data-live-preview="portal_intro"><?= nl2br(esc($copy('portal_intro', 'Pilih informasi yang ingin Anda kunjungi.'))) ?></p>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 row-cols-xl-5 g-3">
                <?php
                $portalItems = [
                    ['label' => 'Profil', 'description' => $copy('portal_profile', 'Mengenal Jemaat Bukit Pengharapan dan pelayanannya.'), 'previewKey' => 'portal_profile', 'path' => 'profil'],
                    ['label' => 'Jadwal', 'description' => $copy('portal_schedule', 'Informasi ibadah dan kegiatan pelayanan.'), 'previewKey' => 'portal_schedule', 'path' => 'jadwal'],
                    ['label' => 'Pelayanan', 'description' => $copy('portal_service', 'Informasi wadah pelayanan, sektor, dan unit jemaat.'), 'previewKey' => 'portal_service', 'path' => 'pelayanan'],
                    ['label' => 'Galeri', 'description' => $copy('portal_gallery', 'Dokumentasi kegiatan dan persekutuan.'), 'previewKey' => 'portal_gallery', 'path' => 'galeri'],
                    ['label' => 'Kontak', 'description' => $copy('portal_contact', 'Informasi untuk menghubungi dan mengunjungi gereja.'), 'previewKey' => 'portal_contact', 'path' => 'kontak'],
                ];
                ?>
                <?php foreach ($portalItems as $index => $portal): ?>
                    <div class="col">
                        <a class="card portal-card text-decoration-none" href="<?= esc(site_url($portal['path'])) ?>" aria-labelledby="portal-title-<?= esc((string) $index) ?>">
                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title" id="portal-title-<?= esc((string) $index) ?>"><?= esc($portal['label']) ?></h3>
                                <p class="card-text" data-live-preview="<?= esc($portal['previewKey'], 'attr') ?>"><?= esc($portal['description']) ?></p>
                                <span class="portal-link mt-auto">Buka portal</span>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php
    $homeSections['portal'] = ob_get_clean();
    foreach ($sectionOrder as $sectionId) {
        echo $homeSections[$sectionId] ?? '';
    }
    ?>
    <section class="section-block contact-band" id="kontak" aria-labelledby="judul-kontak">
        <div class="container">
            <div class="row g-4 align-items-start">
                <div class="col-lg-5">
                    <h2 class="section-heading" id="judul-kontak" data-live-preview="visit_heading"><?= esc($copy('visit_heading', 'Informasi kunjungan')) ?></h2>
                    <p class="section-intro mb-0" data-live-preview="visit_intro"><?= nl2br(esc($copy('visit_intro', 'Gereja Elpido, Latuhalat, Ambon'))) ?></p>
                </div>
                <div class="col-lg-7">
                    <p><a href="mailto:<?= esc(config('Email')->recipients) ?>"><?= esc(config('Email')->recipients) ?></a></p>
                    <a class="btn contact-link fw-bold" href="<?= esc(site_url('kontak')) ?>" data-live-preview="shared_contact_button"><?= esc($copy('shared_contact_button', 'Lihat peta & kirim pesan')) ?></a>
                </div>
            </div>
        </div>
    </section>
</main>

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