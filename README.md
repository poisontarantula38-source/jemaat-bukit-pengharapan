# Website Jemaat GPM Bukit Pengharapan

Website informasi dan layanan digital Gereja Elpido, Jemaat GPM Bukit
Pengharapan. Aplikasi ini dibangun dengan CodeIgniter 4, PHP, dan MySQL.

Fitur publik mencakup profil jemaat, jadwal ibadah, pelayanan, Warta dan
berita, galeri, serta kontak. Panel admin digunakan untuk mengelola konten,
jadwal, pesan, galeri, dan akun pengelola.

## Requirements

- PHP 8.1 atau lebih baru dengan ekstensi yang dibutuhkan CodeIgniter 4
- Composer
- MySQL atau MariaDB

## Installation

Install dependencies, salin `.env.example` menjadi `.env`, lalu sesuaikan
URL aplikasi dan koneksi database:

```sh
composer install
copy .env.example .env
```

Pada Linux/macOS, gunakan `cp .env.example .env`. Isi database dan atur
`CI_ENVIRONMENT = development` hanya untuk pengembangan lokal. Untuk server
produksi gunakan `CI_ENVIRONMENT = production`, HTTPS, serta kredensial yang
unik dan kuat. Jangan unggah `.env` atau kredensial ke GitHub.

Buat database terlebih dahulu, kemudian jalankan:

```sh
php spark migrate
```

Buat akun admin utama melalui `/admin/setup`, atau isi `ADMIN_USERNAME` dan
`ADMIN_PASSWORD` di `.env` lalu jalankan `php spark db:seed AdminUserSeeder`.
Password admin minimal 12 karakter. Setelah admin utama tersedia, akun
pengelola tambahan dibuat dari dashboard.

Jalankan server pengembangan lokal dengan:

```sh
php spark serve
```

Lalu buka `http://localhost:8080`. Untuk detail fitur dan alur pengelolaan,
lihat bagian-bagian panduan di bawah.

## Installation & updates

Dependencies proyek ini dikelola dengan Composer dan versi terkuncinya ada di
`composer.lock`. Untuk memasang ulang dependencies, jalankan `composer install`.

When updating, check the release notes to see if there are any changes you might need to apply
to your `app` folder. The affected files can be copied or merged from
`vendor/codeigniter4/framework/app`.

## Setup

Salin `.env.example` menjadi `.env` dan sesuaikan `app.baseURL` serta pengaturan
database. Simpan `.env` hanya di perangkat/server Anda; file tersebut
dikecualikan dari Git.

## Admin Warta

The admin panel is available at `/admin/login`. Configure the MySQL connection
in `.env`, then run the database migration and create the first admin account:

```ini
database.default.hostname = localhost
database.default.database = jemaat_bukit_pengharapan
database.default.username = your_database_user
database.default.password = your_database_password
database.default.DBDriver = MySQLi

ADMIN_USERNAME = admin
ADMIN_PASSWORD = use-a-unique-password-with-at-least-12-characters
```

Keep `.env` private and replace every example value. Then run:

```sh
php spark migrate
php spark db:seed AdminUserSeeder
```

Alternatively, after running the migration, open `/admin/setup` to create the
first admin account in the browser. This setup page is available only while no
admin account exists; after the first account is created, it is locked. The
seeder creates the configured account only when its username does not already
exist. Admins can create, edit, publish, and delete Warta & Berita;
published entries appear on the public homepage and Warta page. Warta forms
accept an optional JPG, PNG, or WebP photo up to 5 MB, with alternative text.
Photos are stored in `public/uploads/warta`; ensure the web server can write to
that directory.

There is no public admin sign-up. The first account becomes **Admin utama**;
use **Akun pengelola** in the dashboard to create separate **Pengelola** accounts
for the rest of the team. To set up four people, keep the first account as the
admin utama and create three pengelola accounts. The admin utama can activate or
deactivate accounts; a disabled account loses access on its next request.
Accounts can also be permanently deleted; any contact-message assignment keeps
the former manager's username as a history note. The current account and the
last active admin utama cannot be deleted. Each pengelola can change their own
password from **Akun** after signing in. The admin navigation is in a left
sidebar: use the menu button in the top bar to collapse it on desktop or open
and close it on smaller screens. On mobile, the menu also closes with Escape or
by selecting its backdrop.

Use **Jadwal ibadah** to add and update confirmed service dates, times in WIT,
locations, ministers, and notes. Future schedules appear on the public `/jadwal`
page. Use **Kotak pesan** to review messages sent from the public Kontak form,
assign a responsible pengelola, and track each message as Baru, Sedang ditangani,
or Selesai. Messages are stored in the configured database and remain visible
to all active admins; replies are sent by email using the sender's email link.
Admins can permanently delete an individual message from the inbox after
confirming the action.

The admin sidebar includes **Buku panduan** with step-by-step instructions for
managing schedules, shared contact messages, Warta & Berita, page content,
gallery photos, and account security. Admin utama also see instructions for
creating and maintaining pengelola accounts.

Use **Editor halaman** to edit headings, introductions, empty-state copy,
profile roster, and service lists. A live site preview appears beside the form
on desktop and above it on smaller screens; edits appear in the preview as you
type. Each editor form is paired with its matching public page; Profil and
Pelayanan each have a separate form for every child page. Internal links work
in the preview, while preview forms are prevented from submitting. Use the
preview controls to switch between desktop and phone widths. Changes are public
only after you save them. On Beranda, the **Naik** and **Turun** buttons change
section order. Any text edits in the form are saved along with the new order.

The **Kelola foto situs** panel in Editor halaman lets admins replace or restore
Beranda's banner and profile photos, and add, edit, or delete gallery photos.
Photo changes appear in the preview before saving. Warta article photos remain
managed with their articles from **Kelola foto Warta**. Image editing works in
the browser before the file is uploaded. Custom Beranda photos are stored in
`public/uploads/site`; ensure the web server can write to that directory.

Use **Galeri** to manage older photos beyond the latest 12 shown in Editor
halaman. The admin page accepts JPG, PNG, and WebP images up to 5 MB, plus a
caption and optional alternative text. Uploaded files are stored locally in
`public/uploads/gallery` and are displayed immediately on `/galeri` and the
homepage. Ensure the web server can write to that directory. The local gallery
is not shared with other devices unless the website is intentionally hosted or
deployed to a shared server.

## Public navigation

The **Profil** menu groups separate pages for Sejarah Jemaat, Visi & Misi, and
Struktur Majelis, while the **Pelayanan** menu groups separate pages for Wadah
Pelayanan and Sektor & Unit Pelayanan. Both menus open directly to their
individual pages without an overview page. The old `/profil` and `/pelayanan`
URLs redirect to Sejarah Jemaat and Wadah Pelayanan, respectively.

## Important Change with index.php

`index.php` is no longer in the root of the project! It has been moved inside the *public* folder,
for better security and separation of components.

This means that you should configure your web server to "point" to your project's *public* folder, and
not to the project root. A better practice would be to configure a virtual host to point there. A poor practice would be to point your web server to the project root and expect to enter *public/...*, as the rest of your logic and the
framework are exposed.

**Please** read the user guide for a better explanation of how CI4 works!

## Repository Management

We use GitHub issues, in our main repository, to track **BUGS** and to track approved **DEVELOPMENT** work packages.
We use our [forum](http://forum.codeigniter.com) to provide SUPPORT and to discuss
FEATURE REQUESTS.

This repository is a "distribution" one, built by our release preparation script.
Problems with it can be raised on our forum, or as issues in the main repository.

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

> [!WARNING]
> - The end of life date for PHP 7.4 was November 28, 2022.
> - The end of life date for PHP 8.0 was November 26, 2023.
> - If you are still using PHP 7.4 or 8.0, you should upgrade immediately.
> - The end of life date for PHP 8.1 will be December 31, 2025.

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [mysqlnd](http://php.net/manual/en/mysqlnd.install.php) if you plan to use MySQL
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
