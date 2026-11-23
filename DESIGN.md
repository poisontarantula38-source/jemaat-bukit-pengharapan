---
name: Website Informasi Jemaat Bukit Pengharapan
description: Identitas publik yang hangat dan mudah dibaca, memakai palet biru-gading, tipografi modern yang bersih, serta interaksi responsif untuk jemaat.
colors:
  bg-primary: "#FFFEFA"
  bg-secondary: "#F5F1F6"
  bg-tertiary: "#E2EEF8"
  card-bg: "#FFFEFA"
  brand-blue: "#0d6efd"
  brand-blue-dark: "#0b5ed7"
  brand-blue-hover: "#0a58ca"
  brand-gold: "#D8BD72"
  text-main: "#29232C"
  text-muted: "#5D5361"
typography:
  display:
    fontFamily: "'Inter', 'Segoe UI', sans-serif"
    fontWeight: 600
    letterSpacing: "-.02em"
  body:
    fontFamily: "'Inter', 'Segoe UI', sans-serif"
    fontWeight: 400, 600, 700
    lineHeight: 1.65
rounded:
  control: "0.5rem"
  card: "1rem"
  panel: "1.6rem"
  pill: "9999px"
---

# Design System: Website Jemaat Bukit Pengharapan

## Overview

Antarmuka publik memakai komposisi editorial yang mengutamakan identitas jemaat, warta, dan jadwal ibadah. Implementasi memakai PHP Views (CodeIgniter 4), Bootstrap 5, dan CSS khusus. Panduan ini mencatat token serta perilaku antarmuka yang diterapkan pada proyek.

## 1. Warna

- **Latar utama:** `#FFFEFA`, gading lembut.
- **Permukaan sekunder:** `#F5F1F6`; aksen permukaan hangat `#E2EEF8`.
- **Aksen utama:** biru profesional `#0d6efd`; biru gelap `#0b5ed7` untuk tautan dan hover.
- **Aksen emas:** `#D8BD72`.
- **Teks utama:** `#29232C`; teks sekunder `#5D5361`.

Gunakan warna melalui token CSS pada `:root` agar elemen publik tetap konsisten.

## 2. Tipografi

- **Inter / Sans-serif Modern, 600:** judul halaman dan bagian.
- **Inter, 400/600/700:** isi, metadata, tombol, dan antarmuka.
- Ukuran teks isi dasar 16-18 px dengan line-height 1.65 agar ramah dibaca oleh seluruh kalangan usia jemaat.

## 3. Bentuk, Permukaan, dan Hierarki

- Kartu konten menggunakan radius sudut `1rem`; panel hero menggunakan radius `1.6rem`. Kontrol kecil memakai sudut `0.5rem`.
- Header publik bersifat *sticky* dengan latar semi-transparan, efek blur, dan bayangan lembut.
- Susun konten menggunakan grid Bootstrap dan urutan yang mendahulukan warta serta jadwal ibadah setelah bagian hero. Di ponsel, daftar tampil dalam satu kolom tanpa *overflow* horizontal.

## 4. Interaksi dan Aksesibilitas

- Kartu informasi dan panel hero memiliki transisi hover yang halus.
- Semua kontrol keyboard mempertahankan indikator fokus yang jelas.
- Navigasi menggunakan offcanvas Bootstrap pada layar kecil agar ramah pengguna *mobile*.
- Jadwal menampilkan jenis ibadah, waktu (WIT), lokasi, dan keterangan pelayan secara jelas.

## 5. Komponen Utama

- **Navbar:** Logo/nama gereja, menu navigasi aktif, dan tombol menu *offcanvas* seluler.
- **Hero Beranda:** Sambutan, identitas Jemaat Bukit Pengharapan, serta akses cepat ke jadwal ibadah.
- **Kartu Warta / Pengumuman:** Kategori, tanggal, judul, dan ringkasan informasi rohani.
- **Tabel Jadwal Ibadah:** Tanggal, jenis ibadah, waktu, dan lokasi yang tertata rapi responsif untuk HP.