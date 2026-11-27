# Panduan Kerja Tim — Disney+ Clone

Dokumen ini menjelaskan cara kerja tim 5 orang untuk proyek `/Users/iqbal/Downloads/tubes`: siapa mengerjakan apa, urutan kerjanya, dan perintah terminal yang dipakai.

## Anggota Tim

| Nama | Tugas utama |
|---|---|
| **Amey** | Penanggung jawab repo, setup proyek, pengaturan awal, dokumentasi, mengecek dan menggabungkan Pull Request ke `main` |
| **Awa** | Database, tabel/migration, model, data contoh (seeder) |
| **Rizki** | Interface/contract, service, repository, pengaturan dependency injection |
| **Iqbal** | Validasi request, format response API, controller API, routes, testing |
| **Hao** | Tampilan frontend, penghubung frontend ke API, perbaikan tampilan, aset |

## Istilah Sederhana

| Istilah | Artinya |
|---|---|
| `main` | Branch utama. Isinya versi terbaru proyek yang sudah disepakati tim. |
| `branch` | Cabang kerja terpisah. Jadi kamu bisa kerja tanpa mengganggu `main`. |
| `git checkout main` | Pindah ke branch `main`. |
| `git pull origin main` | Ambil update terbaru dari `main` di GitHub ke komputer kamu. Ini penting supaya kamu kerja dari kode paling baru. |
| `commit` | Simpan checkpoint perubahan di Git, dengan pesan singkat apa yang dikerjakan. |
| `push` | Kirim commit dari komputer kamu ke GitHub. |
| `Pull Request (PR)` | Permintaan untuk menggabungkan branch kerjamu ke `main`. Teman tim bisa cek dulu sebelum digabung. |

## Penamaan Branch dan Pesan Commit

Gunakan nama yang sederhana dan jelas:

| Jenis pekerjaan | Contoh nama branch | Contoh pesan commit |
|---|---|---|
| Persiapan/setup/pengaturan | `tugas/setup-project` | `tugas: menyiapkan awal project laravel` |
| Fitur baru | `fitur/database-migrations` | `fitur: menambah migration database` |
| Perbaikan bug | `perbaikan/fix-watchlist` | `perbaikan: memperbaiki toggle watchlist` |
| Tes/testing | `tes/api-feature-tests` | `tes: menambah feature test api` |
| Perbaikan tampilan | `tampilan/ui-polish-assets` | `tampilan: merapikan tampilan responsive` |
| Dokumentasi | `dokumentasi/final-readme` | `dokumentasi: melengkapi readme proyek` |

## Aturan Penting

- **Jangan kerja langsung di `main`.** Buat branch sendiri untuk setiap tugas.
- **Selalu mulai dari `main` terbaru** sebelum membuat branch kerja.
- **Satu tahap = satu orang = satu branch = satu PR.**
- **Jangan push file sensitif/lokal:** `.env`, `vendor/`, `node_modules/`, cache, `.DS_Store`.
- `package-lock.json` boleh dipush agar versi dependensi npm tim sama.

## Cara Kerja Umum Setiap Tahap

Sebelum mulai tahap apa pun, pakai pola ini:

```bash
git checkout main         # pindah ke branch main
git pull origin main      # ambil update terbaru dari GitHub
git checkout -b nama-branch-kamu   # buat branch kerja baru dari main terbaru
```

Setelah selesai mengerjakan:

```bash
git add .                 # atau git add nama-file
git commit -m "pesan commit"
git push -u origin nama-branch-kamu
# lalu buka Pull Request ke main di GitHub
```

## Tahapan Kerja

### Tahap 0 — Amey: Menyiapkan awal proyek
**Branch:** `tugas/setup-project`

```bash
git checkout main
git pull origin main
git checkout -b tugas/setup-project
# kerjakan: setup Laravel, .gitignore, .editorconfig, .env.example, struktur dasar
git add .gitignore .editorconfig .env.example artisan bootstrap composer.json
git commit -m "tugas: menyiapkan awal project laravel"
git push -u origin tugas/setup-project
# buka PR: tugas/setup-project -> main
```

### Tahap 1 — Amey: Mengatur dependensi dan konfigurasi
**Branch:** `tugas/config-dependencies`

```bash
git checkout main
git pull origin main
git checkout -b tugas/config-dependencies
# kerjakan: composer.json, package.json, package-lock.json, vite.config.js, config Tailwind/Sanctum
git add composer.json composer.lock package.json package-lock.json vite.config.js config/sanctum.php
git commit -m "tugas: mengatur vite tailwind dan dependensi proyek"
git push -u origin tugas/config-dependencies
# buka PR: tugas/config-dependencies -> main
```

### Tahap 2 — Awa: Membuat migration database
**Branch:** `fitur/database-migrations`

```bash
git checkout main
git pull origin main
git checkout -b fitur/database-migrations
# kerjakan: migration brands, contents, profiles, watchlists, personal_access_tokens, dll
git add database/migrations
git commit -m "fitur: menambah migration database disney clone"
git push -u origin fitur/database-migrations
# buka PR: fitur/database-migrations -> main
```

### Tahap 3 — Awa: Membuat model dan relasi
**Branch:** `fitur/models-relations`

```bash
git checkout main
git pull origin main
git checkout -b fitur/models-relations
# kerjakan: app/Models/User.php, Brand.php, Content.php, Profile.php, Watchlist.php
git add app/Models
git commit -m "fitur: menambah model eloquent dan relasi"
git push -u origin fitur/models-relations
# buka PR: fitur/models-relations -> main
```

### Tahap 4 — Awa: Membuat seeder dan factory
**Branch:** `fitur/seeders-data`

```bash
git checkout main
git pull origin main
git checkout -b fitur/seeders-data
# kerjakan: database/seeders/*, database/factories/*
git add database/seeders database/factories
git commit -m "fitur: menambah seeder dan factory data contoh"
git push -u origin fitur/seeders-data
# buka PR: fitur/seeders-data -> main
```

### Tahap 5 — Rizki: Membuat contract/interface
**Branch:** `fitur/contracts`

```bash
git checkout main
git pull origin main
git checkout -b fitur/contracts
# kerjakan: app/Contracts/*
git add app/Contracts
git commit -m "fitur: menambah contract untuk media dan watchlist"
git push -u origin fitur/contracts
# buka PR: fitur/contracts -> main
```

### Tahap 6 — Rizki: Membuat service dan repository
**Branch:** `fitur/services-repositories`

```bash
git checkout main
git pull origin main
git checkout -b fitur/services-repositories
# kerjakan: app/Services/*, app/Repositories/*
git add app/Services app/Repositories
git commit -m "fitur: menambah service dan repository"
git push -u origin fitur/services-repositories
# buka PR: fitur/services-repositories -> main
```

### Tahap 7 — Rizki: Mengatur dependency injection
**Branch:** `fitur/di-bindings`

```bash
git checkout main
git pull origin main
git checkout -b fitur/di-bindings
# kerjakan: app/Providers/AppServiceProvider.php
git add app/Providers/AppServiceProvider.php
git commit -m "fitur: mengatur binding service dan media resolver"
git push -u origin fitur/di-bindings
# buka PR: fitur/di-bindings -> main
```

### Tahap 8 — Iqbal: Membuat Form Request dan API Resource
**Branch:** `fitur/api-requests-resources`

```bash
git checkout main
git pull origin main
git checkout -b fitur/api-requests-resources
# kerjakan: app/Http/Requests/*, app/Http/Resources/*
git add app/Http/Requests app/Http/Resources
git commit -m "fitur: menambah form request dan json resource"
git push -u origin fitur/api-requests-resources
# buka PR: fitur/api-requests-resources -> main
```

### Tahap 9 — Iqbal: Membuat Controller API dan routes
**Branch:** `fitur/api-endpoints`

```bash
git checkout main
git pull origin main
git checkout -b fitur/api-endpoints
# kerjakan: app/Http/Controllers/Api/V1/*, routes/api.php
git add app/Http/Controllers/Api routes/api.php
git commit -m "fitur: menambah endpoint api v1 disney clone"
git push -u origin fitur/api-endpoints
# buka PR: fitur/api-endpoints -> main
```

### Tahap 10 — Hao: Membuat tampilan frontend
**Branch:** `fitur/frontend-ui`

```bash
git checkout main
git pull origin main
git checkout -b fitur/frontend-ui
# kerjakan: resources/views/*, resources/css/*, resources/js/*, routes/web.php
git add resources/views resources/css resources/js routes/web.php
git commit -m "fitur: menambah tampilan frontend disney plus"
git push -u origin fitur/frontend-ui
# buka PR: fitur/frontend-ui -> main
```

### Tahap 11 — Hao: Menghubungkan frontend dengan API
**Branch:** `fitur/frontend-api-integration`

```bash
git checkout main
git pull origin main
git checkout -b fitur/frontend-api-integration
# kerjakan: hubungkan fetch/axios ke /api/v1, watchlist toggle, loading/error state
git add resources/js resources/views
git commit -m "fitur: menghubungkan frontend dengan api v1"
git push -u origin fitur/frontend-api-integration
# buka PR: fitur/frontend-api-integration -> main
```

### Tahap 12 — Iqbal: Membuat test API
**Branch:** `tes/api-feature-tests`

```bash
git checkout main
git pull origin main
git checkout -b tes/api-feature-tests
# kerjakan: tests/Feature/*, tests/Unit/* jika perlu
git add tests
git commit -m "tes: menambah feature test api disney"
git push -u origin tes/api-feature-tests
# buka PR: tes/api-feature-tests -> main
```

### Tahap 13 — Hao: Merapikan tampilan dan aset
**Branch:** `tampilan/ui-polish-assets`

```bash
git checkout main
git pull origin main
git checkout -b tampilan/ui-polish-assets
# kerjakan: responsive, empty/loading state, public/images, aset brand/video hover
git add public resources/css resources/views
git commit -m "tampilan: merapikan ui responsive dan memperbarui aset"
git push -u origin tampilan/ui-polish-assets
# buka PR: tampilan/ui-polish-assets -> main
```

### Tahap 14 — Amey: Melengkapi dokumentasi
**Branch:** `dokumentasi/final-readme`

```bash
git checkout main
git pull origin main
git checkout -b dokumentasi/final-readme
# kerjakan: README.md, cara install/run, endpoint API, catatan pembagian tugas
git add README.md
git commit -m "dokumentasi: melengkapi readme dan panduan proyek"
git push -u origin dokumentasi/final-readme
# buka PR: dokumentasi/final-readme -> main
```

## Yang Perlu Dipush Sekarang

Saat dokumen ini dibuat, `main` sudah sinkron dengan `origin/main`. Ada 1 file lokal yang belum masuk Git:

- `package-lock.json`

Jika tim ingin memakai file ini, Amey bisa mempushnya:

```bash
git checkout main
git pull origin main
git checkout -b tugas/add-package-lock
git add package-lock.json
git commit -m "tugas: menambah package-lock agar dependensi npm konsisten"
git push -u origin tugas/add-package-lock
# buka PR: tugas/add-package-lock -> main
```

## Kalau Ada Konflik

Konflik terjadi kalau dua orang mengubah bagian file yang sama. Cara menanganinya:

```bash
git checkout main
git pull origin main
git checkout nama-branch-kamu
git merge main
# jika ada konflik, perbaiki file yang konflik
git add .
git commit -m "perbaikan: menyelesaikan konflik dengan main"
git push origin nama-branch-kamu
```

**Intinya:** ambil update `main` terbaru, gabungkan ke branch kamu, perbaiki konfliknya di komputer kamu, lalu push lagi.