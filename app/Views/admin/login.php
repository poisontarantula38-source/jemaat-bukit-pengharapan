<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $setupMode ? 'Buat Akun Admin' : 'Login Admin' ?> | Jemaat GPM Bukit Pengharapan</title>
    <link rel="icon" type="image/x-icon" href="<?= esc(base_url('favicon.ico?v=2')) ?>">
    <link rel="stylesheet" href="<?= esc(base_url('assets/vendor/bootstrap/bootstrap.min.css')) ?>">
    <style {csp-style-nonce}>
        :root {
            color-scheme: light;
            --page-bg: #192b3a;
            --brand-blue-dark: #0b5ed7;
            --text-main: #29232C;
            --text-muted: #5D5361;
        }

        body {
            min-height: 100vh;
            min-height: 100svh;
            display: grid;
            place-items: center;
            background-color: var(--page-bg);
            color: var(--text-main);
            font-family: Inter, "Segoe UI", sans-serif;
            line-height: 1.6;
            padding: clamp(1rem, 4vw, 2.5rem);
        }

        .login-backdrop {
            position: fixed;
            z-index: 0;
            inset: 0;
            overflow: hidden;
            background: #192b3a;
            pointer-events: none;
        }

        .login-backdrop img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }

        .login-backdrop::after {
            position: absolute;
            inset: 0;
            background: linear-gradient(115deg, rgba(13, 30, 47, .76), rgba(13, 30, 47, .42) 58%, rgba(13, 30, 47, .58));
            content: "";
        }

        .login-panel {
            position: relative;
            z-index: 1;
            width: min(100%, 31rem);
            border: 1px solid rgba(255, 255, 255, .72);
            border-radius: 1.2rem;
            background: #fffefa;
            padding: clamp(1.5rem, 5vw, 2.65rem);
            box-shadow: 0 24px 72px rgba(8, 24, 39, .3);
        }

        .login-brand-lockup {
            display: flex;
            align-items: center;
            gap: .9rem;
            margin-bottom: 1.9rem;
        }

        .login-brand-logo {
            width: 3.15rem;
            height: 3.8rem;
            flex: 0 0 auto;
            object-fit: contain;
        }

        .login-brand {
            max-width: 15rem;
            margin: 0;
            color: #084ba8;
            font-size: .95rem;
            line-height: 1.35;
            text-wrap: balance;
        }

        .login-brand-name {
            display: block;
            font-weight: 750;
        }

        .login-brand-caption {
            display: block;
            margin-top: .18rem;
            color: var(--text-muted);
            font-size: .76rem;
            font-weight: 500;
        }

        .login-title {
            margin: 0 0 .5rem;
            font-size: clamp(1.55rem, 4vw, 1.85rem);
            font-weight: 750;
            letter-spacing: -.03em;
            line-height: 1.2;
        }

        .login-copy {
            color: var(--text-muted);
        }

        .login-panel .form-control {
            min-height: 3rem;
            border-color: #c9c4cb;
            border-radius: .65rem;
            padding-inline: .85rem;
        }

        .login-panel .form-control:focus {
            border-color: var(--brand-blue-dark);
            box-shadow: 0 0 0 .2rem rgba(11, 94, 215, .16);
        }

        .login-panel .btn-primary {
            min-height: 3rem;
            border-radius: .65rem;
            --bs-btn-bg: var(--brand-blue-dark);
            --bs-btn-border-color: var(--brand-blue-dark);
            --bs-btn-hover-bg: #094fb8;
            --bs-btn-hover-border-color: #094fb8;
            --bs-btn-active-bg: #084ba8;
            --bs-btn-active-border-color: #084ba8;
        }

        .login-footer {
            border-top: 1px solid rgba(41, 35, 44, .12);
            padding-top: 1rem;
        }

        a {
            text-underline-offset: .2em;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible {
            outline: 3px solid #084ba8;
            outline-offset: 3px;
            box-shadow: none;
        }

        @media (max-width: 575.98px) {
            body {
                background-color: var(--page-bg);
            }

            .login-backdrop img {
                object-position: 56% center;
            }

            .login-brand-lockup {
                margin-bottom: 1.55rem;
            }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
            }
        }
    </style>
</head>
<body>
    <div class="login-backdrop" aria-hidden="true">
        <img src="<?= esc(base_url('assets/images/gereja-bukit-pengharapan-udara.jpg')) ?>" alt="">
    </div>
    <main class="login-panel">
        <div class="login-brand-lockup">
            <img class="login-brand-logo" src="<?= esc(base_url('assets/images/logo-gpm-bukit-pengharapan-optimized.webp')) ?>" alt="Logo GPM Bukit Pengharapan">
            <p class="login-brand">
                <span class="login-brand-name">Jemaat GPM Bukit Pengharapan</span>
                <span class="login-brand-caption">Panel pengelolaan jemaat</span>
            </p>
        </div>
        <h1 class="login-title"><?= $setupMode ? 'Buat akun admin pertama' : 'Masuk ke panel admin' ?></h1>
        <p class="login-copy mb-4"><?= $setupMode
            ? 'Akun ini menjadi akses pertama ke panel admin. Setelah dibuat, pendaftaran akun pertama akan ditutup.'
            : 'Gunakan akun admin untuk mengelola Warta &amp; Berita jemaat.' ?></p>

        <?php if (! $databaseConfigured): ?>
            <div class="alert alert-warning" role="status">
                Database belum disiapkan. Pengelola situs perlu mengatur koneksi database dan membuat akun admin terlebih dahulu.
            </div>
        <?php endif; ?>

        <?php if (! empty($setupSuccess)): ?>
            <div class="alert alert-success" role="status"><?= esc($setupSuccess) ?></div>
        <?php endif; ?>

        <?php if (! empty($setupError)): ?>
            <div class="alert alert-danger" role="alert"><?= esc($setupError) ?></div>
        <?php endif; ?>

        <?php if (! empty($authError)): ?>
            <div class="alert alert-danger" role="alert"><?= esc($authError) ?></div>
        <?php endif; ?>

        <?php if ($setupMode): ?>
            <?php if ($databaseConfigured && ! $adminExists): ?>
                <form method="post" action="<?= esc(site_url('admin/setup')) ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="username">Nama pengguna</label>
                        <input class="form-control" id="username" name="username" type="text" autocomplete="username" minlength="3" maxlength="80" required autofocus value="<?= esc($setupUsername, 'attr') ?>">
                        <div class="form-text">Gunakan 3–80 karakter: huruf, angka, titik, garis bawah, atau tanda hubung.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold" for="password">Kata sandi</label>
                        <input class="form-control" id="password" name="password" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
                        <div class="form-text">Minimal 12 karakter.</div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold" for="password_confirm">Ulangi kata sandi</label>
                        <input class="form-control" id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" minlength="12" maxlength="1024" required>
                    </div>
                    <button class="btn btn-primary w-100 fw-semibold" type="submit">Buat akun admin</button>
                </form>
            <?php endif; ?>
        <?php else: ?>
            <form method="post" action="<?= esc(site_url('admin/login')) ?>">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label class="form-label fw-semibold" for="username">Nama pengguna</label>
                    <input class="form-control" id="username" name="username" type="text" autocomplete="username" maxlength="80" required value="<?= esc($loginUsername, 'attr') ?>"<?= $databaseConfigured ? '' : ' disabled' ?>>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-semibold" for="password">Kata sandi</label>
                    <input class="form-control" id="password" name="password" type="password" autocomplete="current-password" required<?= $databaseConfigured ? '' : ' disabled' ?>>
                </div>
                <button class="btn btn-primary w-100 fw-semibold" type="submit"<?= $databaseConfigured ? '' : ' disabled' ?>>Masuk</button>
            </form>

            <?php if ($databaseConfigured && ! $adminExists): ?>
                <p class="login-copy small text-center mt-4 mb-0">
                    Belum ada akun admin?
                    <a href="<?= esc(site_url('admin/setup')) ?>">Buat akun admin pertama</a>
                </p>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($setupMode): ?>
            <p class="login-copy small login-footer mt-4 mb-0"><a href="<?= esc(site_url('admin/login')) ?>">Kembali ke login admin</a></p>
        <?php else: ?>
            <p class="login-copy small login-footer mt-4 mb-0"><a href="<?= esc(site_url('/')) ?>">Kembali ke situs jemaat</a></p>
        <?php endif; ?>
    </main>
</body>
</html>
