<?php
$content = is_array($content ?? null) ? $content : [];
$sejarah = (string) ($sejarah ?? '');
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="history_heading"><?= esc($content['history_heading'] ?? 'Sejarah Jemaat') ?></h1>
    </div>
</section>

<section class="section-block pt-3">
    <div class="container">
        <article class="card detail-card" aria-labelledby="judul-sejarah">
            <div class="card-body">
                <h2 class="surface-title" id="judul-sejarah" data-live-preview="history_heading"><?= esc($content['history_heading'] ?? 'Sejarah Jemaat') ?></h2>
                <p class="mb-0" data-live-preview="history_text" data-live-preview-present-if="history_text" <?= $sejarah === '' ? 'hidden' : '' ?>><?= nl2br(esc($sejarah)) ?></p>
                <div class="empty-state" role="status" data-live-preview-empty-if="history_text" <?= $sejarah !== '' ? 'hidden' : '' ?>>
                    <h3 data-live-preview="history_empty_heading"><?= esc($content['history_empty_heading'] ?? 'Sejarah jemaat belum tersedia') ?></h3>
                    <p data-live-preview="history_empty_text"><?= nl2br(esc($content['history_empty_text'] ?? 'Narasi sejarah resmi Jemaat Bukit Pengharapan belum dipublikasikan.')) ?></p>
                </div>
            </div>
        </article>
    </div>
</section>
<?= $this->endSection() ?>
