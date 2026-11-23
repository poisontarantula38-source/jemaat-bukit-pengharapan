<?php $footerContent = is_array($content ?? null) ? $content : []; ?>
<footer class="site-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h2 class="footer-heading">Jemaat GPM Bukit Pengharapan</h2>
                <p class="mb-0">Informasi ibadah, warta, dan pelayanan jemaat.</p>
            </div>
            <div class="col-md-4">
                <h2 class="footer-heading" data-live-preview="shared_contact_heading"><?= esc($footerContent['shared_contact_heading'] ?? 'Lokasi & Kontak') ?></h2>
                <p class="mb-1" data-live-preview="shared_contact_intro"><?= nl2br(esc($footerContent['shared_contact_intro'] ?? 'Gereja Elpido, Latuhalat, Ambon')) ?></p>
                <a href="mailto:<?= esc(config('Email')->recipients) ?>"><?= esc(config('Email')->recipients) ?></a>
                <p class="mt-2 mb-0"><a href="https://www.google.com/maps/search/?api=1&amp;query=-3.7763184347482532%2C128.11324017541236" target="_blank" rel="noopener noreferrer">Lihat petunjuk arah</a></p>
            </div>
            <div class="col-md-4">
                <h2 class="footer-heading">Tautan Cepat</h2>
                <ul class="footer-links">
                    <li><a href="<?= esc(site_url('/')) ?>">Beranda</a></li>
                    <li><a href="<?= esc(site_url('profil/sejarah')) ?>">Sejarah Jemaat</a></li>
                    <li><a href="<?= esc(site_url('jadwal')) ?>">Jadwal Ibadah</a></li>
                    <li><a href="<?= esc(site_url('warta')) ?>">Warta &amp; Berita</a></li>
                    <li><a href="<?= esc(site_url('galeri')) ?>">Galeri</a></li>
                    <li><a href="<?= esc(site_url('kontak')) ?>">Kontak</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <span>&copy; <?= date('Y') ?> Jemaat GPM Bukit Pengharapan</span>
        </div>
    </div>
</footer>