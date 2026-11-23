<?php
$providedFormErrors = $formErrors ?? null;
$formErrors = is_array($providedFormErrors) ? $providedFormErrors : [];
$contactEmail = $contactEmail ?? 'bukitpengharapan33@gmail.com';
$content = is_array($content ?? null) ? $content : [];
$mapEmbedUrl = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3402.992049363432!2d128.11324017541236!3d-3.7763184347482532!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2d6cdd002b689a13%3A0x45d0084dbaf077f7!2sGereja%20Elpido%20Jemaat%20Bukit%20Pengharapan%20Latuhalat!5e1!3m2!1sid!2sid!4v1794078646768!5m2!1sid!2sid';
$mapDirectionsUrl = 'https://www.google.com/maps/search/?api=1&query=-3.7763184347482532%2C128.11324017541236';
?>
<?= $this->extend('layouts/publik') ?>
<?= $this->section('content') ?>
<section class="detail-header">
    <div class="container">
        <h1 class="detail-title" data-live-preview="page_title"><?= esc($content['page_title'] ?? 'Kontak Jemaat') ?></h1>
        <p class="section-intro" data-live-preview="page_intro"><?= nl2br(esc($content['page_intro'] ?? 'Kirim pesan atau temukan lokasi Gereja Elpido, Latuhalat, Ambon.')) ?></p>
    </div>
</section>

<section class="section-block pt-3" aria-label="Form kontak dan lokasi jemaat">
    <div class="container">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <section class="card detail-card h-100" aria-labelledby="judul-kirim-pesan">
                    <div class="card-body">
                        <h2 class="surface-title" id="judul-kirim-pesan" data-live-preview="form_heading"><?= esc($content['form_heading'] ?? 'Kirim pesan') ?></h2>
                        <p class="small text-body-secondary">
                            <?= ! empty($contactInboxEnabled)
                                ? 'Pesan ini akan masuk ke kotak pesan dashboard dan dapat dibaca oleh para pengelola jemaat.'
                                : 'Pesan ini akan dikirim ke alamat kontak gereja.' ?>
                        </p>

                        <?php if (! empty($contactSuccess)): ?>
                            <div class="alert alert-success" role="status"><?= esc($contactSuccess) ?></div>
                        <?php endif; ?>

                        <?php if (! empty($contactError)): ?>
                            <div class="alert alert-warning" role="alert">
                                <?= esc($contactError) ?>
                                <a href="mailto:<?= esc($contactEmail, 'attr') ?>">Kirim email langsung</a>.
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?= esc(site_url('kontak/kirim')) ?>" novalidate>
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label fw-bold" for="nama">Nama</label>
                                <input class="form-control<?= isset($formErrors['nama']) ? ' is-invalid' : '' ?>" id="nama" name="nama" type="text" autocomplete="name" maxlength="100" value="<?= esc(old('nama') ?? '') ?>" required>
                                <?php if (isset($formErrors['nama'])): ?>
                                    <div class="invalid-feedback d-block"><?= esc($formErrors['nama']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" for="email">Email</label>
                                <input class="form-control<?= isset($formErrors['email']) ? ' is-invalid' : '' ?>" id="email" name="email" type="email" autocomplete="email" maxlength="254" value="<?= esc(old('email') ?? '') ?>" required>
                                <?php if (isset($formErrors['email'])): ?>
                                    <div class="invalid-feedback d-block"><?= esc($formErrors['email']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" for="subjek">Subjek</label>
                                <input class="form-control<?= isset($formErrors['subjek']) ? ' is-invalid' : '' ?>" id="subjek" name="subjek" type="text" maxlength="120" value="<?= esc(old('subjek') ?? '') ?>" required>
                                <?php if (isset($formErrors['subjek'])): ?>
                                    <div class="invalid-feedback d-block"><?= esc($formErrors['subjek']) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold" for="pesan">Pesan</label>
                                <textarea class="form-control<?= isset($formErrors['pesan']) ? ' is-invalid' : '' ?>" id="pesan" name="pesan" rows="5" maxlength="5000" required><?= esc(old('pesan') ?? '') ?></textarea>
                                <?php if (isset($formErrors['pesan'])): ?>
                                    <div class="invalid-feedback d-block"><?= esc($formErrors['pesan']) ?></div>
                                <?php endif; ?>
                            </div>

                            <button class="btn btn-primary fw-bold" type="submit">Kirim pesan</button>
                        </form>
                    </div>
                </section>
            </div>

            <div class="col-lg-6">
                <section class="card detail-card h-100" aria-labelledby="judul-lokasi">
                    <div class="card-body">
                        <h2 class="surface-title" id="judul-lokasi" data-live-preview="location_heading"><?= esc($content['location_heading'] ?? 'Lokasi Jemaat') ?></h2>
                        <p data-live-preview="location_text"><?= nl2br(esc($content['location_text'] ?? 'Gereja Elpido, Latuhalat, Ambon')) ?></p>
                        <div class="contact-map-frame mb-3">
                            <iframe src="<?= esc($mapEmbedUrl, 'attr') ?>" title="Peta lokasi Gereja Elpido, Jemaat GPM Bukit Pengharapan" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                        </div>
                        <a href="<?= esc($mapDirectionsUrl, 'attr') ?>" target="_blank" rel="noopener noreferrer">Buka petunjuk arah di Google Maps</a>
                        <p class="mt-3 mb-0"><span class="fw-bold">Email:</span> <a href="mailto:<?= esc($contactEmail, 'attr') ?>"><?= esc($contactEmail) ?></a></p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
<?= $this->endSection() ?>