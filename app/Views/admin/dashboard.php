<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$wartaTerbaru = is_array($wartaTerbaru ?? null) ? $wartaTerbaru : [];
$jadwalMendatang = is_array($jadwalMendatang ?? null) ? $jadwalMendatang : [];
$pesanTerbaru = is_array($pesanTerbaru ?? null) ? $pesanTerbaru : [];
$newMessages = (int) ($newMessages ?? 0);
$upcomingSchedules = (int) ($upcomingSchedules ?? 0);
$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
$statusLabels = ['baru' => 'Baru', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];
$dateParts = static function (string $date) use ($monthNames): array {
    $timestamp = strtotime($date);

    return $timestamp === false
        ? ['day' => '—', 'month' => '']
        : ['day' => date('d', $timestamp), 'month' => $monthNames[(int) date('n', $timestamp) - 1]];
};
?>
<section class="dashboard-welcome mb-4" aria-labelledby="dashboard-welcome-title">
    <div class="dashboard-welcome-copy">
        <h1 id="dashboard-welcome-title"><span data-wit-greeting>Selamat datang</span>, <?= esc(session()->get('adminUsername') ?? 'Admin') ?>.</h1>
        <p>Ringkasan terbaru pengelolaan situs Jemaat GPM Bukit Pengharapan.</p>
        <div class="dashboard-live-date">
            <span class="dashboard-live-dot" aria-hidden="true"></span>
            <time data-wit-clock datetime="<?= esc(gmdate('c')) ?>"><?= esc(gmdate('d-m-Y H:i')) ?> WIT</time>
        </div>
    </div>
    <a class="btn btn-primary" href="<?= esc(site_url('admin/warta/tambah')) ?>">Tulis warta baru</a>
</section>

<section class="dashboard-summary" aria-label="Ringkasan dashboard">
    <a class="dashboard-summary-link" href="<?= esc(site_url('admin/pesan?status=baru')) ?>">
        <span class="dashboard-summary-label">Pesan baru</span>
        <span class="dashboard-summary-value"><?= esc((string) $newMessages) ?></span>
    </a>
    <a class="dashboard-summary-link" href="<?= esc(site_url('admin/jadwal')) ?>">
        <span class="dashboard-summary-label">Jadwal mendatang</span>
        <span class="dashboard-summary-value"><?= esc((string) $upcomingSchedules) ?></span>
    </a>
</section>

<div class="dashboard-columns">
    <section class="dashboard-panel" aria-labelledby="judul-jadwal-mendatang">
        <div class="dashboard-panel-heading">
            <h2 id="judul-jadwal-mendatang">Jadwal ibadah mendatang</h2>
            <a href="<?= esc(site_url('admin/jadwal')) ?>">Kelola jadwal</a>
        </div>
        <?php if ($jadwalMendatang === []): ?>
            <div class="dashboard-empty">
                <p class="mb-2 fw-semibold">Belum ada jadwal mendatang.</p>
                <a class="small fw-semibold" href="<?= esc(site_url('admin/jadwal/tambah')) ?>">Tambahkan jadwal ibadah</a>
            </div>
        <?php else: ?>
            <div class="dashboard-feed">
                <?php foreach ($jadwalMendatang as $schedule): ?>
                    <?php $datePart = $dateParts((string) $schedule['service_date']); ?>
                    <div class="dashboard-feed-row">
                        <div class="dashboard-feed-copy">
                            <span class="dashboard-feed-title"><?= esc($schedule['service_type']) ?></span>
                            <span class="dashboard-feed-meta">
                                <?= esc(substr((string) $schedule['service_time'], 0, 5)) ?> WIT
                                · <?= esc($schedule['location']) ?>
                            </span>
                        </div>
                        <span class="dashboard-date-chip" aria-label="<?= esc($datePart['day'] . ' ' . $datePart['month']) ?>">
                            <strong><?= esc($datePart['day']) ?></strong>
                            <?= esc($datePart['month']) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <section class="dashboard-panel" aria-labelledby="judul-pesan-terbaru">
        <div class="dashboard-panel-heading">
            <h2 id="judul-pesan-terbaru">Pesan terbaru</h2>
            <a href="<?= esc(site_url('admin/pesan')) ?>">Buka kotak pesan</a>
        </div>
        <?php if ($pesanTerbaru === []): ?>
            <div class="dashboard-empty">
                <p class="mb-0">Belum ada pesan masuk dari halaman Kontak.</p>
            </div>
        <?php else: ?>
            <div class="dashboard-feed">
                <?php foreach ($pesanTerbaru as $message): ?>
                    <?php
                    $createdAt = strtotime((string) $message['created_at']);
                    $messageStatus = (string) $message['status'];
                    ?>
                    <a class="dashboard-feed-row text-decoration-none" href="<?= esc(site_url('admin/pesan')) ?>">
                        <span class="dashboard-feed-copy">
                            <span class="dashboard-feed-title"><?= esc($message['subject']) ?></span>
                            <span class="dashboard-feed-meta">
                                <?= esc($message['sender_name']) ?>
                                · <?= $createdAt === false ? 'Tanggal tidak tersedia' : esc(date('d-m-Y', $createdAt)) ?>
                            </span>
                        </span>
                        <span class="dashboard-status <?= $messageStatus === 'selesai' ? 'dashboard-status-published' : 'dashboard-status-draft' ?>">
                            <?= esc($statusLabels[$messageStatus] ?? 'Pesan') ?>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>
</div>

<section class="dashboard-section" aria-labelledby="judul-warta-terbaru">
    <div class="dashboard-panel">
        <div class="dashboard-panel-heading">
            <h2 id="judul-warta-terbaru">Warta terbaru</h2>
            <a href="<?= esc(site_url('admin/warta')) ?>">Lihat semua</a>
        </div>
        <?php if ($wartaTerbaru === []): ?>
            <div class="dashboard-empty">
                <p class="mb-2 fw-semibold">Belum ada warta.</p>
                <a class="small fw-semibold" href="<?= esc(site_url('admin/warta/tambah')) ?>">Tulis warta pertama</a>
            </div>
        <?php else: ?>
            <div class="dashboard-article-list">
                <?php foreach ($wartaTerbaru as $article): ?>
                    <?php
                    $isPublished = (int) $article['is_published'] === 1;
                    $dateValue = $isPublished
                        ? ($article['published_at'] ?? null)
                        : ($article['updated_at'] ?? null);
                    $timestamp = $dateValue === null ? false : strtotime((string) $dateValue);
                    ?>
                    <div class="dashboard-article-row">
                        <a class="dashboard-article-title" href="<?= esc(site_url('admin/warta/' . $article['id'] . '/ubah')) ?>">
                            <?= esc($article['title']) ?>
                        </a>
                        <span class="dashboard-article-meta">
                            <?= $timestamp === false ? 'Tanggal belum tersedia' : esc(date('d-m-Y', $timestamp)) ?>
                        </span>
                        <span class="dashboard-status <?= $isPublished ? 'dashboard-status-published' : 'dashboard-status-draft' ?>">
                            <?= $isPublished ? 'Terbit' : 'Draf' ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="dashboard-section" aria-labelledby="judul-kelola-situs">
    <div class="dashboard-panel">
        <div class="dashboard-panel-heading">
            <h2 id="judul-kelola-situs">Akses cepat</h2>
        </div>
        <div class="row row-cols-1 row-cols-md-2 g-2">
            <div class="col">
                <a class="dashboard-tool-link" href="<?= esc(site_url('admin/konten')) ?>">
                    <span>
                        <span class="dashboard-tool-title">Editor halaman</span>
                        <span class="dashboard-tool-description">Ubah tulisan dan susunan halaman situs.</span>
                    </span>
                    <span class="dashboard-tool-action">Buka</span>
                </a>
            </div>
            <div class="col">
                <a class="dashboard-tool-link" href="<?= esc(site_url('admin/galeri')) ?>">
                    <span>
                        <span class="dashboard-tool-title">Foto galeri</span>
                        <span class="dashboard-tool-description">Tambah, ubah, atau hapus foto jemaat.</span>
                    </span>
                    <span class="dashboard-tool-action">Buka</span>
                </a>
            </div>
            <?php if (session()->get('adminRole') === 'superadmin'): ?>
                <div class="col">
                    <a class="dashboard-tool-link" href="<?= esc(site_url('admin/pengelola')) ?>">
                        <span>
                            <span class="dashboard-tool-title">Akun pengelola</span>
                            <span class="dashboard-tool-description">Kelola akses tim pengelola situs.</span>
                        </span>
                        <span class="dashboard-tool-action">Buka</span>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
<script {csp-script-nonce}>
    const witClock = document.querySelector('[data-wit-clock]');
    const witGreeting = document.querySelector('[data-wit-greeting]');
    const updateWitTime = () => {
        const now = new Date();
        const hour = Number(new Intl.DateTimeFormat('en', {
            hour: 'numeric',
            hourCycle: 'h23',
            timeZone: 'Asia/Jayapura',
        }).format(now));
        const greeting = hour < 11 ? 'Selamat pagi' : (hour < 15 ? 'Selamat siang' : (hour < 18 ? 'Selamat sore' : 'Selamat malam'));
        const time = new Intl.DateTimeFormat('id-ID', {
            dateStyle: 'full',
            timeStyle: 'short',
            timeZone: 'Asia/Jayapura',
        }).format(now);

        witGreeting.textContent = greeting;
        witClock.textContent = `${time} WIT`;
        witClock.dateTime = now.toISOString();
    };

    updateWitTime();
    window.setInterval(updateWitTime, 30000);
</script>
<?= $this->endSection() ?>
