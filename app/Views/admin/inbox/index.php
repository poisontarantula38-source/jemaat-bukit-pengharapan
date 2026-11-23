<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$statusLabels = ['baru' => 'Baru', 'diproses' => 'Sedang ditangani', 'selesai' => 'Selesai'];
$admins = is_array($admins ?? null) ? $admins : [];
?>
<div class="d-flex flex-wrap align-items-end justify-content-between gap-3 mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Kotak pesan</h1>
        <p class="admin-muted mb-0">Pesan masuk dapat dilihat semua pengelola. Tetapkan penanggung jawab agar tidak terlewat.</p>
    </div>
    <form method="get" action="<?= esc(site_url('admin/pesan')) ?>">
        <label class="form-label fw-semibold mb-1" for="status-filter">Tampilkan</label>
        <select class="form-select" id="status-filter" name="status">
            <option value="">Semua pesan</option>
            <?php foreach ($statusLabels as $value => $label): ?>
                <option value="<?= esc($value) ?>"<?= $selectedStatus === $value ? ' selected' : '' ?>><?= esc($label) ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-outline-secondary mt-2" type="submit">Terapkan filter</button>
    </form>
</div>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<?php if ($messages === []): ?>
    <div class="admin-surface p-4">
        <h2 class="h5 fw-bold">Belum ada pesan di sini</h2>
        <p class="admin-muted mb-0"><?= $selectedStatus === '' ? 'Pesan yang dikirim dari formulir Kontak akan tampil di halaman ini.' : 'Tidak ada pesan dengan status tersebut.' ?></p>
    </div>
<?php else: ?>
    <div class="d-grid gap-3">
        <?php foreach ($messages as $message): ?>
            <?php
            $currentStatus = (string) $message['status'];
            $assignedId = $message['assigned_admin_id'] === null ? '' : (string) $message['assigned_admin_id'];
            $created = new DateTimeImmutable(
                (string) $message['created_at'],
                new DateTimeZone(date_default_timezone_get()),
            );
            $created = $created->setTimezone(new DateTimeZone('Asia/Jayapura'));
            ?>
            <article class="admin-surface p-3 p-md-4">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <h2 class="h5 fw-bold mb-1"><?= esc($message['subject']) ?></h2>
                        <p class="admin-muted mb-2">
                            <?= esc($message['sender_name']) ?>
                            · <a href="mailto:<?= esc($message['sender_email'], 'attr') ?>"><?= esc($message['sender_email']) ?></a>
                            · <?= esc($created->format('d-m-Y H:i') . ' WIT') ?>
                        </p>
                    </div>
                    <span class="badge rounded-pill text-bg-<?= $currentStatus === 'selesai' ? 'success' : ($currentStatus === 'diproses' ? 'primary' : 'warning') ?>">
                        <?= esc($statusLabels[$currentStatus] ?? 'Status tidak dikenal') ?>
                    </span>
                </div>
                <p class="mb-3"><?= nl2br(esc($message['message'])) ?></p>
                <?php if ($assignedId === '' && ! empty($message['assigned_admin_username'])): ?>
                    <p class="small text-body-secondary">Sebelumnya ditugaskan kepada <?= esc($message['assigned_admin_username']) ?> (akun sudah dihapus).</p>
                <?php endif; ?>
                <form class="row g-2 align-items-end" method="post" action="<?= esc(site_url('admin/pesan/' . $message['id'])) ?>">
                    <?= csrf_field() ?>
                    <div class="col-sm-5 col-lg-4">
                        <label class="form-label fw-semibold mb-1" for="assignee-<?= esc((string) $message['id']) ?>">Penanggung jawab</label>
                        <select class="form-select" id="assignee-<?= esc((string) $message['id']) ?>" name="assigned_admin_id">
                            <option value="">Belum ditugaskan</option>
                            <?php foreach ($admins as $admin): ?>
                                <?php $isInactiveAssignee = (int) $admin['is_active'] !== 1; ?>
                                <option value="<?= esc((string) $admin['id']) ?>"<?= $assignedId === (string) $admin['id'] ? ' selected' : '' ?><?= $isInactiveAssignee ? ' disabled' : '' ?>>
                                    <?= esc($admin['username'] . ($isInactiveAssignee ? ' (nonaktif)' : '')) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-4 col-lg-3">
                        <label class="form-label fw-semibold mb-1" for="status-<?= esc((string) $message['id']) ?>">Status</label>
                        <select class="form-select" id="status-<?= esc((string) $message['id']) ?>" name="status">
                            <?php foreach ($statusLabels as $value => $label): ?>
                                <option value="<?= esc($value) ?>"<?= $currentStatus === $value ? ' selected' : '' ?>><?= esc($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-auto">
                        <button class="btn btn-primary" type="submit">Simpan tindak lanjut</button>
                    </div>
                </form>
                <form class="mt-2" method="post" action="<?= esc(site_url('admin/pesan/' . $message['id'] . '/hapus')) ?>" data-confirm-delete="Hapus pesan ini secara permanen? Tindakan ini tidak dapat dibatalkan.">
                    <?= csrf_field() ?>
                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus pesan</button>
                </form>
            </article>
        <?php endforeach; ?>
    </div>
    <?php if ($pager->getPageCount() > 1): ?>
        <div class="mt-3"><?= $pager->links() ?></div>
    <?php endif; ?>
<?php endif; ?>
<?= $this->endSection() ?>
