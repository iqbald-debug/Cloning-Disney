# Disney+ Clone — Tugas Besar Praktikum

Aplikasi web streaming kloning **Disney+** yang dibangun menggunakan **Laravel v13** dengan penerapan **Pemrograman Berorientasi Objek (PBO)**, **SOLID Principle**, **Relational Database**, dan **REST API (`/api/v1`)**.

## Penerapan Konsep Utama
- **Abstraksi:** `app/Contracts/*` (`MediaProviderInterface`, `PlayableInterface`, `SearchableInterface`, `WatchlistRepositoryInterface`) dan `AbstractMediaService`.
- **Enkapsulasi:** Properti `private` & `protected` pada Service/Repository serta `$fillable`, `$hidden`, dan `$casts` pada Model Eloquent.
- **Pewarisan (Inheritance):** `MovieContentService`, `SeriesContentService`, `LocalDatabaseProvider`, dan `TmdbApiProvider` mewarisi `AbstractMediaService`.
- **Polimorfisme:** Method `calculateDurationLabel()` dan `formatStreamMetadata()` menghasilkan format otomatis berbeda pada `MovieContentService` (`3h 1m`) vs `SeriesContentService` (`2 Seasons`).
- **SOLID Principle:** Pemisahan tanggung jawab ke dalam `Controller`, `FormRequest`, `JsonResource`, `Service`, `Repository`, dan Dependency Injection pada `AppServiceProvider`.

## Cara Menjalankan Proyek
1. Clone repository dan masuk ke direktori proyek:
   ```bash
   git clone [https://github.com/ccaramey/TUBES-CLONING-DISNEY.git](https://github.com/ccaramey/TUBES-CLONING-DISNEY.git)
   cd TUBES-CLONING-DISNEY