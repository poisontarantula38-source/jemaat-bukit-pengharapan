<?php
$providedBerita = $berita ?? null;
$berita = is_array($providedBerita) ? $providedBerita : [];
$content = is_array($content ?? null) ? $content : [];
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="page_title"><?= esc($content['page_title'] ?? 'Warta & Berita') ?></h1>
        <p class="section-intro" data-live-preview="page_intro"><?= nl2br(esc($content['page_intro'] ?? 'Pengumuman dan kabar terbaru dari Jemaat Bukit Pengharapan.')) ?></p>
    </div>
</section>

<section class="section-block pt-3" aria-labelledby="judul-berita">
    <div class="container">
        <h2 class="visually-hidden" id="judul-berita">Daftar warta dan berita</h2>
        <?php if ($berita === []): ?>
            <div class="card detail-card">
                <div class="card-body">
                    <div class="empty-state" role="status">
                        <h3 data-live-preview="empty_heading"><?= esc($content['empty_heading'] ?? 'Belum ada warta yang dipublikasikan') ?></h3>
                        <p data-live-preview="empty_text"><?= nl2br(esc($content['empty_text'] ?? 'Warta dan berita jemaat akan ditampilkan di halaman ini setelah tersedia.')) ?></p>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($berita as $item): ?>
                    <div class="col-12">
                        <article class="card detail-card article-entry">
                            <div class="card-body">
                                <?php
                                $imagePath = (string) ($item['foto'] ?? '');
                                $hasArticleImage = str_starts_with($imagePath, 'uploads/warta/')
                                    && basename(substr($imagePath, strlen('uploads/warta/'))) === substr($imagePath, strlen('uploads/warta/'))
                                    && is_file(FCPATH . $imagePath);
                                ?>
                                <?php if ($hasArticleImage): ?>
                                    <img class="article-photo" src="<?= esc(base_url($imagePath)) ?>" alt="<?= esc($item['foto_alt'] ?? '') ?>" loading="lazy">
                                <?php endif; ?>
                                <?php if (! empty($item['kategori'])): ?>
                                    <p class="article-category mb-1"><?= esc($item['kategori']) ?></p>
                                <?php endif; ?>
                                <h2 class="surface-title"><?= esc($item['judul'] ?? 'Warta Jemaat') ?></h2>
                                <?php if (! empty($item['tanggal'])): ?>
                                    <p><time datetime="<?= esc($item['tanggal_iso'] ?? '', 'attr') ?>"><?= esc($item['tanggal']) ?></time></p>
                                <?php endif; ?>
                                <?php if (! empty($item['ringkasan'])): ?>
                                    <p class="mb-0"><?= nl2br(esc($item['ringkasan'])) ?></p>
                                <?php endif; ?>
                                <a class="d-inline-block mt-3 fw-semibold" href="<?= esc(site_url('warta/' . $item['slug'])) ?>">Baca selengkapnya</a>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
<?= $this->endSection() ?>