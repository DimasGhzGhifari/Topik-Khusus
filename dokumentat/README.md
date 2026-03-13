//Prompt
Saya ingin membuat sebuah **aplikasi backend menggunakan bahasa Go (Golang) dengan Redis sebagai database utama** untuk mengelola **sistem keberangkatan mobil (vehicle departure system)**.

Aplikasi ini harus dirancang menggunakan **konsep Clean Architecture** dengan pemisahan tanggung jawab yang jelas antara setiap layer.

Struktur proyek yang diharapkan adalah sebagai berikut:

* **documentation/** → berisi dokumentasi sistem seperti penjelasan arsitektur, alur kerja sistem, serta contoh penggunaan API atau fungsi utama.
* **domain/** → berisi entity atau model utama dari sistem, seperti data mobil, jadwal keberangkatan, tujuan perjalanan, dan status kendaraan.
* **repository/** → berisi implementasi akses data yang berinteraksi langsung dengan Redis, seperti penyimpanan data mobil, antrian keberangkatan, serta pengelolaan status kendaraan.
* **usecase/** → berisi logika bisnis utama sistem, seperti proses penjadwalan keberangkatan, pengelolaan antrian mobil, pembaruan status kendaraan, serta pencatatan waktu keberangkatan.
* **main.go** → file utama yang menjalankan aplikasi, menginisialisasi koneksi Redis, serta menjalankan use case yang tersedia.
* **go.mod** dan **go.sum** → untuk manajemen dependensi proyek Go.

Fitur utama yang diharapkan dalam aplikasi ini antara lain:

1. **Manajemen Data Mobil**
   Sistem dapat menyimpan dan mengambil data mobil seperti ID mobil, nama sopir, tujuan perjalanan, dan waktu keberangkatan.

2. **Sistem Antrian Keberangkatan**
   Menggunakan Redis List atau Queue untuk mengatur urutan mobil yang akan berangkat.

3. **Status Kendaraan**
   Menyimpan status kendaraan seperti *menunggu*, *siap berangkat*, dan *sudah berangkat* menggunakan Redis.

4. **Pencatatan Waktu Keberangkatan**
   Sistem dapat mencatat waktu keberangkatan setiap mobil dan menyimpannya di Redis.

5. **Simulasi Operasi Sistem**
   Pada file `main.go`, buat contoh simulasi penggunaan sistem seperti:

   * menambahkan mobil ke antrian keberangkatan
   * memproses mobil yang berangkat
   * menampilkan sisa antrian mobil
   * menampilkan status kendaraan

Gunakan **library Redis untuk Golang (go-redis)** dan pastikan struktur kode mudah dipahami, modular, serta mengikuti praktik terbaik dalam pengembangan backend menggunakan Golang.

===========================================================================================

# Sistem Keberangkatan Mobil (Vehicle Departure System)

Aplikasi backend menggunakan bahasa Go (Golang) dengan Redis sebagai database utama untuk mengelola sistem keberangkatan mobil.

## Arsitektur Sistem

Aplikasi ini dirancang menggunakan **Clean Architecture** dengan pemisahan tanggung jawab yang jelas:

- **Domain Layer**: Berisi entity bisnis utama (Vehicle, Departure)
- **Repository Layer**: Interface dan implementasi akses data Redis
- **Usecase Layer**: Logika bisnis dan aturan aplikasi
- **Main Layer**: Entry point dan konfigurasi aplikasi

## Struktur Proyek

```
├── dokumentat/          # Dokumentasi sistem
├── domain/             # Entity dan model bisnis
│   ├── car.go          # Model Vehicle
│   └── departure.go    # Model Departure
├── repository/         # Akses data Redis
│   ├── car_repository.go
│   └── departure_repository.go
├── usecase/            # Logika bisnis
│   ├── car_usecase.go
│   └── departure_usecase.go
├── main.go             # Entry point aplikasi
├── go.mod              # Dependency management
└── go.sum              # Checksum dependencies
```

## Fitur Utama

### 1. Manajemen Data Mobil
- Penyimpanan data mobil (ID, nama sopir, tujuan, waktu keberangkatan)
- Pembaruan status kendaraan (menunggu, siap_berangkat, sudah_berangkat)

### 2. Sistem Antrian Keberangkatan
- Menggunakan Redis List untuk mengatur urutan mobil
- Penambahan mobil ke antrian
- Pemrosesan mobil yang berangkat (FIFO)

### 3. Pencatatan Waktu Keberangkatan
- Pencatatan waktu aktual keberangkatan
- Riwayat keberangkatan tersimpan di Redis

## Alur Kerja Sistem

1. **Pendaftaran Mobil**: Mobil didaftarkan dengan data awal dan status "menunggu"
2. **Penjadwalan**: Mobil dijadwalkan untuk berangkat, status berubah ke "siap_berangkat" dan masuk antrian
3. **Pemrosesan**: Sistem memproses mobil dari antrian, mencatat waktu keberangkatan, dan update status ke "sudah_berangkat"

## Cara Menjalankan

1. Pastikan Redis server berjalan di `localhost:6379`
2. Jalankan `go mod tidy` untuk install dependencies
3. Jalankan `go run main.go` untuk menjalankan simulasi

## Contoh Output

```
Terhubung ke Redis: PONG

=== MENAMBAHKAN MOBIL ===
Mobil V001 ditambahkan dengan status: menunggu
Mobil V002 ditambahkan dengan status: menunggu
Mobil V003 ditambahkan dengan status: menunggu

=== MENJADWALKAN KEBERANGKATAN ===
Mobil V001 dijadwalkan untuk berangkat ke Jakarta
Mobil V002 dijadwalkan untuk berangkat ke Bandung
Mobil V003 dijadwalkan untuk berangkat ke Surabaya

Panjang antrian keberangkatan: 3

=== MEMPROSES KEBERANGKATAN ===
Mobil V001 telah berangkat pada 14:30:25
Mobil V002 telah berangkat pada 14:30:25
Mobil V003 telah berangkat pada 14:30:25

Sisa antrian: 0

=== STATUS SEMUA MOBIL ===
Mobil V001 - Sopir: John Doe, Tujuan: Jakarta, Status: sudah_berangkat
Mobil V002 - Sopir: Jane Smith, Tujuan: Bandung, Status: sudah_berangkat
Mobil V003 - Sopir: Bob Johnson, Tujuan: Surabaya, Status: sudah_berangkat

=== RIWAYAT KEBERANGKATAN ===
ID: dep_V001_1700000000, Mobil: V001, Waktu: 14:30:25
ID: dep_V002_1700000000, Mobil: V002, Waktu: 14:30:25
ID: dep_V003_1700000000, Mobil: V003, Waktu: 14:30:25
```

## Teknologi

- **Bahasa**: Go (Golang)
- **Database**: Redis
- **Library**: github.com/redis/go-redis/v9
- **Arsitektur**: Clean Architecture

