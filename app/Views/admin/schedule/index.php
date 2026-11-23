<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Jadwal ibadah</h1>
        <p class="admin-muted mb-0">Jadwal yang akan datang tampil otomatis di halaman publik.</p>
    </div>
    <a class="btn btn-primary" href="<?= esc(site_url('admin/jadwal/tambah')) ?>">Tambah jadwal</a>
</div>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<?php if ($schedules === []): ?>
    <div class="admin-surface p-4">
        <h2 class="h5 fw-bold">Belum ada jadwal ibadah</h2>
        <p class="admin-muted mb-3">Tambahkan jadwal yang sudah dikonfirmasi agar jemaat dapat melihatnya di situs.</p>
        <a class="btn btn-primary" href="<?= esc(site_url('admin/jadwal/tambah')) ?>">Tambah jadwal pertama</a>
    </div>
<?php else: ?>
    <div class="admin-surface table-responsive">
        <table class="table table-hover align-middle mb-0">
            <caption class="visually-hidden">Daftar jadwal ibadah</caption>
            <thead>
                <tr>
                    <th scope="col">Tanggal &amp; waktu</th>
                    <th scope="col">Ibadah</th>
                    <th scope="col">Lokasi</th>
                    <th scope="col">Pelayan</th>
                    <th scope="col"><span class="visually-hidden">Tindakan</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($schedules as $schedule): ?>
                    <tr>
                        <th scope="row">
                            <?= esc(date('d-m-Y', strtotime((string) $schedule['service_date']))) ?>
                            <span class="d-block fw-normal text-body-secondary"><?= esc(substr((string) $schedule['service_time'], 0, 5)) ?> WIT</span>
                        </th>
                        <td>
                            <span class="fw-semibold"><?= esc($schedule['service_type']) ?></span>
                            <?php if (! empty($schedule['notes'])): ?>
                                <span class="d-block small text-body-secondary"><?= esc($schedule['notes']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($schedule['location']) ?></td>
                        <td><?= esc($schedule['minister'] ?: 'Belum diisi') ?></td>
                        <td class="text-end text-nowrap">
                            <a class="btn btn-sm btn-outline-primary" href="<?= esc(site_url('admin/jadwal/' . $schedule['id'] . '/ubah')) ?>">Ubah</a>
                            <form class="d-inline" method="post" action="<?= esc(site_url('admin/jadwal/' . $schedule['id'] . '/hapus')) ?>" data-confirm-delete="Hapus jadwal ini? Jadwal tidak lagi tampil di situs.">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="mt-3"><?= $pager->links() ?></div>
    <?php endif; ?>
<?php endif; ?>
<?= $this->endSection() ?>
