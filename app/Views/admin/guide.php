<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<header class="mb-4">
    <h1 class="admin-page-title mb-2">Buku panduan pengelola</h1>
    <p class="admin-muted mb-0">Langkah praktis untuk mengelola jadwal, pesan, warta, dan isi situs jemaat.</p>
</header>

<div class="admin-guide-layout">
    <nav class="admin-guide-index" aria-label="Daftar isi buku panduan">
        <a href="#mulai">Mulai menggunakan</a>
        <a href="#jadwal">Jadwal ibadah</a>
        <a href="#pesan">Kotak pesan</a>
        <a href="#warta">Warta &amp; berita</a>
        <a href="#editor">Editor halaman</a>
        <a href="#galeri">Galeri foto</a>
        <a href="#akun">Akun dan keamanan</a>
        <?php if ($isSuperadmin): ?>
            <a href="#pengelola">Akun pengelola</a>
        <?php endif; ?>
    </nav>

    <article class="admin-guide-content">
        <section id="mulai" aria-labelledby="panduan-mulai">
            <h2 id="panduan-mulai">Mulai menggunakan panel</h2>
            <p>Gunakan menu di sisi kiri untuk berpindah bagian. Di komputer, klik logo atau ikon menu untuk membuka dan menciutkan sidebar. Di ponsel, buka menu dengan tombol hamburger; ketuk area gelap atau tekan Escape untuk menutupnya.</p>
            <div class="admin-guide-callout">
                Perubahan pada situs publik berlaku setelah berhasil disimpan. Periksa kembali halaman situs setelah menyunting agar isi dan foto tampil sesuai.
            </div>
            <h3>Akses sesuai peran</h3>
            <ul>
                <li><strong>Admin utama</strong> dapat mengelola seluruh bagian panel, termasuk membuat dan mengatur akun pengelola.</li>
                <li><strong>Pengelola/editor</strong> dapat membantu mengelola jadwal, pesan, warta, isi halaman, dan galeri. Menu akun pengelola hanya tersedia untuk admin utama.</li>
            </ul>
        </section>

        <section id="jadwal" aria-labelledby="panduan-jadwal">
            <h2 id="panduan-jadwal">Mengelola jadwal ibadah</h2>
            <ol>
                <li>Buka <a href="<?= esc(site_url('admin/jadwal')) ?>">Jadwal ibadah</a>, lalu pilih tambah jadwal atau ubah jadwal yang sudah ada.</li>
                <li>Isi tanggal, jenis ibadah, waktu, dan lokasi. Waktu ditampilkan dalam WIT. Nama pelayan dan catatan boleh dikosongkan.</li>
                <li>Simpan perubahan dan periksa halaman Jadwal pada situs publik. Jadwal mendatang akan ditampilkan kepada pengunjung.</li>
            </ol>
            <p class="mb-0">Sebelum menghapus jadwal, pastikan jadwal tersebut memang tidak lagi diperlukan.</p>
        </section>

        <section id="pesan" aria-labelledby="panduan-pesan">
            <h2 id="panduan-pesan">Menindaklanjuti pesan jemaat</h2>
            <ol>
                <li>Buka <a href="<?= esc(site_url('admin/pesan')) ?>">Kotak pesan</a> dan periksa pesan baru.</li>
                <li>Pilih penanggung jawab agar tim mengetahui siapa yang akan menindaklanjuti pesan. Semua pengelola aktif dapat melihat kotak pesan bersama.</li>
                <li>Perbarui status menjadi <strong>Baru</strong>, <strong>Diproses</strong>, atau <strong>Selesai</strong> sesuai tindak lanjut.</li>
                <li>Untuk membalas, gunakan tautan alamat email pengirim. Email balasan dikirim melalui aplikasi email, bukan dari dalam panel.</li>
            </ol>
            <p class="mb-0">Menghapus pesan bersifat permanen. Pastikan informasi sudah tidak diperlukan sebelum mengonfirmasi penghapusan.</p>
        </section>

        <section id="warta" aria-labelledby="panduan-warta">
            <h2 id="panduan-warta">Menerbitkan warta dan berita</h2>
            <ol>
                <li>Buka <a href="<?= esc(site_url('admin/warta')) ?>">Warta &amp; Berita</a>, lalu pilih tulis warta atau ubah tulisan.</li>
                <li>Isi judul dan konten. Ringkasan, kategori, dan foto dapat digunakan untuk melengkapi tulisan; isi teks alternatif foto agar lebih mudah diakses.</li>
                <li>Pilih status draf jika tulisan belum siap atau terbit jika ingin menampilkannya kepada publik.</li>
                <li>Simpan, lalu periksa halaman Warta di situs publik.</li>
            </ol>
            <p class="mb-0">Foto yang diunggah harus berupa JPG, PNG, atau WebP dan berukuran maksimal 5 MB.</p>
        </section>

        <section id="editor" aria-labelledby="panduan-editor">
            <h2 id="panduan-editor">Menggunakan editor halaman</h2>
            <ol>
                <li>Buka <a href="<?= esc(site_url('admin/konten')) ?>">Editor halaman</a>, lalu pilih halaman yang akan disunting dari menu halaman.</li>
                <li>Ubah teks pada bagian yang tersedia. Pratinjau di samping formulir (atau di atasnya pada layar kecil) membantu melihat perubahan sebelum disimpan.</li>
                <li>Di Beranda, gunakan tombol naik/turun untuk mengubah urutan bagian jika diperlukan.</li>
                <li>Tekan tombol simpan, lalu buka halaman publik untuk memastikan perubahan sudah tampil.</li>
            </ol>
            <h3>Mengganti foto Beranda</h3>
            <p class="mb-0">Gunakan bagian pengelolaan foto di editor Beranda untuk mengganti atau mengembalikan foto bawaan. Foto JPG, PNG, atau WebP maksimal 5 MB. Pratinjau foto dapat dilihat sebelum perubahan disimpan.</p>
        </section>

        <section id="galeri" aria-labelledby="panduan-galeri">
            <h2 id="panduan-galeri">Mengelola galeri foto</h2>
            <ol>
                <li>Buka <a href="<?= esc(site_url('admin/galeri')) ?>">Galeri</a> untuk menambah, mengubah keterangan, atau menghapus foto.</li>
                <li>Saat menambah foto, isi keterangan dan teks alternatif yang menjelaskan isi foto.</li>
                <li>Gunakan foto JPG, PNG, atau WebP maksimal 5 MB, lalu periksa hasilnya di halaman Galeri publik.</li>
            </ol>
        </section>

        <section id="akun" aria-labelledby="panduan-akun">
            <h2 id="panduan-akun">Kata sandi dan keamanan akun</h2>
            <ul>
                <li>Buka <a href="<?= esc(site_url('admin/akun')) ?>">Akun saya</a> untuk mengubah kata sandi. Masukkan kata sandi saat ini dan kata sandi baru minimal 12 karakter.</li>
                <li>Jangan berbagi kata sandi melalui kotak pesan atau grup umum. Setiap pengelola sebaiknya menggunakan akunnya sendiri.</li>
                <li>Keluar dari panel setelah selesai, terutama saat menggunakan perangkat bersama.</li>
            </ul>
        </section>

        <?php if ($isSuperadmin): ?>
            <section id="pengelola" aria-labelledby="panduan-pengelola">
                <h2 id="panduan-pengelola">Mengatur akun pengelola</h2>
                <ol>
                    <li>Buka <a href="<?= esc(site_url('admin/pengelola')) ?>">Akun pengelola</a> untuk membuat akun terpisah bagi anggota tim yang dipercaya.</li>
                    <li>Gunakan nama pengguna yang mudah dikenali dan kata sandi awal minimal 12 karakter. Sampaikan informasi masuk secara pribadi; minta pengelola mengganti kata sandi setelah masuk.</li>
                    <li>Nonaktifkan akun sementara bila akses perlu dihentikan. Hapus akun hanya jika memang tidak diperlukan lagi.</li>
                </ol>
                <p class="mb-0">Akun yang sedang digunakan tidak dapat dinonaktifkan atau dihapus. Admin utama aktif terakhir juga tidak dapat dinonaktifkan atau dihapus.</p>
            </section>
        <?php endif; ?>
    </article>
</div>
<?= $this->endSection() ?>
