<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$isEditing = isset($article['id']);
$formAction = $isEditing
    ? site_url('admin/warta/' . $article['id'])
    : site_url('admin/warta');
$status = old('status', ! empty($article['is_published']) ? 'published' : 'draft');
?>
<div class="mb-4">
    <a class="small" href="<?= esc(site_url('admin/warta')) ?>">Kembali ke daftar warta</a>
    <h1 class="admin-page-title mt-2 mb-1"><?= $isEditing ? 'Ubah warta' : 'Tambah warta' ?></h1>
    <p class="admin-muted mb-0">Isi informasi yang akan dibaca oleh jemaat.</p>
</div>

<?php if (! empty($formErrors)): ?>
    <div class="alert alert-danger" role="alert">
        <p class="fw-semibold mb-1">Periksa kembali isian berikut:</p>
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

<form class="admin-surface p-3 p-md-4" method="post" action="<?= esc($formAction) ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="judul">Judul</label>
        <input class="form-control" id="judul" name="judul" type="text" maxlength="180" required value="<?= esc(old('judul', $article['title'] ?? ''), 'attr') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="kategori">Kategori <span class="admin-muted fw-normal">(opsional)</span></label>
        <input class="form-control" id="kategori" name="kategori" type="text" maxlength="80" value="<?= esc(old('kategori', $article['category'] ?? ''), 'attr') ?>">
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="ringkasan">Ringkasan <span class="admin-muted fw-normal">(opsional)</span></label>
        <textarea class="form-control" id="ringkasan" name="ringkasan" rows="3" maxlength="600"><?= esc(old('ringkasan', $article['summary'] ?? '')) ?></textarea>
        <div class="form-text">Ringkasan muncul pada daftar warta dan sorotan beranda.</div>
    </div>
    <?php
    $imageEditorName = 'foto';
    $imageEditorId = 'foto';
    $imageEditorSource = $imageSource ?? '';
    $imageEditorAspect = '16/9';
    $imageEditorRequired = false;
    ?>
    <div class="mb-3">
        <h2 class="h6 fw-bold">Foto warta <span class="admin-muted fw-normal">(opsional)</span></h2>
        <?php if ($isEditing && $imageEditorSource !== ''): ?>
            <label class="form-label fw-semibold" for="foto_alt">Teks alternatif foto</label>
            <input class="form-control mb-3" id="foto_alt" name="foto_alt" type="text" maxlength="250" value="<?= esc(old('foto_alt', $article['image_alt'] ?? ''), 'attr') ?>">
        <?php else: ?>
            <label class="form-label fw-semibold visually-hidden" for="foto_alt">Teks alternatif foto</label>
            <input class="form-control mb-3" id="foto_alt" name="foto_alt" type="text" maxlength="250" value="<?= esc(old('foto_alt', $article['image_alt'] ?? ''), 'attr') ?>" placeholder="Teks alternatif foto (opsional)">
        <?php endif; ?>
        <?= view('partials/admin_image_editor', [
            'imageEditorName' => $imageEditorName,
            'imageEditorId' => $imageEditorId,
            'imageEditorSource' => $imageEditorSource,
            'imageEditorAspect' => $imageEditorAspect,
            'imageEditorRequired' => $imageEditorRequired,
        ]) ?>
        <?php if ($isEditing && $imageEditorSource !== ''): ?>
            <div class="form-check mt-2">
                <input class="form-check-input" id="hapus_foto" name="hapus_foto" type="checkbox" value="1">
                <label class="form-check-label" for="hapus_foto">Hapus foto dari warta ini</label>
            </div>
        <?php endif; ?>
    </div>
    <div class="mb-3">
        <label class="form-label fw-semibold" for="isi">Isi warta</label>
        <textarea class="form-control" id="isi" name="isi" rows="10" maxlength="50000" required><?= esc(old('isi', $article['content'] ?? '')) ?></textarea>
        <div class="form-text">Teks ditampilkan apa adanya dengan pemformatan baris.</div>
    </div>
    <div class="mb-4">
        <label class="form-label fw-semibold" for="status">Status</label>
        <select class="form-select" id="status" name="status" required>
            <option value="draft"<?= $status === 'draft' ? ' selected' : '' ?>>Simpan sebagai draf</option>
            <option value="published"<?= $status === 'published' ? ' selected' : '' ?>>Terbitkan ke halaman publik</option>
        </select>
    </div>
    <div class="d-flex flex-column flex-sm-row gap-2">
        <button class="btn btn-primary fw-semibold" type="submit">Simpan warta</button>
        <a class="btn btn-outline-secondary" href="<?= esc(site_url('admin/warta')) ?>">Batal</a>
    </div>
</form>
<?= $this->endSection() ?>
