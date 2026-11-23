<?php
$pageTitle = $pageTitle ?? 'Admin';
$adminUsername = session()->get('adminUsername') ?? 'Admin';
$isSuperadmin = session()->get('adminRole') === 'superadmin';
$logoAsset = 'assets/images/logo-gpm-bukit-pengharapan-optimized.webp';
$hasLogo = is_file(FCPATH . $logoAsset);
$currentUri = uri_string();
$navigationGroups = [
    'Utama' => [
        ['label' => 'Dashboard', 'url' => 'admin', 'icon' => 'dashboard', 'active' => $currentUri === 'admin'],
    ],
    'Kelola situs' => [
        ['label' => 'Jadwal ibadah', 'url' => 'admin/jadwal', 'icon' => 'schedule', 'active' => str_starts_with($currentUri, 'admin/jadwal')],
        ['label' => 'Kotak pesan', 'url' => 'admin/pesan', 'icon' => 'inbox', 'active' => str_starts_with($currentUri, 'admin/pesan')],
        ['label' => 'Warta & Berita', 'url' => 'admin/warta', 'icon' => 'news', 'active' => str_starts_with($currentUri, 'admin/warta')],
        ['label' => 'Editor halaman', 'url' => 'admin/konten', 'icon' => 'edit', 'active' => str_starts_with($currentUri, 'admin/konten')],
        ['label' => 'Galeri', 'url' => 'admin/galeri', 'icon' => 'gallery', 'active' => str_starts_with($currentUri, 'admin/galeri')],
    ],
    'Bantuan' => [
        ['label' => 'Buku panduan', 'url' => 'admin/panduan', 'icon' => 'guide', 'active' => str_starts_with($currentUri, 'admin/panduan')],
    ],
];
if ($isSuperadmin) {
    $navigationGroups['Pengaturan'] = [
        ['label' => 'Akun pengelola', 'url' => 'admin/pengelola', 'icon' => 'users', 'active' => str_starts_with($currentUri, 'admin/pengelola')],
    ];
}
$navigationIcons = [
    'dashboard' => '<rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="5" rx="1.5"/><rect x="13" y="10" width="8" height="11" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/>',
    'schedule' => '<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18M8 14h3M8 17h7"/>',
    'inbox' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 13h5l2 3h4l2-3h5"/>',
    'news' => '<path d="M5 4h13a2 2 0 0 1 2 2v14H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z"/><path d="M7 8h9M7 12h9M7 16h5"/>',
    'edit' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>',
    'gallery' => '<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/>',
    'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M20 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
    'guide' => '<path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v17H6.5A2.5 2.5 0 0 1 4 17.5z"/><path d="M4 17.5A2.5 2.5 0 0 1 6.5 15H20M8 7h8M8 10h8"/>',
    'external' => '<path d="M14 3h7v7M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
    'account' => '<circle cx="12" cy="8" r="4"/><path d="M5 21v-2a7 7 0 0 1 14 0v2"/>',
    'logout' => '<path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-6"/>',
];
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?> | Admin Jemaat Bukit Pengharapan</title>
    <link rel="icon" type="image/x-icon" href="<?= esc(base_url('favicon.ico?v=2')) ?>">
    <link rel="stylesheet" href="<?= esc(base_url('assets/vendor/bootstrap/bootstrap.min.css')) ?>">
    <style {csp-style-nonce}>
        :root {
            color-scheme: light;
            --page-bg: #FFFEFA;
            --surface-soft: #F5F1F6;
            --surface-blue: #E2EEF8;
            --brand-blue: #0d6efd;
            --brand-blue-dark: #0b5ed7;
            --brand-gold: #D8BD72;
            --text-main: #29232C;
            --text-muted: #5D5361;
            --line-soft: rgba(41, 35, 44, .12);
            --sidebar-width: 16.25rem;
            --sidebar-collapsed-width: 5rem;
            --topbar-height: 4.5rem;
            --sidebar-ease: cubic-bezier(.16, 1, .3, 1);
        }

        body {
            min-height: 100vh;
            background: #f5f7fb;
            color: var(--text-main);
            font-family: Inter, "Segoe UI", sans-serif;
            line-height: 1.6;
        }

        a {
            text-underline-offset: .2em;
        }

        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        select:focus-visible,
        textarea:focus-visible {
            outline: 3px solid var(--brand-blue-dark);
            outline-offset: 3px;
            box-shadow: none;
        }

        .admin-topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            min-height: var(--topbar-height);
            padding-inline: clamp(1rem, 2.5vw, 2rem);
        }

        .admin-topbar {
            position: sticky;
            z-index: 1030;
            top: 0;
            border-bottom: 1px solid var(--line-soft);
            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(12px);
        }

        .admin-topbar-start,
        .admin-topbar-actions {
            display: flex;
            align-items: center;
            gap: .85rem;
        }

        .admin-current-page {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
        }

        .admin-toggle {
            display: inline-grid;
            width: 2.65rem;
            height: 2.65rem;
            place-items: center;
            border: 1px solid var(--line-soft);
            border-radius: .7rem;
            background: #fff;
            color: var(--text-main);
            transition: background-color 160ms ease, border-color 160ms ease, color 160ms ease;
        }

        .admin-toggle:hover {
            border-color: rgba(11, 94, 215, .35);
            background: var(--surface-blue);
            color: var(--brand-blue-dark);
        }

        .admin-icon {
            width: 1.2rem;
            height: 1.2rem;
            flex: 0 0 auto;
            fill: none;
            stroke: currentColor;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-width: 1.8;
        }

        .admin-sidebar {
            position: fixed;
            z-index: 1040;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            width: var(--sidebar-width);
            flex-direction: column;
            overflow-x: hidden;
            overflow-y: auto;
            border-right: 1px solid var(--line-soft);
            background: #fff;
            padding: 1.2rem .85rem;
            transition: width 320ms var(--sidebar-ease), transform 320ms var(--sidebar-ease), box-shadow 220ms ease;
        }

        .admin-sidebar-brand {
            position: relative;
            display: flex;
            min-height: 3.5rem;
            align-items: center;
            gap: .7rem;
            margin: 0 .45rem 1.35rem;
            border: 0;
            background: transparent;
            color: var(--text-main);
            cursor: pointer;
            font: inherit;
            padding: 0;
            text-align: left;
            text-decoration: none;
            transition: gap 320ms var(--sidebar-ease), padding 320ms var(--sidebar-ease);
        }

        .admin-logo {
            width: 2.6rem;
            height: 2.6rem;
            flex: 0 0 auto;
            object-fit: contain;
            transition: opacity 180ms ease, transform 320ms var(--sidebar-ease);
        }

        .admin-brand-action-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            display: grid;
            width: 2.6rem;
            height: 2.6rem;
            place-items: center;
            border-radius: .75rem;
            background: #eaf1fc;
            color: #084ba8;
            opacity: 0;
            pointer-events: none;
            transform: translate(-50%, -50%) scale(.78);
            transition: opacity 160ms ease, transform 320ms var(--sidebar-ease), background-color 160ms ease;
        }

        .admin-brand-action-icon .admin-icon {
            width: 1.35rem;
            height: 1.35rem;
        }

        body.admin-sidebar-collapsed .admin-sidebar-brand:is(:hover, :focus-visible) .admin-logo {
            opacity: 0;
            transform: scale(.76) rotate(-8deg);
        }

        body.admin-sidebar-collapsed .admin-sidebar-brand:is(:hover, :focus-visible) .admin-brand-action-icon {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        body.admin-sidebar-collapsed .admin-sidebar-brand:is(:hover, :focus-visible) .admin-brand-action-icon {
            background: #dceaff;
        }

        .admin-brand-copy {
            display: grid;
            min-width: 0;
            max-width: 13rem;
            overflow: hidden;
            line-height: 1.3;
            transition: max-width 300ms var(--sidebar-ease), opacity 130ms ease, transform 260ms var(--sidebar-ease), visibility 0s linear;
        }

        .admin-brand-name {
            font-size: .8rem;
            font-weight: 750;
        }

        .admin-brand-tagline {
            color: var(--text-muted);
            font-size: .72rem;
        }

        .admin-nav-group + .admin-nav-group {
            margin-top: 1.1rem;
        }

        .admin-nav-heading {
            margin: 0 .7rem .45rem;
            max-height: 2rem;
            overflow: hidden;
            color: #77717a;
            font-size: .68rem;
            font-weight: 750;
            letter-spacing: .08em;
            text-transform: uppercase;
            transition: max-height 260ms var(--sidebar-ease), margin 260ms var(--sidebar-ease), opacity 130ms ease, transform 260ms var(--sidebar-ease), visibility 0s linear;
        }

        .admin-nav-list {
            display: grid;
            gap: .2rem;
        }

        .admin-nav-link {
            display: flex;
            min-height: 2.75rem;
            align-items: center;
            gap: .8rem;
            border-radius: .7rem;
            color: #554f58;
            padding: .6rem .7rem;
            font-size: .88rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 180ms ease, color 180ms ease, transform 260ms var(--sidebar-ease), gap 320ms var(--sidebar-ease);
        }

        .admin-nav-link:hover {
            background: #f3f6fb;
            color: var(--brand-blue-dark);
            transform: translateX(2px);
        }

        .admin-nav-link[aria-current="page"] {
            background: #eaf1fc;
            color: #084ba8;
            font-weight: 700;
        }

        .admin-nav-link .admin-icon {
            transition: transform 280ms var(--sidebar-ease);
        }

        body.admin-sidebar-collapsed .admin-nav-link:hover .admin-icon,
        body.admin-sidebar-collapsed .admin-nav-link:focus-visible .admin-icon {
            transform: translateX(.35rem) scale(1.08);
        }

        .admin-nav-label {
            max-width: 12rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            transition: max-width 300ms var(--sidebar-ease), opacity 130ms ease, transform 260ms var(--sidebar-ease), visibility 0s linear;
        }

        .admin-sidebar-bottom {
            display: grid;
            gap: .2rem;
            margin-top: auto;
            padding-top: 1rem;
        }

        .admin-sidebar-profile {
            display: flex;
            align-items: center;
            gap: .7rem;
            margin: 0 .25rem .55rem;
            border-top: 1px solid var(--line-soft);
            padding: 1rem .35rem .5rem;
            transition: gap 260ms var(--sidebar-ease);
        }

        .admin-avatar {
            display: grid;
            width: 2.35rem;
            height: 2.35rem;
            flex: 0 0 auto;
            place-items: center;
            border-radius: 50%;
            background: #eaf1fc;
            color: #084ba8;
            font-size: .85rem;
            font-weight: 750;
            text-transform: uppercase;
        }

        .admin-profile-copy {
            display: grid;
            min-width: 0;
            max-width: 11rem;
            overflow: hidden;
            transition: max-width 300ms var(--sidebar-ease), opacity 130ms ease, transform 260ms var(--sidebar-ease), visibility 0s linear;
        }

        .admin-profile-name {
            overflow: hidden;
            font-size: .83rem;
            font-weight: 700;
            text-overflow: ellipsis;
        }

        .admin-profile-role {
            color: var(--text-muted);
            font-size: .72rem;
        }

        .admin-sidebar-logout {
            width: 100%;
            border: 0;
            background: transparent;
            text-align: left;
        }

        .admin-sidebar-logout-label {
            max-width: 8rem;
            overflow: hidden;
            white-space: nowrap;
            transition: max-width 300ms var(--sidebar-ease), opacity 130ms ease, transform 260ms var(--sidebar-ease), visibility 0s linear;
        }

        .admin-account-form {
            display: block;
            margin: 0;
        }

        .admin-sidebar-public-link {
            display: none;
        }

        .admin-user {
            max-width: 12rem;
            overflow: hidden;
            color: var(--text-muted);
            font-size: .88rem;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .admin-workspace {
            min-height: 100vh;
            margin-left: var(--sidebar-width);
            transition: margin-left 320ms var(--sidebar-ease);
        }

        .admin-main {
            width: min(100%, 1600px);
            margin-inline: auto;
            padding: 2rem clamp(1.25rem, 3vw, 2.75rem) 4rem;
        }

        .admin-page-title {
            font-size: 1.75rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .admin-surface {
            border: 1px solid var(--line-soft);
            border-radius: 1rem;
            background: #fff;
        }

        .admin-muted {
            color: var(--text-muted);
        }

        .admin-stat {
            font-size: 1.75rem;
            font-weight: 700;
        }

        .btn-primary {
            --bs-btn-bg: var(--brand-blue-dark);
            --bs-btn-border-color: var(--brand-blue-dark);
            --bs-btn-hover-bg: #094fb8;
            --bs-btn-hover-border-color: #094fb8;
        }

        .dashboard-welcome {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
            border: 1px solid #dfe7f3;
            border-radius: 1rem;
            background: linear-gradient(110deg, #fff 0%, #f6f9ff 100%);
            padding: clamp(1.25rem, 2.5vw, 2rem);
        }

        .dashboard-welcome-copy {
            max-width: 38rem;
        }

        .dashboard-welcome h1 {
            margin-bottom: .35rem;
            font-size: 1.7rem;
            font-weight: 700;
            letter-spacing: -.025em;
        }

        .dashboard-welcome p {
            margin: 0;
            color: var(--text-muted);
        }

        .dashboard-welcome .btn {
            flex: 0 0 auto;
            font-weight: 700;
        }

        .dashboard-live-date {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            margin-top: .75rem;
            color: #55505a;
            font-size: .84rem;
            font-weight: 600;
            font-variant-numeric: tabular-nums;
        }

        .dashboard-live-dot {
            width: .45rem;
            height: .45rem;
            border-radius: 50%;
            background: #288352;
        }

        .dashboard-section-title {
            font-size: 1.12rem;
            font-weight: 700;
            letter-spacing: -.015em;
        }

        .dashboard-tool-link {
            display: flex;
            min-height: 4.5rem;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border: 1px solid var(--line-soft);
            border-radius: .8rem;
            background: #fff;
            color: var(--text-main);
            padding: .85rem 1rem;
            text-decoration: none;
            transition: background-color 160ms ease-out, border-color 160ms ease-out, transform 160ms ease-out, box-shadow 160ms ease-out;
        }

        .dashboard-tool-link:hover,
        .dashboard-tool-link:focus-visible {
            border-color: rgba(11, 94, 215, .35);
            background: var(--surface-blue);
            color: var(--text-main);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(41, 35, 44, .06);
        }

        .dashboard-tool-title,
        .dashboard-tool-description {
            display: block;
        }

        .dashboard-tool-title {
            font-weight: 700;
        }

        .dashboard-tool-description {
            margin-top: .2rem;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .dashboard-tool-action {
            flex: 0 0 auto;
            color: var(--brand-blue-dark);
            font-size: .9rem;
            font-weight: 700;
        }

        .dashboard-article-list {
            border-top: 1px solid var(--line-soft);
        }

        .dashboard-article-row {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto auto;
            align-items: center;
            gap: 1rem;
            border-bottom: 1px solid var(--line-soft);
            padding: .95rem 0;
        }

        .dashboard-article-title {
            display: block;
            overflow: hidden;
            color: var(--text-main);
            font-weight: 650;
            text-overflow: ellipsis;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-article-title:hover {
            color: var(--brand-blue-dark);
            text-decoration: underline;
        }

        .dashboard-article-meta {
            color: var(--text-muted);
            font-size: .82rem;
            white-space: nowrap;
        }

        .dashboard-status {
            border-radius: 9999px;
            padding: .28rem .6rem;
            font-size: .75rem;
            font-weight: 700;
        }

        .dashboard-status-published {
            background: #e5f3ea;
            color: #245c38;
        }

        .dashboard-status-draft {
            background: #f0edf1;
            color: #554c59;
        }

        .dashboard-empty {
            border-radius: .75rem;
            background: var(--surface-soft);
            padding: 1.25rem;
        }

        .admin-guide-layout {
            display: grid;
            grid-template-columns: minmax(12rem, 15rem) minmax(0, 1fr);
            align-items: start;
            gap: clamp(1.5rem, 4vw, 3.5rem);
        }

        .admin-guide-index {
            position: sticky;
            top: 1.5rem;
            display: grid;
            gap: .2rem;
            border-left: 1px solid var(--line-soft);
            padding-left: 1rem;
        }

        .admin-guide-index a {
            border-radius: .35rem;
            color: var(--text-muted);
            padding: .38rem .55rem;
            font-size: .86rem;
            font-weight: 600;
            text-decoration: none;
            transition: background-color 150ms ease, color 150ms ease;
        }

        .admin-guide-index a:hover,
        .admin-guide-index a:focus-visible {
            background: #eaf1fc;
            color: #084ba8;
        }

        .admin-guide-content {
            min-width: 0;
            max-width: 52rem;
        }

        .admin-guide-content section {
            scroll-margin-top: 1.5rem;
            padding-block: 1.4rem;
        }

        .admin-guide-content section + section {
            border-top: 1px solid var(--line-soft);
        }

        .admin-guide-content h2 {
            margin-bottom: .65rem;
            font-size: 1.2rem;
            font-weight: 750;
        }

        .admin-guide-content h3 {
            margin: 1rem 0 .4rem;
            font-size: .98rem;
            font-weight: 700;
        }

        .admin-guide-content p,
        .admin-guide-content li {
            color: #4f4853;
            font-size: .94rem;
        }

        .admin-guide-content li + li {
            margin-top: .35rem;
        }

        .admin-guide-callout {
            border-radius: .7rem;
            background: #eaf1fc;
            color: #243f66;
            padding: .85rem 1rem;
        }

        .dashboard-summary {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .75rem 1.5rem;
            border-block: 1px solid var(--line-soft);
            margin-bottom: 1.7rem;
            padding-block: .8rem;
        }

        .dashboard-summary-link {
            display: inline-flex;
            align-items: center;
            gap: .55rem;
            color: var(--text-main);
            text-decoration: none;
            transition: color 150ms ease;
        }

        .dashboard-summary-link:hover {
            color: var(--brand-blue-dark);
            text-decoration: underline;
        }

        .dashboard-summary-label {
            font-weight: 600;
            font-size: .82rem;
        }

        .dashboard-summary-value {
            display: inline-grid;
            min-width: 1.5rem;
            height: 1.5rem;
            place-items: center;
            border-radius: 9999px;
            background: #eaf1fc;
            color: #084ba8;
            padding-inline: .35rem;
            font-size: .75rem;
            font-weight: 750;
            font-variant-numeric: tabular-nums;
        }

        .dashboard-columns {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) minmax(18rem, .75fr);
            align-items: start;
            gap: 1.5rem;
        }

        .dashboard-panel {
            min-width: 0;
            border: 1px solid var(--line-soft);
            border-radius: .9rem;
            background: #fff;
            padding: 1.15rem;
        }

        .dashboard-panel-heading {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: .75rem;
            margin-bottom: .8rem;
        }

        .dashboard-panel-heading h2 {
            margin: 0;
            font-size: 1.02rem;
            font-weight: 750;
        }

        .dashboard-panel-heading a {
            flex: 0 0 auto;
            font-size: .8rem;
            font-weight: 650;
        }

        .dashboard-feed {
            display: grid;
        }

        .dashboard-feed-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: .9rem;
            border-top: 1px solid var(--line-soft);
            padding: .8rem 0;
        }

        .dashboard-feed-copy {
            min-width: 0;
        }

        .dashboard-feed-title {
            display: block;
            overflow: hidden;
            color: var(--text-main);
            font-size: .88rem;
            font-weight: 700;
            text-overflow: ellipsis;
            text-decoration: none;
            white-space: nowrap;
        }

        .dashboard-feed-title:hover {
            color: var(--brand-blue-dark);
        }

        .dashboard-feed-meta {
            display: block;
            margin-top: .15rem;
            color: var(--text-muted);
            font-size: .76rem;
        }

        .dashboard-date-chip {
            display: grid;
            min-width: 3.2rem;
            flex: 0 0 auto;
            justify-items: center;
            border-radius: .65rem;
            background: #f0f4fb;
            color: #34445d;
            padding: .35rem;
            font-size: .72rem;
            line-height: 1.25;
        }

        .dashboard-date-chip strong {
            font-size: .95rem;
        }

        .dashboard-section {
            margin-top: 1.65rem;
        }

        .content-page-nav {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            align-items: start;
            gap: .75rem;
        }

        .content-page-group {
            min-width: 0;
            padding: .8rem;
            border: 1px solid var(--line-soft);
            border-radius: .75rem;
            background: #fff;
        }

        .content-page-group-label {
            margin: 0 0 .6rem;
            color: var(--text-muted);
            font-size: .82rem;
            font-weight: 700;
        }

        .content-page-group-links {
            display: flex;
            flex-wrap: wrap;
            gap: .4rem;
        }

        .content-page-group-links .btn {
            text-align: left;
            white-space: normal;
        }

        .live-editor-guidance {
            margin: 0 0 1.25rem;
            padding: .75rem .9rem;
            border-radius: .5rem;
            background: var(--surface-blue);
            color: var(--text-main);
            font-size: .9rem;
            line-height: 1.5;
        }

        .live-editor-layout {
            display: grid;
            grid-template-columns: minmax(0, 1.05fr) minmax(20rem, .95fr);
            align-items: start;
            gap: 1.25rem;
        }

        .live-preview-panel,
        .live-editor-form {
            min-width: 0;
            border: 1px solid var(--line-soft);
            border-radius: 1rem;
            background: #fff;
        }

        .live-preview-panel {
            position: sticky;
            top: 1rem;
            padding: 1rem;
        }

        .live-preview-heading,
        .live-editor-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
        }

        .live-preview-heading {
            margin-bottom: .85rem;
        }

        .live-preview-heading h2,
        .live-editor-toolbar h2 {
            margin: 0;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .live-preview-state,
        .live-editor-save-state {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-top: .2rem;
            color: var(--text-muted);
            font-size: .78rem;
        }

        .live-preview-state-dot {
            width: .5rem;
            height: .5rem;
            flex: 0 0 auto;
            border-radius: 50%;
            background: #399464;
        }

        .live-preview-state[data-state="loading"] .live-preview-state-dot {
            background: #c28a24;
        }

        .live-preview-state[data-state="error"] .live-preview-state-dot {
            background: #b42318;
        }

        .preview-device-switch {
            display: inline-flex;
            flex: 0 0 auto;
            gap: .15rem;
            border: 1px solid var(--line-soft);
            border-radius: .6rem;
            background: var(--page-bg);
            padding: .2rem;
        }

        .preview-device-switch button {
            border: 0;
            border-radius: .4rem;
            background: transparent;
            color: var(--text-muted);
            padding: .3rem .5rem;
            font-size: .75rem;
            font-weight: 600;
        }

        .preview-device-switch button[aria-pressed="true"] {
            background: var(--surface-blue);
            color: var(--brand-blue-dark);
        }

        .live-preview-frame-wrap {
            position: relative;
            overflow: hidden;
            min-height: 24rem;
            border: 1px solid var(--line-soft);
            border-radius: .65rem;
            background: var(--page-bg);
        }

        .live-preview-frame {
            display: block;
            width: 100%;
            height: clamp(24rem, 64vh, 48rem);
            border: 0;
            background: var(--page-bg);
        }

        .live-preview-panel[data-preview-size="mobile"] .live-preview-frame {
            width: 390px;
            max-width: 100%;
            margin-inline: auto;
        }

        .live-preview-loading {
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            background: var(--page-bg);
            color: var(--text-muted);
            font-size: .9rem;
        }

        .live-preview-loading[hidden] {
            display: none;
        }

        .live-preview-note {
            margin-top: .7rem;
            color: var(--text-muted);
            font-size: .78rem;
            line-height: 1.45;
        }

        .live-editor-form {
            overflow: clip;
        }

        .live-editor-toolbar {
            position: sticky;
            z-index: 2;
            top: .5rem;
            border-bottom: 1px solid var(--line-soft);
            background: #fff;
            padding: 1rem 1.15rem;
        }

        .live-editor-toolbar .btn {
            flex: 0 0 auto;
        }

        .live-editor-fields {
            padding: 1.15rem;
        }

        .content-edit-section + .content-edit-section {
            margin-top: 1.5rem;
            border-top: 1px solid var(--line-soft);
            padding-top: 1.35rem;
        }

        .live-editor-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .6rem;
            border-top: 1px solid var(--line-soft);
            background: #fff;
            padding: 1rem 1.15rem;
        }

        .live-editor-form [data-save-state][data-state="dirty"] {
            color: #8a5b08;
        }

        .live-editor-form [data-save-state][data-state="saved"] {
            color: #24633d;
        }

        .live-media-manager {
            margin-top: 1.25rem;
            padding: clamp(1rem, 2.5vw, 1.5rem);
            border: 1px solid var(--line-soft);
            border-radius: 1rem;
            background: #fff;
        }

        .live-media-heading {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.25rem;
        }

        .live-media-heading h2,
        .live-media-heading h3,
        .live-home-image h3 {
            margin: 0 0 .35rem;
            color: var(--text-strong);
            font-size: 1.05rem;
            font-weight: 700;
        }

        .live-home-images {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1.5rem;
        }

        .live-home-image {
            min-width: 0;
        }

        .live-home-image-thumb {
            display: block;
            width: 100%;
            max-height: 15rem;
            margin: 1rem 0;
            border-radius: .65rem;
            object-fit: cover;
        }

        .live-gallery-manager {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--line-soft);
        }

        .live-gallery-upload {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--line-soft);
        }

        .live-gallery-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem 1.5rem;
            margin-top: 1.25rem;
        }

        .live-gallery-item {
            display: grid;
            grid-template-columns: 6rem minmax(0, 1fr);
            align-items: center;
            gap: .85rem;
            min-width: 0;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--line-soft);
        }

        .live-gallery-item > img,
        .live-gallery-missing {
            width: 6rem;
            height: 5rem;
            border-radius: .5rem;
            object-fit: cover;
        }

        .live-gallery-missing {
            display: grid;
            place-items: center;
            padding: .5rem;
            background: var(--page-bg);
            color: var(--text-muted);
            font-size: .75rem;
            text-align: center;
        }

        .live-gallery-item-copy {
            min-width: 0;
            overflow-wrap: anywhere;
        }

        .live-gallery-item-copy > strong {
            display: block;
        }

        [data-live-gallery][hidden],
        [data-live-gallery-archive][hidden] {
            display: none !important;
        }

        .content-edit-heading {
            margin-bottom: 1rem;
            font-size: 1.05rem;
            font-weight: 700;
        }

        .content-edit-heading-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .content-edit-heading-row .content-edit-heading {
            margin-bottom: 0;
        }

        .content-section-move {
            display: flex;
            flex: 0 0 auto;
            gap: .4rem;
        }

        .admin-image-editor {
            max-width: 48rem;
        }

        .admin-image-editor-workspace:not([hidden]) {
            margin-top: .75rem;
        }

        .admin-image-canvas {
            display: block;
            width: 100%;
            height: auto;
            max-height: 28rem;
            margin-inline: auto;
            border: 1px solid var(--line-soft);
            border-radius: 1rem;
            background: #fff;
            cursor: grab;
            touch-action: none;
        }

        .admin-image-canvas:active {
            cursor: grabbing;
        }

        .admin-image-editor-controls {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: .5rem;
            margin-top: .75rem;
        }

        .admin-image-zoom {
            display: flex;
            flex: 1 1 12rem;
            align-items: center;
            gap: .75rem;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .admin-image-zoom input {
            flex: 1;
            min-width: 5rem;
        }

        .admin-gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(min(100%, 16rem), 1fr));
            gap: 1rem;
        }

        .admin-gallery-card {
            overflow: hidden;
        }

        .admin-gallery-thumb {
            display: block;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: var(--surface-blue);
            object-fit: cover;
        }

        .admin-gallery-missing {
            display: grid;
            place-items: center;
            color: var(--text-muted);
            font-size: .9rem;
        }

        .table > :not(caption) > * > * {
            padding: .8rem .75rem;
        }

        body.admin-sidebar-collapsed .admin-sidebar {
            width: var(--sidebar-collapsed-width);
        }

        body.admin-sidebar-collapsed .admin-workspace {
            margin-left: var(--sidebar-collapsed-width);
        }

        body.admin-sidebar-collapsed .admin-brand-copy,
        body.admin-sidebar-collapsed .admin-nav-label,
        body.admin-sidebar-collapsed .admin-profile-copy,
        body.admin-sidebar-collapsed .admin-sidebar-logout-label {
            max-width: 0;
            opacity: 0;
            transform: translateX(-.4rem);
            visibility: hidden;
            transition-delay: 0s, 0s, 0s, 160ms;
        }

        body.admin-sidebar-collapsed .admin-nav-heading {
            max-height: 0;
            margin-block: 0;
            opacity: 0;
            transform: translateX(-.4rem);
            visibility: hidden;
            transition-delay: 0s, 0s, 0s, 0s, 160ms;
        }

        body.admin-sidebar-collapsed .admin-sidebar-brand,
        body.admin-sidebar-collapsed .admin-nav-link,
        body.admin-sidebar-collapsed .admin-sidebar-bottom > a,
        body.admin-sidebar-collapsed .admin-sidebar-logout {
            justify-content: center;
        }

        body.admin-sidebar-collapsed .admin-sidebar-brand {
            gap: 0;
            margin-inline: 0;
        }

        body.admin-sidebar-collapsed .admin-nav-link {
            gap: 0;
            padding-inline: .5rem;
        }

        body.admin-sidebar-collapsed .admin-sidebar-profile {
            justify-content: center;
            gap: 0;
            margin-inline: 0;
            padding-inline: 0;
        }

        .admin-sidebar-backdrop {
            display: none;
        }

        @media (min-width: 992px) {
            .admin-topbar {
                display: none;
            }

            .admin-sidebar-public-link {
                display: flex;
            }
        }

        @view-transition {
            navigation: auto;
        }

        ::view-transition-old(root) {
            animation: admin-page-leave 130ms ease-in both;
        }

        ::view-transition-new(root) {
            animation: admin-page-enter 260ms var(--sidebar-ease) both;
        }

        @keyframes admin-page-leave {
            to {
                opacity: 0;
                transform: translateX(-.25rem);
            }
        }

        @keyframes admin-page-enter {
            from {
                opacity: 0;
                transform: translateX(.4rem);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 991.98px) {
            .live-home-images,
            .live-gallery-list,
            .content-page-nav {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .admin-sidebar {
                top: var(--topbar-height);
                width: min(var(--sidebar-width), calc(100vw - 3.5rem));
                box-shadow: 12px 0 32px rgba(20, 32, 54, .12);
                transform: translateX(-105%);
                transition: width 320ms var(--sidebar-ease), transform 340ms var(--sidebar-ease), box-shadow 220ms ease;
            }

            .admin-sidebar-public-link {
                display: none;
            }

            body.admin-sidebar-open {
                overflow: hidden;
            }

            body.admin-sidebar-open .admin-sidebar {
                transform: translateX(0);
            }

            body.admin-sidebar-open .admin-sidebar-backdrop {
                opacity: 1;
                pointer-events: auto;
                visibility: visible;
                transition-delay: 0s;
            }

            .admin-sidebar-backdrop {
                position: fixed;
                z-index: 1035;
                inset: var(--topbar-height) 0 0;
                display: block;
                border: 0;
                background: rgba(26, 34, 48, .34);
                opacity: 0;
                pointer-events: none;
                visibility: hidden;
                transition: opacity 220ms ease, visibility 0s linear 220ms;
            }

            .admin-workspace,
            body.admin-sidebar-collapsed .admin-workspace {
                margin-left: 0;
            }

            body.admin-sidebar-collapsed .admin-sidebar {
                width: min(var(--sidebar-width), calc(100vw - 3.5rem));
            }

            body.admin-sidebar-collapsed .admin-sidebar-brand {
                margin-inline: .45rem;
            }

            body.admin-sidebar-collapsed .admin-nav-link {
                justify-content: flex-start;
                padding-inline: .7rem;
            }

            body.admin-sidebar-collapsed .admin-sidebar-profile {
                justify-content: flex-start;
                margin-inline: .25rem;
                padding-inline: .35rem;
            }

            body.admin-sidebar-collapsed .admin-sidebar-bottom > a,
            body.admin-sidebar-collapsed .admin-sidebar-logout {
                justify-content: flex-start;
            }

            .live-editor-layout {
                grid-template-columns: minmax(0, 1fr);
            }

            .live-preview-panel {
                position: static;
            }

            .live-preview-frame {
                height: min(68vh, 42rem);
                min-height: 24rem;
            }
        }

        @media (max-width: 575.98px) {
            .admin-main {
                padding: 1.25rem 1rem 3rem;
            }

            .admin-topbar-inner {
                padding-inline: 1rem;
            }

            .admin-topbar-start,
            .admin-topbar-actions {
                gap: .55rem;
            }

            .admin-user {
                display: none;
            }

            .content-page-nav {
                grid-template-columns: minmax(0, 1fr);
                gap: .5rem;
            }

            .content-page-group {
                padding: .65rem;
            }

            .content-page-group-links .btn {
                flex: 1 1 100%;
            }

            .live-preview-panel {
                padding: .75rem;
            }

            .live-preview-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .live-preview-frame {
                height: 65vh;
                min-height: 22rem;
            }

            .live-editor-toolbar {
                top: 0;
                padding: .85rem;
            }

            .live-editor-fields {
                padding: .9rem;
            }

            .dashboard-welcome {
                align-items: flex-start;
                flex-direction: column;
                gap: 1.25rem;
            }

            .dashboard-welcome h1 {
                font-size: 1.45rem;
            }

            .dashboard-columns {
                grid-template-columns: minmax(0, 1fr);
            }

            .admin-guide-layout {
                grid-template-columns: minmax(0, 1fr);
                gap: .75rem;
            }

            .admin-guide-index {
                position: static;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                border-left: 0;
                border-bottom: 1px solid var(--line-soft);
                padding: 0 0 .75rem;
            }

            .dashboard-article-row {
                grid-template-columns: minmax(0, 1fr) auto;
                gap: .5rem .75rem;
            }

            .dashboard-article-meta {
                grid-column: 1;
                grid-row: 2;
            }

            .dashboard-status {
                grid-column: 2;
                grid-row: 1 / span 2;
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

            ::view-transition-old(root),
            ::view-transition-new(root) {
                animation-duration: .01ms !important;
            }
        }
    </style>
</head>
<body>
    <header class="admin-topbar">
        <div class="admin-topbar-inner">
            <div class="admin-topbar-start">
                <button class="admin-toggle" type="button" data-sidebar-toggle aria-controls="admin-sidebar" aria-expanded="true" aria-label="Tutup menu samping" title="Tutup atau buka menu samping">
                    <svg class="admin-icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
            <div class="admin-topbar-actions">
                <span class="admin-user"><?= esc($adminUsername) ?></span>
                <a class="btn btn-sm btn-outline-secondary" href="<?= esc(site_url('/')) ?>">Lihat situs</a>
            </div>
        </div>
    </header>
    <aside class="admin-sidebar" id="admin-sidebar" aria-label="Menu admin">
        <button class="admin-sidebar-brand" type="button" data-sidebar-toggle aria-controls="admin-sidebar" aria-expanded="true" aria-label="Tutup menu samping" title="Tutup atau buka menu samping">
            <?php if ($hasLogo): ?>
                <img class="admin-logo" src="<?= esc(base_url($logoAsset)) ?>" alt="">
            <?php endif; ?>
            <span class="admin-brand-action-icon" aria-hidden="true">
                <svg class="admin-icon" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 4v16M13 8h4M13 12h4M13 16h4"/></svg>
            </span>
            <span class="admin-brand-copy">
                <span class="admin-brand-name">Jemaat GPM<br>Bukit Pengharapan</span>
                <span class="admin-brand-tagline">Panel pengelolaan</span>
            </span>
        </button>
        <nav aria-label="Navigasi utama admin">
            <?php foreach ($navigationGroups as $groupLabel => $items): ?>
                <section class="admin-nav-group" aria-label="<?= esc($groupLabel) ?>">
                    <h2 class="admin-nav-heading"><?= esc($groupLabel) ?></h2>
                    <div class="admin-nav-list">
                        <?php foreach ($items as $item): ?>
                            <a class="admin-nav-link" href="<?= esc(site_url($item['url'])) ?>" aria-label="<?= esc($item['label'], 'attr') ?>"<?= $item['active'] ? ' aria-current="page"' : '' ?> title="<?= esc($item['label'], 'attr') ?>">
                                <svg class="admin-icon" viewBox="0 0 24 24" aria-hidden="true"><?= $navigationIcons[$item['icon']] ?></svg>
                                <span class="admin-nav-label"><?= esc($item['label']) ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        </nav>
        <div class="admin-sidebar-bottom">
            <a class="admin-nav-link admin-sidebar-public-link" href="<?= esc(site_url('/')) ?>" aria-label="Lihat situs" title="Lihat situs">
                <svg class="admin-icon" viewBox="0 0 24 24" aria-hidden="true"><?= $navigationIcons['external'] ?></svg>
                <span class="admin-nav-label">Lihat situs</span>
            </a>
            <div class="admin-sidebar-profile">
                <span class="admin-avatar" aria-hidden="true"><?= esc(substr($adminUsername, 0, 1)) ?></span>
                <span class="admin-profile-copy">
                    <span class="admin-profile-name"><?= esc($adminUsername) ?></span>
                    <span class="admin-profile-role"><?= $isSuperadmin ? 'Admin utama' : 'Pengelola' ?></span>
                </span>
            </div>
            <a class="admin-nav-link" href="<?= esc(site_url('admin/akun')) ?>" aria-label="Akun saya" title="Akun saya">
                <svg class="admin-icon" viewBox="0 0 24 24" aria-hidden="true"><?= $navigationIcons['account'] ?></svg>
                <span class="admin-nav-label">Akun saya</span>
            </a>
            <form class="admin-account-form" method="post" action="<?= esc(site_url('admin/logout')) ?>">
                    <?= csrf_field() ?>
                <button class="admin-nav-link admin-sidebar-logout" type="submit" aria-label="Keluar" title="Keluar">
                    <svg class="admin-icon" viewBox="0 0 24 24" aria-hidden="true"><?= $navigationIcons['logout'] ?></svg>
                    <span class="admin-sidebar-logout-label">Keluar</span>
                </button>
            </form>
        </div>
    </aside>
    <button class="admin-sidebar-backdrop" type="button" data-sidebar-backdrop aria-label="Tutup menu admin"></button>
    <div class="admin-workspace">
        <main class="admin-main">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
    <script src="<?= esc(base_url('assets/vendor/bootstrap/bootstrap.bundle.min.js')) ?>"></script>
    <script src="<?= esc(base_url('assets/js/admin-content-editor.js')) ?>" defer></script>
    <script src="<?= esc(base_url('assets/js/admin-image-editor.js')) ?>" defer></script>
    <script {csp-script-nonce}>
        const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');
        const sidebarToggle = sidebarToggles[0];
        const sidebarBackdrop = document.querySelector('[data-sidebar-backdrop]');
        const adminSidebar = document.querySelector('#admin-sidebar');
        const sidebarStorageKey = 'jemaat-admin-sidebar-collapsed';
        const desktopSidebar = window.matchMedia('(min-width: 992px)');
        const getSidebarPreference = () => {
            try {
                return window.localStorage.getItem(sidebarStorageKey) === 'true';
            } catch (error) {
                console.warn('Preferensi sidebar tidak dapat dibaca dari penyimpanan browser.', error);
                return false;
            }
        };
        const saveSidebarPreference = (collapsed) => {
            try {
                window.localStorage.setItem(sidebarStorageKey, String(collapsed));
            } catch (error) {
                console.warn('Preferensi sidebar tidak dapat disimpan di penyimpanan browser.', error);
            }
        };

        const setSidebarState = (open) => {
            if (desktopSidebar.matches) {
                adminSidebar.inert = false;
                adminSidebar.removeAttribute('aria-hidden');
                document.body.classList.remove('admin-sidebar-open');
                document.body.classList.toggle('admin-sidebar-collapsed', !open);
                saveSidebarPreference(!open);
            } else {
                adminSidebar.inert = !open;
                adminSidebar.setAttribute('aria-hidden', String(!open));
                document.body.classList.remove('admin-sidebar-collapsed');
                document.body.classList.toggle('admin-sidebar-open', open);
            }

            sidebarToggles.forEach((toggle) => {
                toggle.setAttribute('aria-expanded', String(open));
                toggle.setAttribute('aria-label', open ? 'Tutup menu samping' : 'Buka menu samping');
                toggle.setAttribute('title', open ? 'Tutup menu samping' : 'Buka menu samping');
            });
        };

        setSidebarState(desktopSidebar.matches ? !getSidebarPreference() : false);

        sidebarToggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const isOpen = desktopSidebar.matches
                    ? !document.body.classList.contains('admin-sidebar-collapsed')
                    : document.body.classList.contains('admin-sidebar-open');
                setSidebarState(!isOpen);

                if (!desktopSidebar.matches && !isOpen) {
                    document.querySelector('#admin-sidebar nav a')?.focus();
                }
            });
        });

        sidebarBackdrop.addEventListener('click', () => {
            setSidebarState(false);
            sidebarToggle.focus();
        });
        document.querySelectorAll('#admin-sidebar a').forEach((link) => {
            link.addEventListener('click', () => {
                if (!desktopSidebar.matches) {
                    setSidebarState(false);
                }
            });
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && document.body.classList.contains('admin-sidebar-open')) {
                setSidebarState(false);
                sidebarToggle.focus();
            }
        });

        desktopSidebar.addEventListener('change', (event) => {
            document.body.classList.remove('admin-sidebar-open');
            const shouldOpen = event.matches
                ? !getSidebarPreference()
                : false;
            setSidebarState(shouldOpen);
        });

        document.querySelectorAll('[data-confirm-delete]').forEach((form) => {
            form.addEventListener('submit', (event) => {
                if (!window.confirm(form.dataset.confirmDelete || 'Hapus warta ini? Tindakan ini tidak dapat dibatalkan.')) {
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
