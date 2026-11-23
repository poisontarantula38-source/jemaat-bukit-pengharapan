<?php
$content = is_array($content ?? null) ? $content : [];
$visi = (string) ($visi ?? '');
$misi = (string) ($misi ?? '');
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview-combine="vision_heading,mission_heading"><?= esc(($content['vision_heading'] ?? 'Visi') . ' & ' . ($content['mission_heading'] ?? 'Misi')) ?></h1>
    </div>
</section>

<section class="section-block pt-3">
    <div class="container">
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <article class="card detail-card h-100" aria-labelledby="judul-visi">
                    <div class="card-body">
                        <h2 class="surface-title" id="judul-visi" data-live-preview="vision_heading"><?= esc($content['vision_heading'] ?? 'Visi') ?></h2>
                        <p class="mb-0" data-live-preview="vision_text" data-live-preview-present-if="vision_text" <?= $visi === '' ? 'hidden' : '' ?>><?= nl2br(esc($visi)) ?></p>
                        <div class="empty-state" role="status" data-live-preview-empty-if="vision_text" <?= $visi !== '' ? 'hidden' : '' ?>>
                            <h3 data-live-preview="vision_empty_heading"><?= esc($content['vision_empty_heading'] ?? 'Visi belum tersedia') ?></h3>
                            <p data-live-preview="vision_empty_text"><?= nl2br(esc($content['vision_empty_text'] ?? 'Visi resmi jemaat belum dicantumkan.')) ?></p>
                        </div>
                    </div>
                </article>
            </div>
            <div class="col-12 col-lg-6">
                <article class="card detail-card h-100" aria-labelledby="judul-misi">
                    <div class="card-body">
                        <h2 class="surface-title" id="judul-misi" data-live-preview="mission_heading"><?= esc($content['mission_heading'] ?? 'Misi') ?></h2>
                        <p class="mb-0" data-live-preview="mission_text" data-live-preview-present-if="mission_text" <?= $misi === '' ? 'hidden' : '' ?>><?= nl2br(esc($misi)) ?></p>
                        <div class="empty-state" role="status" data-live-preview-empty-if="mission_text" <?= $misi !== '' ? 'hidden' : '' ?>>
                            <h3 data-live-preview="mission_empty_heading"><?= esc($content['mission_empty_heading'] ?? 'Misi belum tersedia') ?></h3>
                            <p data-live-preview="mission_empty_text"><?= nl2br(esc($content['mission_empty_text'] ?? 'Misi resmi jemaat belum dicantumkan.')) ?></p>
                        </div>
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
