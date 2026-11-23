<?php
$content = is_array($content ?? null) ? $content : [];
$fotoGaleri = is_array($fotoGaleri ?? null) ? $fotoGaleri : [];
$validPhotos = [];
foreach ($fotoGaleri as $foto) {
    $filename = (string) ($foto['filename'] ?? '');
    if (basename($filename) !== $filename || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
        log_message('error', 'Ignoring gallery photo with an invalid filename.');
        continue;
    }

    if (! is_file(FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . $filename)) {
        log_message('error', 'Gallery photo file is missing: {filename}', ['filename' => $filename]);
        continue;
    }

    $validPhotos[] = $foto;
}
$fotoUtama = $validPhotos[0] ?? null;
$fotoUtamaPath = $fotoUtama === null ? '' : 'uploads/gallery/' . $fotoUtama['filename'];
$fotoUtamaDate = $fotoUtama === null || empty($fotoUtama['created_at'])
    ? false
    : strtotime((string) $fotoUtama['created_at']);
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="page_title"><?= esc($content['page_title'] ?? 'Galeri Jemaat') ?></h1>
        <p class="section-intro" data-live-preview="page_intro"><?= nl2br(esc($content['page_intro'] ?? 'Kumpulan foto dan momen pelayanan Jemaat Bukit Pengharapan.')) ?></p>
    </div>
</section>

<section class="section-block pt-3" aria-label="Dokumentasi foto jemaat">
    <div class="container">
        <?php if ($fotoUtama === null): ?>
            <div class="empty-state" role="status" data-live-gallery-empty>
                <h2 class="h5 fw-bold" data-live-preview="empty_heading"><?= esc($content['empty_heading'] ?? 'Belum ada foto galeri') ?></h2>
                <p data-live-preview="empty_text"><?= nl2br(esc($content['empty_text'] ?? 'Dokumentasi jemaat akan ditampilkan di sini setelah foto diunggah.')) ?></p>
            </div>
        <?php endif; ?>
        <article class="gallery-feature" data-live-gallery-feature data-live-gallery-feature-empty="<?= $fotoUtama === null ? 'true' : 'false' ?>" <?= $fotoUtama === null ? 'hidden' : '' ?>>
            <a class="gallery-feature-image" href="<?= $fotoUtamaPath === '' ? '#' : esc(base_url($fotoUtamaPath)) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= $fotoUtama === null ? 'Buka foto ukuran penuh' : esc('Buka foto ukuran penuh: ' . $fotoUtama['caption'], 'attr') ?>">
                <img <?= $fotoUtamaPath === '' ? '' : 'src="' . esc(base_url($fotoUtamaPath), 'attr') . '"' ?> alt="<?= esc($fotoUtama['alt_text'] ?? '') ?>" <?= $fotoUtama === null ? '' : 'fetchpriority="high"' ?>>
            </a>
            <div class="gallery-feature-copy">
                <h2 class="surface-title" data-live-gallery-feature-caption><?= esc($fotoUtama['caption'] ?? '') ?></h2>
                <?php if ($fotoUtamaDate !== false): ?>
                    <p class="gallery-feature-date">
                        Diunggah <time datetime="<?= esc(date('Y-m-d', $fotoUtamaDate), 'attr') ?>"><?= esc(date('d-m-Y', $fotoUtamaDate)) ?></time>
                    </p>
                <?php endif; ?>
            </div>
        </article>
        <section class="gallery-archive" data-live-gallery-archive aria-labelledby="judul-foto-lainnya" <?= count($validPhotos) <= 1 ? 'hidden' : '' ?>>
            <h2 class="section-heading" id="judul-foto-lainnya">Foto lainnya</h2>
            <div class="gallery-grid" data-live-gallery <?= count($validPhotos) <= 1 ? 'hidden' : '' ?>>
                <?php foreach (array_slice($validPhotos, 1) as $foto): ?>
                    <?php $photoPath = 'uploads/gallery/' . $foto['filename']; ?>
                    <figure class="gallery-card">
                        <a class="gallery-card-image" href="<?= esc(base_url($photoPath)) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= esc('Buka foto ukuran penuh: ' . $foto['caption'], 'attr') ?>">
                            <img src="<?= esc(base_url($photoPath)) ?>" alt="<?= esc($foto['alt_text']) ?>" loading="lazy">
                        </a>
                        <figcaption><?= esc($foto['caption']) ?></figcaption>
                    </figure>
                <?php endforeach; ?>
            </div>
        </section>
        <?php if ($fotoUtama !== null && $pager !== null): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>
