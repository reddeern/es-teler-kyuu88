# Dokumentasi API - Es Teler Kyuu

Domain Utama: `https://estelerkyuu.alwaysdata.net`

## 1. REST API (Header: `Accept: application/json`)
Semua endpoint di bawah ini menggunakan prefix `/api`. Untuk endpoint terproteksi, gunakan Header `Authorization: Bearer {token}`.

### Autentikasi
| Method | Endpoint | Fungsi | Ket. |
| :--- | :--- | :--- | :--- |
| POST | `/api/auth/login` | Login user & dapatkan token | Public |
| POST | `/api/auth/logout` | Logout & hapus token | Auth |
| GET | `/api/auth/me` | Profil user login | Auth |

### Master Data Produk
| Method | Endpoint | Fungsi | Ket. |
| :--- | :--- | :--- | :--- |
| GET | `/api/master/produk` | Daftar semua produk | Auth |
| POST | `/api/master/produk` | Tambah produk baru | Auth |
| GET | `/api/master/produk/{id}` | Detail produk | Auth |
| PUT | `/api/master/produk/{id}` | Update data produk | Auth |
| DELETE| `/api/master/produk/{id}` | Hapus produk | Auth |

### Transaksi & Kasir
| Method | Endpoint | Fungsi | Ket. |
| :--- | :--- | :--- | :--- |
| GET | `/api/kasir/katalog` | Produk siap jual (status aktif) | Auth |
| POST | `/api/kasir/checkout` | Simpan transaksi baru | Auth |
| GET | `/api/kasir/riwayat` | Daftar riwayat transaksi | Auth |
| GET | `/api/kasir/riwayat/{id}` | Detail transaksi | Auth |
| GET | `/api/kasir/struk/{id}` | Data khusus untuk struk | Auth |

### Laporan
| Method | Endpoint | Fungsi | Ket. |
| :--- | :--- | :--- | :--- |
| GET | `/api/analitik/laporan-penjualan` | Laporan total penjualan | Auth |

---

## 2. View API (Akses via Browser)
Endpoint ini digunakan untuk mengecek JSON langsung di browser. Menggunakan session login web (bukan token). Prefix: `/view-api`.

| Method | Endpoint | Penjelasan Singkat |
| :--- | :--- | :--- |
| GET | `/view-api/produk` | Lihat JSON semua produk |
| GET | `/view-api/katalog` | Lihat JSON produk aktif |
| GET | `/view-api/riwayat` | Lihat JSON riwayat transaksi |
| GET | `/view-api/riwayat/{id}` | Detail transaksi via browser |
| GET | `/view-api/struk` | List transaksi untuk struk |
| GET | `/view-api/laporan` | JSON Laporan penjualan |
| GET | `/view-api/me` | Cek user yang sedang login |

---
**Catatan:** Untuk endpoint `/view-api`, pastikan Anda sudah login ke aplikasi melalui halaman web biasa terlebih dahulu.
