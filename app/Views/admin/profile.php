<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php $formErrors = is_array($formErrors ?? null) ? $formErrors : []; ?>
<p class="mb-2"><a href="<?= esc(site_url('admin')) ?>">&larr; Kembali ke dashboard</a></p>
<h1 class="admin-page-title mb-1">Akun saya</h1>
<p class="admin-muted mb-4">Ubah kata sandi akun <?= esc(session()->get('adminUsername') ?? '') ?> secara berkala dan jangan membagikannya.</p>

<?php if (! empty($passwordSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($passwordSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($passwordError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($passwordError) ?></div>
<?php endif; ?>

<form class="admin-surface p-4" method="post" action="<?= esc(site_url('admin/akun/kata-sandi')) ?>">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="current_password">Kata sandi saat ini</label>
        <input class="form-control" id="current_password" name="current_password" type="password" autocomplete="current-password" maxlength="1024" required>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="new_password">Kata sandi baru</label>
        <input class="form-control<?= isset($formErrors['new_password']) ? ' is-invalid' : '' ?>" id="new_password" name="new_password" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
        <?php if (isset($formErrors['new_password'])): ?><div class="invalid-feedback"><?= esc($formErrors['new_password']) ?></div><?php else: ?><div class="form-text">Minimal 12 karakter.</div><?php endif; ?>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold" for="password_confirm">Ulangi kata sandi baru</label>
        <input class="form-control<?= isset($formErrors['password_confirm']) ? ' is-invalid' : '' ?>" id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
        <?php if (isset($formErrors['password_confirm'])): ?><div class="invalid-feedback"><?= esc($formErrors['password_confirm']) ?></div><?php endif; ?>
    </div>
    <button class="btn btn-primary" type="submit">Perbarui kata sandi</button>
</form>
<?= $this->endSection() ?>
