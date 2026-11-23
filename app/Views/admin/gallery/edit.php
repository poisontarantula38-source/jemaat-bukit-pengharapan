<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$imageEditorName = 'foto';
$imageEditorId = 'foto';
$imageEditorSource = $imageSource ?? '';
$imageEditorAspect = '4/3';
$imageEditorRequired = false;
?>
<div class="mb-4">
    <?php $returnPage = (string) ($returnPage ?? ''); ?>
    <a class="small" href="<?= esc($returnPage !== '' ? site_url('admin/konten/' . $returnPage) : site_url('admin/galeri')) ?>">Kembali ke <?= esc($returnPage !== '' ? 'Editor halaman' : 'galeri') ?></a>
    <h1 class="admin-page-title mt-2 mb-1">Ubah foto galeri</h1>
    <p class="admin-muted mb-0">Ganti atau atur foto, lalu perbarui keterangannya.</p>
</div>

<?php if (! empty($formErrors)): ?>
    <div class="alert alert-danger" role="alert">
        <p class="fw-semibold mb-1">Foto belum dapat diperbarui:</p>
        <ul class="mb-0">
            <?php foreach ($formErrors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<form class="admin-surface p-3 p-md-4" method="post" action="<?= esc(site_url('admin/galeri/' . $photo['id'] . '/ubah')) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <?php if ($returnPage !== ''): ?>
        <input type="hidden" name="return_page" value="<?= esc($returnPage, 'attr') ?>">
    <?php endif; ?>
    <div class="mb-3">
        <?= view('partials/admin_image_editor', [
            'imageEditorName' => $imageEditorName,
            'imageEditorId' => $imageEditorId,
            'imageEditorSource' => $imageEditorSource,
            'imageEditorAspect' => $imageEditorAspect,
            'imageEditorRequired' => $imageEditorRequired,
        ]) ?>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="caption">Keterangan foto</label>
        <input class="form-control" id="caption" name="caption" type="text" maxlength="180" required value="<?= esc(old('caption', $photo['caption'] ?? ''), 'attr') ?>">
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold" for="alt_text">Teks alternatif <span class="admin-muted fw-normal">(opsional)</span></label>
        <input class="form-control" id="alt_text" name="alt_text" type="text" maxlength="250" value="<?= esc(old('alt_text', $photo['alt_text'] ?? ''), 'attr') ?>">
        <div class="form-text">Jelaskan isi foto untuk pengunjung yang menggunakan pembaca layar.</div>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2">
        <button class="btn btn-primary fw-semibold" type="submit">Simpan perubahan</button>
        <a class="btn btn-outline-secondary" href="<?= esc(site_url('admin/galeri')) ?>">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
