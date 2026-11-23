<?php
$content = is_array($content ?? null) ? $content : [];
$providedWadahPelayanan = $wadahPelayanan ?? null;
$wadahPelayanan = is_array($providedWadahPelayanan) ? $providedWadahPelayanan : [];
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="groups_heading"><?= esc($content['groups_heading'] ?? 'Wadah Pelayanan') ?></h1>
        <p class="section-intro" data-live-preview="groups_intro"><?= nl2br(esc($content['groups_intro'] ?? 'Informasi wadah pelayanan Jemaat Bukit Pengharapan.')) ?></p>
    </div>
</section>

<section class="section-block pt-3">
    <div class="container">
            <div class="empty-state" role="status" data-live-preview-empty-if="service_items" <?= $wadahPelayanan !== [] ? 'hidden' : '' ?>>
                <h2 data-live-preview="groups_empty_heading"><?= esc($content['groups_empty_heading'] ?? 'Wadah pelayanan belum tersedia') ?></h2>
                <p data-live-preview="groups_empty_text"><?= nl2br(esc($content['groups_empty_text'] ?? 'Informasi wadah pelayanan resmi belum dipublikasikan.')) ?></p>
            </div>
            <ul class="list-group list-group-flush" data-live-preview-list="service_items" data-live-preview-present-if="service_items" <?= $wadahPelayanan === [] ? 'hidden' : '' ?>>
                <?php foreach ($wadahPelayanan as $wadah): ?>
                    <li class="list-group-item bg-transparent px-0 py-3"><?= esc($wadah['nama'] ?? '') ?></li>
                <?php endforeach; ?>
            </ul>
    </div>
</section>
<?= $this->endSection() ?>
