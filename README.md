<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/badge/license-MIT-brightgreen.svg" alt="License"></a>
</p>

---

# School SaaS — Sistem Manajemen Sekolah

Aplikasi web untuk mengelola kegiatan sekolah dalam satu tempat. Dibangun sebagai layanan SaaS sehingga satu aplikasi bisa dipakai oleh banyak sekolah sekaligus.

## Fitur-Fitur

- **Dashboard sekolah** — Ringkasan data dalam satu layar: jumlah siswa aktif, guru, kelas, dan mata pelajaran, plus rekap kehadiran hari ini dan pengumuman terbaru.
- **Manajemen sekolah** — Menambah dan mengelola data sekolah (nama, NPSN, alamat, kontak) untuk tiap instansi.
- **Manajemen siswa** — Pendataan siswa lengkap dengan data pribadi, kelas, jurusan, dan status aktif.
- **Manajemen guru** — Pendataan guru (NIP, nama, kontak) serta pemetaan guru ke mata pelajaran yang diampu.
- **Manajemen kelas** — Pembagian kelas per tingkat, pengelompokan berdasarkan jurusan, dan penunjukan wali kelas.
- **Manajemen jurusan** — Mengelola jurusan seperti RPL, TKJ, DKV, dan lainnya.
- **Manajemen mata pelajaran** — Daftar mata pelajaran lengkap dengan kode dan nama.
- **Absensi harian** — Pencatatan kehadiran siswa per kelas per tanggal, dengan status: hadir, sakit, izin, alpa, dan terlambat.
- **Tugas dan pengumpulan** — Guru membagikan tugas ke siswa, siswa mengumpulkan jawaban, dan guru bisa melihat serta mengunduh pengumpulan.
- **Pengumuman** — Membagikan informasi ke seluruh sekolah, tercantum langsung di dashboard.
- **Landing page** — Halaman depan berisi info fitur, harga (pricing), dan form kontak.

## Peran Pengguna

- **Super Admin** — Mengelola semua sekolah, bisa berpindah antar-sekolah, dan melihat gambaran seluruh data.
- **Admin Sekolah** — Mengelola data di sekolahnya sendiri: siswa, guru, kelas, jurusan, mata pelajaran, dan pengumuman.
- **Guru** — Mengisi absensi, membuat dan menilai tugas, serta melihat data siswa.
- **Siswa** — Melihat tugas, mengumpulkan jawaban, dan melihat kehadirannya.
- **Orang Tua** — Memantau kehadiran dan tugas anak.

## Cara Menggunakan

1. **Daftar atau masuk** — Setiap pengguna perlu akun dan email terverifikasi untuk bisa masuk.
2. **Super Admin** — Pertama kali masuk, Super Admin akan melihat daftar semua sekolah dan bisa menambah sekolah baru. Setiap akun yang dibuat setelahnya harus terhubung ke salah satu sekolah.
3. **Admin Sekolah** — Setelah masuk, mulai dari melengkapi data: jurusan → mata pelajaran → guru → kelas → siswa.
4. **Guru** — Buka menu Absensi untuk mencatat kehadiran per kelas, dan menu Tugas untuk membagikan serta menilai tugas.
5. **Siswa & Orang Tua** — Lihat informasi lewat dashboard: pengumuman, kehadiran, dan daftar tugas.

Semua menu disesuaikan otomatis dengan peran pengguna — yang tidak berhak tidak akan melihat menu tersebut.

## Tentang Laravel

Laravel adalah framework aplikasi web dengan sintaks yang ekspresif dan elegan. Framework ini dibangun di atas komponen Symfony untuk memberikan pengalaman pengembangan yang menyenangkan dan kreatif.

## Lisensi

Project ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).
