<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$formErrors = is_array($formErrors ?? null) ? $formErrors : [];
$currentAdminId = (int) session()->get('adminUserId');
?>
<div class="mb-4">
    <h1 class="admin-page-title mb-1">Akun pengelola</h1>
    <p class="admin-muted mb-0">Tidak ada pendaftaran publik. Admin utama membuat akun untuk pengelola yang dipercaya.</p>
</div>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<section class="admin-surface p-3 p-md-4 mb-4" aria-labelledby="judul-buat-akun">
    <h2 class="h5 fw-bold" id="judul-buat-akun">Buat akun pengelola</h2>
    <p class="admin-muted">Gunakan akun terpisah untuk setiap orang. Sampaikan kata sandi awal secara pribadi dan minta pengelola menggantinya setelah masuk.</p>
    <form method="post" action="<?= esc(site_url('admin/pengelola')) ?>">
        <?= csrf_field() ?>
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="username">Nama pengguna</label>
                <input class="form-control<?= isset($formErrors['username']) ? ' is-invalid' : '' ?>" id="username" name="username" type="text" autocomplete="off" minlength="3" maxlength="80" value="<?= esc(old('username', $submittedUsername), 'attr') ?>" required>
                <?php if (isset($formErrors['username'])): ?><div class="invalid-feedback"><?= esc($formErrors['username']) ?></div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="password">Kata sandi awal</label>
                <input class="form-control<?= isset($formErrors['password']) ? ' is-invalid' : '' ?>" id="password" name="password" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
                <?php if (isset($formErrors['password'])): ?><div class="invalid-feedback"><?= esc($formErrors['password']) ?></div><?php else: ?><div class="form-text">Minimal 12 karakter.</div><?php endif; ?>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold" for="password_confirm">Ulangi kata sandi</label>
                <input class="form-control<?= isset($formErrors['password_confirm']) ? ' is-invalid' : '' ?>" id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
                <?php if (isset($formErrors['password_confirm'])): ?><div class="invalid-feedback"><?= esc($formErrors['password_confirm']) ?></div><?php endif; ?>
            </div>
        </div>
        <button class="btn btn-primary mt-3" type="submit">Buat akun pengelola</button>
    </form>
</section>

<section aria-labelledby="judul-akun-aktif">
    <h2 class="h5 fw-bold mb-3" id="judul-akun-aktif">Akun yang terdaftar</h2>
    <div class="admin-surface table-responsive">
        <table class="table align-middle mb-0">
            <caption class="visually-hidden">Daftar akun pengelola situs</caption>
            <thead>
                <tr>
                    <th scope="col">Nama pengguna</th>
                    <th scope="col">Peran</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="visually-hidden">Tindakan</span></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($admins as $admin): ?>
                    <tr>
                        <th scope="row">
                            <?= esc($admin['username']) ?>
                            <?php if ((int) $admin['id'] === $currentAdminId): ?><span class="text-body-secondary fw-normal">(akun Anda)</span><?php endif; ?>
                        </th>
                        <td><?= $admin['role'] === 'superadmin' ? 'Admin utama' : 'Pengelola' ?></td>
                        <td><?= (int) $admin['is_active'] === 1 ? 'Aktif' : 'Nonaktif' ?></td>
                        <td class="text-end">
                            <div class="d-flex flex-wrap justify-content-end gap-2">
                            <?php if ((int) $admin['id'] !== $currentAdminId): ?>
                                <form method="post" action="<?= esc(site_url('admin/pengelola/' . $admin['id'] . '/status')) ?>">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="is_active" value="<?= (int) $admin['is_active'] === 1 ? '0' : '1' ?>">
                                    <button class="btn btn-sm <?= (int) $admin['is_active'] === 1 ? 'btn-outline-danger' : 'btn-outline-primary' ?>" type="submit">
                                        <?= (int) $admin['is_active'] === 1 ? 'Nonaktifkan' : 'Aktifkan' ?>
                                    </button>
                                </form>
                                <form method="post" action="<?= esc(site_url('admin/pengelola/' . $admin['id'] . '/hapus')) ?>" data-confirm-delete="Hapus akun <?= esc($admin['username'], 'attr') ?> secara permanen? Riwayat pesan tetap tersimpan, tetapi akun tidak dapat dipulihkan.">
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            <?php else: ?>
                                <span class="text-body-secondary">Akun yang sedang digunakan</span>
                            <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?= $this->endSection() ?>
