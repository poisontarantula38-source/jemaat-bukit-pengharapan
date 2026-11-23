<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$isEditing = isset($schedule['id']);
$formErrors = is_array($formErrors ?? null) ? $formErrors : [];
?>
<p class="mb-2"><a href="<?= esc(site_url('admin/jadwal')) ?>">&larr; Kembali ke jadwal</a></p>
<h1 class="admin-page-title mb-1"><?= $isEditing ? 'Ubah jadwal ibadah' : 'Tambah jadwal ibadah' ?></h1>
<p class="admin-muted mb-4">Pastikan tanggal, waktu WIT, lokasi, dan pelayan sudah dikonfirmasi sebelum menyimpan.</p>

<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<form class="admin-surface p-4" method="post" action="<?= esc($isEditing ? site_url('admin/jadwal/' . $schedule['id']) : site_url('admin/jadwal')) ?>">
    <?= csrf_field() ?>
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="service_date">Tanggal ibadah</label>
            <input class="form-control<?= isset($formErrors['service_date']) ? ' is-invalid' : '' ?>" id="service_date" name="service_date" type="date" value="<?= esc(old('service_date', $schedule['service_date'] ?? '')) ?>" required>
            <?php if (isset($formErrors['service_date'])): ?><div class="invalid-feedback"><?= esc($formErrors['service_date']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="service_time">Waktu mulai (WIT)</label>
            <input class="form-control<?= isset($formErrors['service_time']) ? ' is-invalid' : '' ?>" id="service_time" name="service_time" type="time" value="<?= esc(old('service_time', substr((string) ($schedule['service_time'] ?? ''), 0, 5))) ?>" required>
            <?php if (isset($formErrors['service_time'])): ?><div class="invalid-feedback"><?= esc($formErrors['service_time']) ?></div><?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="service_type">Jenis ibadah</label>
            <input class="form-control<?= isset($formErrors['service_type']) ? ' is-invalid' : '' ?>" id="service_type" name="service_type" type="text" maxlength="160" value="<?= esc(old('service_type', $schedule['service_type'] ?? '')) ?>" required>
            <?php if (isset($formErrors['service_type'])): ?><div class="invalid-feedback"><?= esc($formErrors['service_type']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="location">Lokasi</label>
            <input class="form-control<?= isset($formErrors['location']) ? ' is-invalid' : '' ?>" id="location" name="location" type="text" maxlength="180" value="<?= esc(old('location', $schedule['location'] ?? 'Gereja Elpido, Latuhalat')) ?>" required>
            <?php if (isset($formErrors['location'])): ?><div class="invalid-feedback"><?= esc($formErrors['location']) ?></div><?php endif; ?>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="minister">Pelayan</label>
            <input class="form-control<?= isset($formErrors['minister']) ? ' is-invalid' : '' ?>" id="minister" name="minister" type="text" maxlength="180" value="<?= esc(old('minister', $schedule['minister'] ?? '')) ?>">
            <?php if (isset($formErrors['minister'])): ?><div class="invalid-feedback"><?= esc($formErrors['minister']) ?></div><?php endif; ?>
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="notes">Catatan (opsional)</label>
            <textarea class="form-control<?= isset($formErrors['notes']) ? ' is-invalid' : '' ?>" id="notes" name="notes" rows="3" maxlength="2000"><?= esc(old('notes', $schedule['notes'] ?? '')) ?></textarea>
            <?php if (isset($formErrors['notes'])): ?><div class="invalid-feedback"><?= esc($formErrors['notes']) ?></div><?php endif; ?>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 mt-4">
        <button class="btn btn-primary" type="submit"><?= $isEditing ? 'Simpan perubahan' : 'Simpan jadwal' ?></button>
        <a class="btn btn-outline-secondary" href="<?= esc(site_url('admin/jadwal')) ?>">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
