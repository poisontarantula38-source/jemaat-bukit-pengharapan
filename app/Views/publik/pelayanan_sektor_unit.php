<?php
$content = is_array($content ?? null) ? $content : [];
$providedSektorUnit = $sektorUnit ?? null;
$sektorUnit = is_array($providedSektorUnit) ? $providedSektorUnit : [];
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="sectors_heading"><?= esc($content['sectors_heading'] ?? 'Sektor & Unit Pelayanan') ?></h1>
    </div>
</section>

<section class="section-block pt-3">
    <div class="container">
            <div class="empty-state" role="status" data-live-preview-empty-if="sector_items" <?= $sektorUnit !== [] ? 'hidden' : '' ?>>
                <h2 data-live-preview="sectors_empty_heading"><?= esc($content['sectors_empty_heading'] ?? 'Informasi sektor dan unit belum tersedia') ?></h2>
                <p data-live-preview="sectors_empty_text"><?= nl2br(esc($content['sectors_empty_text'] ?? 'Daftar sektor dan unit pelayanan resmi belum dipublikasikan.')) ?></p>
            </div>
            <ul class="list-group list-group-flush" data-live-preview-list="sector_items" data-live-preview-present-if="sector_items" <?= $sektorUnit === [] ? 'hidden' : '' ?>>
                <?php foreach ($sektorUnit as $unit): ?>
                    <li class="list-group-item bg-transparent px-0 py-3"><?= esc($unit['nama'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
    </div>
</section>
<?= $this->endSection() ?>
