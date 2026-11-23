<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<article class="section-block">
    <div class="container">
        <p class="mb-3"><a href="<?= esc(site_url('warta')) ?>">Kembali ke Warta &amp; Berita</a></p>
        <div class="card detail-card">
            <div class="card-body p-4 p-md-5">
                <?php
                $imagePath = (string) ($berita['foto'] ?? '');
                $imageFilename = substr($imagePath, strlen('uploads/warta/'));
                $hasArticleImage = str_starts_with($imagePath, 'uploads/warta/')
                    && basename($imageFilename) === $imageFilename
                    && is_file(FCPATH . $imagePath);
                ?>
                <?php if ($hasArticleImage): ?>
                    <img class="article-photo" src="<?= esc(base_url($imagePath)) ?>" alt="<?= esc($berita['foto_alt'] ?? '') ?>" fetchpriority="high">
                <?php endif; ?>
                <?php if (! empty($berita['kategori'])): ?>
                    <p class="article-category mb-1"><?= esc($berita['kategori']) ?></p>
                <?php endif; ?>
                <h1 class="detail-title"><?= esc($berita['judul']) ?></h1>
                <?php if (! empty($berita['tanggal'])): ?>
                    <p><time datetime="<?= esc($berita['tanggal_iso'], 'attr') ?>"><?= esc($berita['tanggal']) ?></time></p>
                <?php endif; ?>
                <?php if (! empty($berita['ringkasan'])): ?>
                    <p class="section-intro"><?= nl2br(esc($berita['ringkasan'])) ?></p>
                <?php endif; ?>
                <div class="article-content"><?= nl2br(esc($berita['isi'])) ?></div>
            </div>
        </div>
    </div>
</article>
<?= $this->endSection() ?>
