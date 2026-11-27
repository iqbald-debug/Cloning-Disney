# Disney+ Clone — Tugas Besar Praktikum

Aplikasi web streaming kloning **Disney+** yang dibangun menggunakan **Laravel v13** dengan penerapan **Pemrograman Berorientasi Objek (PBO)**, **SOLID Principle**, **Relational Database**, dan **REST API (`/api/v1`)**.

## Anggota kelompok 
1. Ahmad Rahman N.S             - H1101251015
2. Muhammad Iqbal Dzakwani      - H1101251021
3. Muhammad Ghifari Rubiyanto   - H1101251022
4. Najwa Aulia Putri            - H1101251030
5. Fitri Amelia                 - H1101251054
6. Rizqi Ikhwani                - H1101251061

## Penerapan Konsep Utama
- **Abstraksi:** `app/Contracts/*` (`MediaProviderInterface`, `PlayableInterface`, `SearchableInterface`, `WatchlistRepositoryInterface`) dan `AbstractMediaService`.
- **Enkapsulasi:** Properti `private` & `protected` pada Service/Repository serta `$fillable`, `$hidden`, dan `$casts` pada Model Eloquent.
- **Pewarisan (Inheritance):** `MovieContentService`, `SeriesContentService`, `LocalDatabaseProvider`, dan `TmdbApiProvider` mewarisi `AbstractMediaService`.
- **Polimorfisme:** Method `calculateDurationLabel()` dan `formatStreamMetadata()` menghasilkan format otomatis berbeda pada `MovieContentService` (`3h 1m`) vs `SeriesContentService` (`2 Seasons`).
- **SOLID Principle:** Pemisahan tanggung jawab ke dalam `Controller`, `FormRequest`, `JsonResource`, `Service`, `Repository`, dan Dependency Injection pada `AppServiceProvider`.
