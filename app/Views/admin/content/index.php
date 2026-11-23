<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$movableSections = is_array($movableSections ?? null) ? $movableSections : [];
$sectionOrder = is_array($sectionOrder ?? null) ? $sectionOrder : [];
$editorGroups = [
    'Beranda' => ['beranda'],
    'Profil' => ['profil-sejarah', 'profil-visi-misi', 'profil-struktur-majelis'],
    'Pelayanan' => ['pelayanan-wadah', 'pelayanan-sektor-unit'],
    'Informasi' => ['jadwal', 'warta', 'galeri', 'kontak'],
];
if ($movableSections !== []) {
    $sectionsByLabel = [];
    foreach ($sections as $sectionTitle => $fields) {
        $sectionId = array_search($sectionTitle, $movableSections, true);
        if ($sectionId !== false) {
            $sectionsByLabel[$sectionId] = [$sectionTitle, $fields];
        }
    }

    $orderedSections = [];
    foreach ($sectionOrder as $sectionId) {
        if (isset($sectionsByLabel[$sectionId])) {
            $orderedSections[$sectionsByLabel[$sectionId][0]] = $sectionsByLabel[$sectionId][1];
        }
    }
    $sections = $orderedSections;
}
?>
<header class="content-editor-header">
    <div>
        <h1 class="admin-page-title mb-1">Editor halaman</h1>
        <p class="admin-muted mb-0">Pilih satu halaman, edit isinya, lalu simpan setelah memeriksa pratinjau.</p>
    </div>
</header>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>
<?php if (! empty($uploadErrors)): ?>
    <div class="alert alert-danger" role="alert">
        <p class="fw-semibold mb-1">Foto belum dapat diproses:</p>
        <ul class="mb-0">
            <?php foreach ($uploadErrors as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
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

<nav class="content-page-nav mb-4" aria-label="Pilih halaman publik yang ingin diedit">
    <?php foreach ($editorGroups as $groupLabel => $pageKeys): ?>
        <div class="content-page-group">
            <h2 class="content-page-group-label"><?= esc($groupLabel) ?></h2>
            <div class="content-page-group-links">
                <?php foreach ($pageKeys as $pageKey): ?>
                    <?php $page = $pages[$pageKey]; ?>
                    <a class="btn btn-sm <?= $pageKey === $selectedPage ? 'btn-primary' : 'btn-outline-secondary' ?>"
                        href="<?= esc(site_url('admin/konten/' . $pageKey)) ?>"
                        <?= $pageKey === $selectedPage ? 'aria-current="page"' : '' ?>>
                        <?= esc($page['label']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>
</nav>

<div class="live-editor-layout" data-live-editor data-preview-route="<?= esc($previewRoute, 'attr') ?>">
    <section class="live-preview-panel" aria-labelledby="judul-pratinjau">
        <div class="live-preview-heading">
            <div>
                <h2 id="judul-pratinjau">Pratinjau langsung</h2>
                <span class="live-preview-state" data-preview-state aria-live="polite">
                    <span class="live-preview-state-dot" aria-hidden="true"></span>
                    <span data-preview-state-text><?= esc('Memuat pratinjau ' . $selectedPageLabel) ?></span>
                </span>
            </div>
            <div class="preview-device-switch" role="group" aria-label="Ukuran pratinjau">
                <button type="button" data-preview-width="desktop" aria-pressed="true" aria-label="Pratinjau desktop">Desktop</button>
                <button type="button" data-preview-width="mobile" aria-pressed="false" aria-label="Pratinjau ponsel">Ponsel</button>
            </div>
        </div>
        <div class="live-preview-frame-wrap">
            <iframe
                class="live-preview-frame"
                data-preview-frame
                src="<?= esc($previewRoute, 'attr') ?>"
                title="<?= esc('Pratinjau halaman ' . $selectedPageLabel, 'attr') ?>"
                loading="eager"></iframe>
            <div class="live-preview-loading" data-preview-loading role="status">Memuat pratinjau situs…</div>
        </div>
        <p class="live-preview-note mb-0">Pratinjau ini khusus untuk halaman <?= esc($selectedPageLabel) ?>. Perubahan baru tampil ke pengunjung setelah disimpan.</p>
    </section>

    <form class="live-editor-form" id="siteContentForm" data-live-editor-form method="post" action="<?= esc(site_url('admin/konten/' . $selectedPage)) ?>">
        <?= csrf_field() ?>
        <div class="live-editor-toolbar">
            <div>
                <h2>Edit <?= esc($selectedPageLabel) ?></h2>
                <span class="live-editor-save-state" data-save-state aria-live="polite">Semua perubahan tersimpan</span>
            </div>
            <button class="btn btn-primary fw-semibold" type="submit">Simpan</button>
        </div>

        <div class="live-editor-fields">
            <p class="live-editor-guidance">
                Semua isian berikut hanya mengubah halaman <?= esc($selectedPageLabel) ?> yang tampil di pratinjau. Isian bertanda “saat belum tersedia” hanya muncul jika konten utamanya kosong.
            </p>
            <?php foreach ($sections as $sectionTitle => $fields): ?>
                <?php $sectionId = array_search($sectionTitle, $movableSections, true); ?>
                <section class="content-edit-section" aria-labelledby="<?= esc('judul-' . url_title($sectionTitle, '-', true), 'attr') ?>">
                    <div class="content-edit-heading-row">
                        <h3 class="content-edit-heading" id="<?= esc('judul-' . url_title($sectionTitle, '-', true), 'attr') ?>"><?= esc($sectionTitle) ?></h3>
                        <?php if ($sectionId !== false): ?>
                            <?php $sectionIndex = array_search($sectionId, $sectionOrder, true); ?>
                            <div class="content-section-move" aria-label="<?= esc('Atur letak bagian ' . $sectionTitle, 'attr') ?>">
                                <button class="btn btn-sm btn-outline-secondary" type="submit" formaction="<?= esc(site_url('admin/konten/' . $selectedPage . '/geser')) ?>" formmethod="post" name="move" value="<?= esc($sectionId . ':up', 'attr') ?>" formnovalidate aria-label="<?= esc('Naikkan bagian ' . $sectionTitle, 'attr') ?>" <?= $sectionIndex === 0 ? 'disabled' : '' ?>>Naik</button>
                                <button class="btn btn-sm btn-outline-secondary" type="submit" formaction="<?= esc(site_url('admin/konten/' . $selectedPage . '/geser')) ?>" formmethod="post" name="move" value="<?= esc($sectionId . ':down', 'attr') ?>" formnovalidate aria-label="<?= esc('Turunkan bagian ' . $sectionTitle, 'attr') ?>" <?= $sectionIndex === count($sectionOrder) - 1 ? 'disabled' : '' ?>>Turun</button>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php foreach ($fields as $field): ?>
                        <div class="mb-3">
                            <label class="form-label fw-semibold" for="<?= esc($field['input'], 'attr') ?>"><?= esc($field['label']) ?></label>
                            <?php if ((int) $field['rows'] === 1): ?>
                                <input class="form-control" id="<?= esc($field['input'], 'attr') ?>" name="<?= esc($field['input'], 'attr') ?>" type="text" maxlength="2000" value="<?= esc(old($field['input'], $field['value']), 'attr') ?>">
                            <?php else: ?>
                                <textarea class="form-control" id="<?= esc($field['input'], 'attr') ?>" name="<?= esc($field['input'], 'attr') ?>" rows="<?= esc((string) $field['rows'], 'attr') ?>" maxlength="2000"><?= esc(old($field['input'], $field['value'])) ?></textarea>
                            <?php endif; ?>
                            <?php if (! empty($field['help'])): ?>
                                <div class="form-text"><?= esc($field['help']) ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        </div>

        <div class="live-editor-actions">
            <button class="btn btn-primary fw-semibold" type="submit">Simpan perubahan</button>
            <a class="btn btn-outline-secondary" href="<?= esc($previewRoute) ?>" target="_blank" rel="noopener noreferrer">Buka halaman publik</a>
        </div>
    </form>
</div>

<?php if (in_array($selectedPage, ['beranda', 'galeri', 'warta'], true)): ?>
<section class="live-media-manager" aria-labelledby="judul-kelola-foto">
    <div class="live-media-heading">
        <div>
            <h2 id="judul-kelola-foto">
                <?= $selectedPage === 'galeri'
                    ? 'Kelola foto Galeri'
                    : ($selectedPage === 'warta' ? 'Kelola foto Warta & Berita' : 'Kelola foto Beranda & Galeri') ?>
            </h2>
            <p class="admin-muted mb-0">
                <?= $selectedPage === 'warta'
                    ? 'Tambahkan atau ubah foto pada artikel Warta & Berita.'
                    : 'Kelola foto yang tampil pada pratinjau dan halaman publik.' ?>
            </p>
        </div>
        <?php if ($selectedPage === 'warta'): ?>
            <a class="btn btn-sm btn-outline-secondary" href="<?= esc(site_url('admin/warta')) ?>">Buka daftar Warta</a>
        <?php endif; ?>
    </div>

    <?php if ($selectedPage === 'beranda'): ?>
    <div class="live-home-images">
        <?php foreach ([
            'hero' => [
                'label' => 'Foto banner Beranda',
                'description' => 'Foto utama yang tampil pada bagian atas Beranda.',
                'source' => $heroImagePath,
            ],
            'profile' => [
                'label' => 'Foto profil Beranda',
                'description' => 'Foto gedung pada ringkasan Profil di Beranda.',
                'source' => $profileImagePath,
            ],
        ] as $slot => $image): ?>
            <?php
            $imageEditorName = 'foto';
            $imageEditorId = 'home-image-' . $slot;
            $imageEditorSource = $image['source'] !== '' ? base_url($image['source']) : '';
            $imageEditorAspect = $slot === 'hero' ? '16/9' : '4/3';
            $imageEditorRequired = false;
            ?>
            <article class="live-home-image">
                <div>
                    <h3><?= esc($image['label']) ?></h3>
                    <p class="admin-muted"><?= esc($image['description']) ?></p>
                </div>
                <?php if ($image['source'] !== ''): ?>
                    <img class="live-home-image-thumb" src="<?= esc(base_url($image['source'])) ?>" alt="<?= esc($image['label']) ?>" data-live-media-image="<?= esc($slot, 'attr') ?>">
                <?php else: ?>
                    <p class="small text-danger" role="status">Foto belum tersedia. Unggah foto pengganti.</p>
                <?php endif; ?>
                <form method="post" action="<?= esc(site_url('admin/konten/beranda/foto')) ?>" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="image_slot" value="<?= esc($slot, 'attr') ?>">
                    <?= view('partials/admin_image_editor', [
                        'imageEditorName' => $imageEditorName,
                        'imageEditorId' => $imageEditorId,
                        'imageEditorSource' => $imageEditorSource,
                        'imageEditorAspect' => $imageEditorAspect,
                        'imageEditorRequired' => $imageEditorRequired,
                        'imagePreviewKey' => $slot === 'hero' ? 'hero_image' : 'profile_image',
                    ]) ?>
                    <button class="btn btn-primary mt-3" type="submit">Simpan foto</button>
                </form>
                <?php if (str_starts_with($image['source'], 'uploads/site/')): ?>
                    <form class="mt-2" method="post" action="<?= esc(site_url('admin/konten/beranda/foto/hapus')) ?>" data-confirm-delete="Kembalikan foto ini ke gambar bawaan?">
                        <?= csrf_field() ?>
                        <input type="hidden" name="image_slot" value="<?= esc($slot, 'attr') ?>">
                        <button class="btn btn-sm btn-outline-secondary" type="submit">Gunakan foto bawaan</button>
                    </form>
                <?php else: ?>
                    <p class="form-text mb-0">Foto bawaan situs sedang digunakan.</p>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if (in_array($selectedPage, ['beranda', 'galeri'], true)): ?>
    <div class="live-gallery-manager">
        <div class="live-media-heading">
            <div>
                <h3>Foto galeri</h3>
                <p class="admin-muted mb-0">Tambah, ubah keterangan atau foto, dan hapus dokumentasi jemaat.</p>
            </div>
            <a class="btn btn-sm btn-outline-secondary" href="<?= esc(site_url('admin/galeri')) ?>">Buka galeri</a>
        </div>
        <form class="live-gallery-upload" method="post" action="<?= esc(site_url('admin/galeri')) ?>" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="return_page" value="<?= esc($selectedPage, 'attr') ?>">
            <?php
            $imageEditorName = 'foto';
            $imageEditorId = 'gallery-new-image';
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
                'imagePreviewKey' => 'gallery_new',
            ]) ?>
            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="gallery-new-caption">Keterangan foto</label>
                    <input class="form-control" id="gallery-new-caption" name="caption" type="text" maxlength="180" required data-live-gallery-caption>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold" for="gallery-new-alt">Teks alternatif</label>
                    <input class="form-control" id="gallery-new-alt" name="alt_text" type="text" maxlength="250" data-live-gallery-alt>
                </div>
            </div>
            <button class="btn btn-primary mt-3" type="submit">Tambah foto galeri</button>
        </form>

        <?php if (empty($galleryPhotos)): ?>
            <p class="live-gallery-empty admin-muted mb-0">Belum ada foto galeri.</p>
        <?php else: ?>
            <div class="live-gallery-list">
                <?php foreach ($galleryPhotos as $photo): ?>
                    <?php
                    $filename = (string) ($photo['filename'] ?? '');
                    $photoPath = 'uploads/gallery/' . $filename;
                    $validPhoto = basename($filename) === $filename
                        && preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)
                        && is_file(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $photoPath));
                    ?>
                    <article class="live-gallery-item">
                        <?php if ($validPhoto): ?>
                            <img src="<?= esc(base_url($photoPath)) ?>" alt="<?= esc($photo['alt_text'] ?? '') ?>" loading="lazy">
                        <?php else: ?>
                            <div class="live-gallery-missing" role="img" aria-label="File foto tidak ditemukan">Foto tidak tersedia</div>
                        <?php endif; ?>
                        <div class="live-gallery-item-copy">
                            <strong><?= esc($photo['caption'] ?? 'Foto jemaat') ?></strong>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                <a class="btn btn-sm btn-outline-primary" href="<?= esc(site_url('admin/galeri/' . $photo['id'] . '/ubah?return_page=' . rawurlencode($selectedPage))) ?>">Ubah foto</a>
                                <form method="post" action="<?= esc(site_url('admin/galeri/' . $photo['id'] . '/hapus')) ?>" data-confirm-delete="Hapus foto ini dari galeri? Tindakan ini tidak dapat dibatalkan.">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="return_page" value="<?= esc($selectedPage, 'attr') ?>">
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>
<?= $this->endSection() ?>
