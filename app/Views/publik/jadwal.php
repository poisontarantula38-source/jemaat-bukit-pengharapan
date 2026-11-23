<?php
$providedJadwal = $jadwal ?? null;
$jadwal = is_array($providedJadwal) ? $providedJadwal : [];
$content = is_array($content ?? null) ? $content : [];
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="page_title"><?= esc($content['page_title'] ?? 'Jadwal Ibadah') ?></h1>
        <p class="section-intro" data-live-preview="page_intro"><?= nl2br(esc($content['page_intro'] ?? 'Waktu dan lokasi ibadah Jemaat Bukit Pengharapan.')) ?></p>
    </div>
</section>

<section class="section-block pt-3" aria-labelledby="judul-jadwal">
    <div class="container">
        <article class="card detail-card">
            <div class="card-body">
                <h2 class="surface-title" id="judul-jadwal" data-live-preview="schedule_heading"><?= esc($content['schedule_heading'] ?? 'Jadwal mendatang') ?></h2>
                <?php if ($jadwal === []): ?>
                    <div class="empty-state" role="status">
                        <h3 data-live-preview="schedule_empty_heading"><?= esc($content['schedule_empty_heading'] ?? 'Jadwal ibadah belum tersedia') ?></h3>
                        <p data-live-preview="schedule_empty_text"><?= nl2br(esc($content['schedule_empty_text'] ?? 'Jadwal resmi akan ditampilkan setelah informasi hari, waktu, lokasi, dan pelayan dikonfirmasi.')) ?></p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table schedule-table">
                            <caption class="visually-hidden">Jadwal ibadah Jemaat Bukit Pengharapan</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Hari &amp; tanggal</th>
                                    <th scope="col">Jenis ibadah</th>
                                    <th scope="col">Waktu</th>
                                    <th scope="col">Lokasi</th>
                                    <th scope="col">Pelayan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jadwal as $ibadah): ?>
                                    <tr>
                                        <th scope="row"><?= esc(date('d-m-Y', strtotime((string) $ibadah['service_date']))) ?></th>
                                        <td>
                                            <?= esc($ibadah['service_type'] ?? '') ?>
                                            <?php if (! empty($ibadah['notes'])): ?>
                                                <span class="d-block small text-body-secondary"><?= esc($ibadah['notes']) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= esc(isset($ibadah['service_time']) ? substr((string) $ibadah['service_time'], 0, 5) : '') ?> WIT</td>
                                        <td><?= esc($ibadah['location'] ?? '') ?></td>
                                        <td><?= esc($ibadah['minister'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    </div>
</section>
<?= $this->endSection() ?>