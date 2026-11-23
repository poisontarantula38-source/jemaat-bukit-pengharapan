<?php
$content = is_array($content ?? null) ? $content : [];
$providedStrukturMajelis = $strukturMajelis ?? null;
$strukturMajelis = is_array($providedStrukturMajelis) ? $providedStrukturMajelis : [];
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="board_heading"><?= esc($content['board_heading'] ?? 'Struktur Majelis') ?></h1>
    </div>
</section>

<section class="section-block pt-3">
    <div class="container">
        <article class="card detail-card" aria-labelledby="judul-majelis">
            <div class="card-body">
                <h2 class="surface-title" id="judul-majelis" data-live-preview="board_heading"><?= esc($content['board_heading'] ?? 'Struktur Majelis') ?></h2>
                <div class="empty-state" role="status" data-live-preview-empty-if="board_rows" <?= $strukturMajelis !== [] ? 'hidden' : '' ?>>
                    <h3 data-live-preview="board_empty_heading"><?= esc($content['board_empty_heading'] ?? 'Struktur majelis belum tersedia') ?></h3>
                    <p data-live-preview="board_intro"><?= nl2br(esc($content['board_intro'] ?? 'Nama dan susunan pelayanan majelis resmi belum dipublikasikan.')) ?></p>
                </div>
                <div class="table-responsive" data-live-preview-present-if="board_rows" <?= $strukturMajelis === [] ? 'hidden' : '' ?>>
                    <table class="table align-middle mb-0">
                        <caption class="visually-hidden">Struktur Majelis Jemaat Bukit Pengharapan</caption>
                        <thead>
                            <tr><th scope="col">Nama</th><th scope="col">Jabatan</th></tr>
                        </thead>
                        <tbody data-live-preview-list="board_rows">
                            <?php foreach ($strukturMajelis as $anggota): ?>
                                <tr>
                                    <th scope="row"><?= esc($anggota['nama'] ?? '') ?></th>
                                    <td><?= esc($anggota['jabatan'] ?? '') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </article>
    </div>
</section>
<?= $this->endSection() ?>
