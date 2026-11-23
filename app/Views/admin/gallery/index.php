<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-sm-row align-items-sm-end justify-content-between gap-3 mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Galeri Foto</h1>
        <p class="admin-muted mb-0">Foto yang diunggah langsung tampil di halaman publik Galeri.</p>
    </div>
    <a class="btn btn-outline-primary fw-semibold" href="<?= esc(site_url('galeri')) ?>" target="_blank" rel="noopener noreferrer">Lihat galeri publik</a>
</div>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>
<?php if (! empty($uploadErrors)): ?>
    <div class="alert alert-danger" role="alert">
        <p class="fw-semibold mb-1">Foto belum dapat diunggah:</p>
        <ul class="mb-0">
            <?php foreach ($uploadErrors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<section class="admin-surface p-3 p-md-4 mb-4" aria-labelledby="judul-unggah-foto">
    <h2 class="h5 fw-bold mb-1" id="judul-unggah-foto">Tambah foto</h2>
    <p class="admin-muted small">Pilih foto, atur potongannya, lalu simpan ke galeri.</p>
    <form method="post" action="<?= esc(site_url('admin/galeri')) ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <?php
        $imageEditorName = 'foto';
        $imageEditorId = 'foto';
        $imageEditorSource = '';
        $imageEditorAspect = '4/3';
        $imageEditorRequired = true;
        ?>
        <?= view('partials/admin_image_editor', [
            'imageEditorName' => $imageEditorName,
            'imageEditorId' => $imageEditorId,
            'imageEditorSource' => $imageEditorSource,
            'imageEditorAspect' => $imageEditorAspect,
            'imageEditorRequired' => $imageEditorRequired,
        ]) ?>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="caption">Keterangan foto</label>
            <input class="form-control" id="caption" name="caption" type="text" maxlength="180" value="<?= esc(old('caption', ''), 'attr') ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold" for="alt_text">Teks alternatif <span class="admin-muted fw-normal">(opsional)</span></label>
            <input class="form-control" id="alt_text" name="alt_text" type="text" maxlength="250" value="<?= esc(old('alt_text', ''), 'attr') ?>">
            <div class="form-text">Jelaskan isi foto untuk pengunjung yang menggunakan pembaca layar. Jika dikosongkan, keterangan foto digunakan.</div>
        </div>
        <button class="btn btn-primary fw-semibold" type="submit">Unggah foto</button>
    </form>
</section>

<section aria-labelledby="judul-daftar-foto">
    <div class="d-flex align-items-baseline justify-content-between gap-3 mb-3">
        <h2 class="h5 fw-bold mb-0" id="judul-daftar-foto">Foto yang sudah diunggah</h2>
    </div>
    <?php if ($photos === []): ?>
        <div class="admin-surface p-4">
            <h3 class="h6 fw-bold">Belum ada foto</h3>
            <p class="admin-muted mb-0">Unggah foto pertama untuk mulai mengisi galeri Jemaat Bukit Pengharapan.</p>
        </div>
    <?php else: ?>
        <div class="admin-gallery-grid">
            <?php foreach ($photos as $photo): ?>
                <?php
                $filename = (string) ($photo['filename'] ?? '');
                $photoPath = 'uploads/gallery/' . basename($filename);
                $hasPhotoFile = basename($filename) === $filename && is_file(FCPATH . $photoPath);
                ?>
                <article class="admin-surface admin-gallery-card">
                    <?php if ($hasPhotoFile): ?>
                        <img class="admin-gallery-thumb" src="<?= esc(base_url($photoPath)) ?>" alt="<?= esc($photo['alt_text'] ?? '') ?>" loading="lazy">
                    <?php else: ?>
                        <div class="admin-gallery-thumb admin-gallery-missing" role="img" aria-label="File foto tidak ditemukan">File foto tidak ditemukan</div>
                    <?php endif; ?>
                    <div class="p-3">
                        <h3 class="h6 fw-bold mb-1"><?= esc($photo['caption'] ?? '') ?></h3>
                        <p class="admin-muted small mb-3"><?= ! empty($photo['created_at']) ? esc(date('d-m-Y H:i', strtotime($photo['created_at']))) : 'Tanggal tidak tersedia' ?></p>
                        <div class="d-flex flex-wrap gap-2">
                            <a class="btn btn-sm btn-outline-primary" href="<?= esc(site_url('admin/galeri/' . $photo['id'] . '/ubah')) ?>">Ubah foto</a>
                            <form method="post" action="<?= esc(site_url('admin/galeri/' . $photo['id'] . '/hapus')) ?>" data-confirm-delete="Hapus foto ini dari galeri? Tindakan ini tidak dapat dibatalkan.">
                                <?= csrf_field() ?>
                                <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                            </form>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <?php if (isset($pager)): ?>
            <?= $pager->links() ?>
        <?php endif; ?>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
