<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<div class="d-flex flex-column flex-sm-row align-items-sm-end justify-content-between gap-3 mb-4">
    <div>
        <h1 class="admin-page-title mb-1">Warta &amp; Berita</h1>
        <p class="admin-muted mb-0">Warta terbit ditampilkan di halaman publik jemaat.</p>
    </div>
    <a class="btn btn-primary fw-semibold" href="<?= esc(site_url('admin/warta/tambah')) ?>">Tambah warta</a>
</div>

<?php if (! empty($adminSuccess)): ?>
    <div class="alert alert-success" role="status"><?= esc($adminSuccess) ?></div>
<?php endif; ?>
<?php if (! empty($adminError)): ?>
    <div class="alert alert-danger" role="alert"><?= esc($adminError) ?></div>
<?php endif; ?>

<section class="admin-surface p-3 p-md-4" aria-label="Daftar warta">
    <?php if ($warta === []): ?>
        <div class="py-4 text-center">
            <h2 class="h5 fw-bold">Belum ada warta</h2>
            <p class="admin-muted">Mulai dengan menambahkan warta pertama untuk jemaat.</p>
            <a class="btn btn-primary" href="<?= esc(site_url('admin/warta/tambah')) ?>">Tambah warta</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th scope="col">Judul</th>
                        <th scope="col">Kategori</th>
                        <th scope="col">Status</th>
                        <th scope="col">Tanggal terbit</th>
                        <th scope="col"><span class="visually-hidden">Tindakan</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($warta as $article): ?>
                        <tr>
                            <th scope="row" class="fw-semibold"><?= esc($article['title']) ?></th>
                            <td><?= esc($article['category'] ?? '') ?: '—' ?></td>
                            <td>
                                <?php if ((int) $article['is_published'] === 1): ?>
                                    <span class="badge text-bg-success">Terbit</span>
                                <?php else: ?>
                                    <span class="badge text-bg-secondary">Draf</span>
                                <?php endif; ?>
                            </td>
                            <td><?= ! empty($article['published_at']) ? esc(date('d-m-Y H:i', strtotime($article['published_at']))) : '—' ?></td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-primary" href="<?= esc(site_url('admin/warta/' . $article['id'] . '/ubah')) ?>">Ubah</a>
                                <form class="d-inline" method="post" action="<?= esc(site_url('admin/warta/' . $article['id'] . '/hapus')) ?>" data-confirm-delete>
                                    <?= csrf_field() ?>
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?= $this->endSection() ?>
