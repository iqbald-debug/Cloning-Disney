<!DOCTYPE html>
<html lang="id" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disney+ | Streaming Film & Serial</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top, #090b13 0%, #040714 100%);
            color: #f9f9f9;
            overflow-x: hidden;
        }

        /* Sembunyikan scrollbar horizontal pada slider */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Efek Hover Kartu Khas Disney+ */
        .disney-card {
            border: 3px solid rgba(249, 249, 249, 0.1);
            box-shadow: 0 26px 30px -10px rgba(0, 0, 0, 0.69), 0 16px 10px -10px rgba(0, 0, 0, 0.73);
            transition: transform 300ms cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        border-color 300ms cubic-bezier(0.25, 0.46, 0.45, 0.94),
                        box-shadow 300ms cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .disney-card:hover {
            transform: scale(1.05);
            border-color: rgba(249, 249, 249, 0.85);
            box-shadow: 0 40px 58px -16px rgba(0, 0, 0, 0.80), 0 30px 22px -10px rgba(0, 0, 0, 0.72);
            z-index: 20;
        }

        /* Animasi Garis Bawah Menu Navbar */
        .nav-link-underline {
            position: relative;
        }
        .nav-link-underline::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -6px;
            width: 100%;
            height: 2px;
            background-color: #f9f9f9;
            transform: scaleX(0);
            transform-origin: left center;
            transition: transform 250ms cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .nav-link-underline:hover::after,
        .nav-link-active::after {
            transform: scaleX(1);
        }
    </style>
</head>
<body class="min-h-screen antialiased selection:bg-blue-600 selection:text-white" x-data="disneyApp()" x-init="init()">

    <!-- NOTIFIKASI TOAST -->
    <div x-show="toast.show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         x-cloak
         class="fixed bottom-6 right-6 z-[60] bg-[#1e1f2a]/95 backdrop-blur-md border border-white/20 text-white px-5 py-3.5 rounded-lg shadow-2xl flex items-center gap-3">
        <span class="w-2.5 h-2.5 rounded-full bg-blue-400 animate-pulse"></span>
        <span class="text-sm font-semibold" x-text="toast.message"></span>
    </div>

    <!-- ========================================================= -->
    <!-- 1. LAYAR "WHO'S WATCHING?" (PILIH PROFIL)                 -->
    <!-- ========================================================= -->
    <div x-show="!activeProfile" x-transition.opacity class="fixed inset-0 z-50 bg-[#040714] flex flex-col items-center justify-center px-6">
        <!-- Logo Disney+ Kiri Atas -->
        <div class="absolute top-6 left-8">
            <svg class="w-28 md:w-32 h-auto text-white" viewBox="0 0 180 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M24 38C55 12 118 10 152 38" stroke="#38bdf8" stroke-width="4" stroke-linecap="round"/>
                <text x="16" y="72" fill="currentColor" font-family="Plus Jakarta Sans, sans-serif" font-weight="800" font-size="38" letter-spacing="-1">Disney</text>
                <path d="M150 46V68M139 57H161" stroke="#38bdf8" stroke-width="4.5" stroke-linecap="round"/>
            </svg>
        </div>

        <h1 class="text-3xl md:text-4xl font-bold mb-12 text-center tracking-tight">Who's watching?</h1>

        <!-- Loading State Profil -->
        <div x-show="loadingProfiles" class="flex gap-8">
            <div class="w-28 h-28 md:w-36 md:h-36 rounded-full bg-white/10 animate-pulse"></div>
            <div class="w-28 h-28 md:w-36 md:h-36 rounded-full bg-white/10 animate-pulse"></div>
            <div class="w-28 h-28 md:w-36 md:h-36 rounded-full bg-white/10 animate-pulse"></div>
        </div>

        <!-- Daftar Avatar Profil -->
        <div x-show="!loadingProfiles" class="flex flex-wrap justify-center gap-8 md:gap-10">
            <template x-for="profile in profiles" :key="profile.id">
                <button x-on:click="selectProfile(profile)" class="group flex flex-col items-center focus:outline-none">
                    <div class="w-28 h-28 md:w-36 md:h-36 rounded-full overflow-hidden border-4 border-transparent group-hover:border-white group-hover:scale-105 transition-all duration-300 shadow-[0_20px_35px_rgba(0,0,0,0.8)] bg-gradient-to-b from-[#30323e] to-[#1e1f2a]">
                        <img x-bind:src="profile.avatar_url || 'https://api.dicebear.com/9.x/adventurer/svg?seed=' + encodeURIComponent(profile.name)"
                             x-bind:alt="profile.name" class="w-full h-full object-cover">
                    </div>
                    <span class="mt-4 text-lg font-semibold text-[#cacaca] group-hover:text-white transition" x-text="profile.name"></span>
                    <span x-show="profile.is_kids" class="mt-1 text-[10px] uppercase tracking-widest px-2.5 py-0.5 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 font-bold">Kids</span>
                </button>
            </template>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- 2. APLIKASI UTAMA (SETELAH PROFIL DIPILIH)                -->
    <!-- ========================================================= -->
    <div x-show="activeProfile" x-cloak>

        <!-- STICKY TOP NAVBAR -->
        <nav x-on:scroll.window="scrolled = (window.pageYOffset > 20)"
             x-bind:class="scrolled ? 'bg-[#040714] shadow-[0_10px_30px_rgba(0,0,0,0.8)]' : 'bg-gradient-to-b from-[#040714]/95 via-[#040714]/60 to-transparent'"
             class="fixed top-0 inset-x-0 z-30 h-[72px] px-5 md:px-10 flex items-center justify-between transition-colors duration-300">

            <div class="flex items-center gap-8 lg:gap-10">
                <!-- Logo Disney+ -->
                <button x-on:click="switchTab('home')" class="focus:outline-none flex items-center">
                    <svg class="w-24 md:w-28 h-auto text-white" viewBox="0 0 180 96" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M24 38C55 12 118 10 152 38" stroke="#38bdf8" stroke-width="4" stroke-linecap="round"/>
                        <text x="16" y="72" fill="currentColor" font-family="Plus Jakarta Sans, sans-serif" font-weight="800" font-size="38" letter-spacing="-1">Disney</text>
                        <path d="M150 46V68M139 57H161" stroke="#38bdf8" stroke-width="4.5" stroke-linecap="round"/>
                    </svg>
                </button>

                <!-- Menu Navigasi Desktop -->
                <div class="hidden md:flex items-center gap-7 text-xs font-bold tracking-[1.42px] uppercase">
                    <button x-on:click="switchTab('home')"
                            x-bind:class="currentTab === 'home' ? 'nav-link-active text-white' : 'text-[#f9f9f9]/80 hover:text-white'"
                            class="nav-link-underline py-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        <span>HOME</span>
                    </button>

                    <button x-on:click="switchTab('search')"
                            x-bind:class="currentTab === 'search' ? 'nav-link-active text-white' : 'text-[#f9f9f9]/80 hover:text-white'"
                            class="nav-link-underline py-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        <span>SEARCH</span>
                    </button>

                    <button x-on:click="switchTab('watchlist')"
                            x-bind:class="currentTab === 'watchlist' ? 'nav-link-active text-white' : 'text-[#f9f9f9]/80 hover:text-white'"
                            class="nav-link-underline py-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                        <span>WATCHLIST</span>
                    </button>

                    <button x-on:click="filterByType('movie')"
                            x-bind:class="currentTab === 'movie' ? 'nav-link-active text-white' : 'text-[#f9f9f9]/80 hover:text-white'"
                            class="nav-link-underline py-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 2h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm2 0h1V9h-1v2zm1-4V5h-1v2h1zM5 5v2H4V5h1zm0 4H4v2h1V9zm-1 4h1v2H4v-2z" clip-rule="evenodd"/></svg>
                        <span>MOVIES</span>
                    </button>

                    <button x-on:click="filterByType('series')"
                            x-bind:class="currentTab === 'series' ? 'nav-link-active text-white' : 'text-[#f9f9f9]/80 hover:text-white'"
                            class="nav-link-underline py-1 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm4 11h8v1H6v-1z"/></svg>
                        <span>SERIES</span>
                    </button>
                </div>
            </div>

            <!-- Dropdown Profil Kanan Atas -->
            <div class="relative" x-data="{ openMenu: false }" x-on:mouseenter="openMenu = true" x-on:mouseleave="openMenu = false">
                <button x-on:click="openMenu = !openMenu" class="flex items-center gap-3 focus:outline-none py-2">
                    <span class="hidden sm:inline text-sm font-medium text-[#cacaca]" x-text="activeProfile?.name"></span>
                    <img x-bind:src="activeProfile?.avatar_url || 'https://api.dicebear.com/9.x/adventurer/svg?seed=' + encodeURIComponent(activeProfile?.name || 'User')"
                         class="w-11 h-11 rounded-full object-cover border-2 border-white/40 hover:border-white transition bg-[#1e1f2a]">
                </button>

                <div x-show="openMenu"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-cloak
                     class="absolute right-0 mt-1 w-56 bg-[#13151d] border border-white/15 rounded-lg shadow-2xl py-2 z-50">
                    <div class="px-4 py-2 text-[11px] uppercase tracking-wider text-[#cacaca] border-b border-white/10">Ganti Profil</div>
                    <template x-for="p in profiles" :key="p.id">
                        <button x-on:click="selectProfile(p); openMenu = false"
                                class="w-full px-4 py-2.5 text-left text-sm hover:bg-white/10 flex items-center justify-between transition">
                            <div class="flex items-center gap-3">
                                <img x-bind:src="p.avatar_url" class="w-8 h-8 rounded-full object-cover bg-[#1e1f2a]">
                                <span x-text="p.name" x-bind:class="p.id === activeProfile?.id ? 'font-bold text-white' : 'text-[#cacaca]'"></span>
                            </div>
                            <span x-show="p.id === activeProfile?.id" class="w-2 h-2 rounded-full bg-blue-400"></span>
                        </button>
                    </template>
                    <div class="border-t border-white/10 mt-1 pt-1">
                        <button x-on:click="activeProfile = null; openMenu = false"
                                class="w-full px-4 py-2.5 text-left text-xs font-semibold text-[#cacaca] hover:text-white hover:bg-white/10 transition">
                            Layar Who's Watching?
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- ===================================================== -->
        <!-- 3. HALAMAN HOME (HERO, 5 BRAND, CONTENT SLIDERS)      -->
        <!-- ===================================================== -->
        <main x-show="currentTab === 'home'" class="pb-24">

            <!-- HERO BANNER CAROUSEL -->
            <section class="relative w-full h-[68vh] md:h-[80vh] overflow-hidden group">
                <template x-for="(banner, index) in homeData.featured_banners" :key="banner.id">
                    <div x-show="currentSlide === index"
                         x-transition:enter="transition ease-out duration-700"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-500"
                         x-transition:leave-start="opacity-100"
                         x-transition:leave-end="opacity-0"
                         class="absolute inset-0">
                        <img x-bind:src="banner.backdrop_url" x-bind:alt="banner.title" class="w-full h-full object-cover object-center">

                        <!-- Gradasi Vignette Khas Disney+ -->
                        <div class="absolute inset-0 bg-gradient-to-r from-[#040714] via-[#040714]/55 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-transparent to-[#040714]/40"></div>

                        <!-- Info Banner -->
                        <div class="absolute bottom-14 md:bottom-20 left-6 md:left-14 max-w-xl z-10">
                            <div class="flex items-center gap-2.5 mb-2.5">
                                <span class="text-xs font-extrabold uppercase tracking-[2px] text-blue-400" x-text="banner.brand?.name || 'Disney+'"></span>
                                <span class="text-white/40">•</span>
                                <span class="text-xs font-semibold text-[#cacaca]" x-text="banner.release_year + ' • ' + banner.duration_label"></span>
                            </div>

                            <h2 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-3.5 drop-shadow-[0_4px_12px_rgba(0,0,0,0.8)]" x-text="banner.title"></h2>

                            <p class="text-sm md:text-base text-[#f9f9f9]/85 line-clamp-3 leading-relaxed mb-7" x-text="banner.synopsis"></p>

                            <div class="flex flex-wrap items-center gap-4">
                                <button x-on:click="openDetail(banner.slug)"
                                        class="bg-[#f9f9f9] text-[#040714] hover:bg-white/80 font-bold text-xs md:text-sm tracking-[1.5px] uppercase px-7 py-3.5 rounded flex items-center gap-2.5 shadow-lg transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                    <span>WATCH NOW</span>
                                </button>

                                <button x-on:click="toggleWatchlist(banner.id)"
                                        x-bind:class="isInWatchlist(banner.id) ? 'bg-blue-600/80 border-blue-400' : 'bg-black/50 border-white hover:bg-white hover:text-black'"
                                        class="w-12 h-12 rounded-full border-2 text-white flex items-center justify-center text-xl font-bold transition"
                                        title="Tambah / Hapus Watchlist">
                                    <span x-text="isInWatchlist(banner.id) ? '✓' : '+'"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Panah Kiri/Kanan Hero Carousel -->
                <button x-show="homeData.featured_banners.length > 1"
                        x-on:click="prevSlide()"
                        class="opacity-0 group-hover:opacity-100 absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-20 flex items-center justify-center text-white/70 hover:text-white transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button x-show="homeData.featured_banners.length > 1"
                        x-on:click="nextSlide()"
                        class="opacity-0 group-hover:opacity-100 absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-20 flex items-center justify-center text-white/70 hover:text-white transition">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>

                <!-- Pagination Dots Pojok Kanan Bawah -->
                <div class="absolute bottom-6 right-8 md:right-14 flex items-center gap-2.5 z-20">
                    <template x-for="(banner, idx) in homeData.featured_banners" :key="idx">
                        <button x-on:click="currentSlide = idx"
                                x-bind:class="currentSlide === idx ? 'bg-white w-6' : 'bg-white/40 hover:bg-white/70 w-2'"
                                class="h-2 rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </section>

            <!-- 5 KARTU BRAND / FRANCHISE (DISNEY, PIXAR, MARVEL, STAR WARS, NATGEO) -->
            <section class="px-6 md:px-14 mt-2 mb-12">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 md:gap-6">
                    <template x-for="brand in homeData.brands" :key="brand.id">
                        <button x-on:click="filterByBrand(brand)"
                                x-on:mouseenter="const v = $el.querySelector('video'); if (v) { v.muted = true; v.play().catch(() => {}); }"
                                class="disney-card group relative aspect-video rounded-xl overflow-hidden bg-gradient-to-b from-[#30323e] to-[#1e1f2a] flex items-center justify-center p-5 focus:outline-none">

                            <!-- Efek Cahaya Warna Khas Studio saat Hover -->
                            <div x-bind:class="{
                                    'from-blue-600/50 via-indigo-900/40 to-[#090b13]': brand.slug === 'disney',
                                    'from-cyan-500/50 via-sky-900/40 to-[#090b13]': brand.slug === 'pixar',
                                    'from-red-600/60 via-rose-950/50 to-[#090b13]': brand.slug === 'marvel',
                                    'from-slate-400/40 via-blue-950/60 to-[#090b13]': brand.slug === 'star-wars',
                                    'from-amber-500/50 via-yellow-900/40 to-[#090b13]': brand.slug === 'national-geographic'
                                 }"
                                 class="absolute inset-0 z-0 bg-gradient-to-br opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                            <!-- Logo Brand Putih Bersih (z-20) -->
                            <img x-bind:src="brand.logo_url" x-bind:alt="brand.name"
                                 class="relative z-20 w-3/4 max-h-14 md:max-h-16 object-contain brightness-0 invert pointer-events-none drop-shadow-[0_4px_12px_rgba(0,0,0,0.85)]">

                            <!-- Video Animasi Latar Belakang saat Hover (z-10) -->
                            <video x-show="brand.hover_video_url"
                                   x-bind:src="brand.hover_video_url"
                                   x-bind:class="{
                                       'hue-rotate-180': brand.slug === 'disney',
                                       'hue-rotate-150 brightness-110': brand.slug === 'pixar',
                                       'hue-rotate-[320deg] saturate-200': brand.slug === 'marvel',
                                       'grayscale contrast-125': brand.slug === 'star-wars',
                                       'sepia saturate-150': brand.slug === 'national-geographic'
                                   }"
                                   autoplay loop muted playsinline preload="auto"
                                   class="absolute inset-0 z-10 w-full h-full object-cover opacity-0 group-hover:opacity-80 mix-blend-screen transition-opacity duration-300"></video>
                        </button>
                    </template>
                </div>
            </section>

            <!-- BARIS SLIDER KONTEN HORIZONTAL PER KATEGORI -->
            <section class="space-y-10">
                <template x-for="(items, rowTitle) in homeData.rows" :key="rowTitle">
                    <div class="relative group/row" x-data="{ scrollRow(dir) { $refs.track.scrollBy({ left: dir * 650, behavior: 'smooth' }); } }">
                        <h3 class="px-6 md:px-14 text-lg md:text-xl font-bold tracking-wide text-[#f9f9f9] mb-3.5" x-text="rowTitle"></h3>

                        <!-- Tombol Navigasi Slider Kiri -->
                        <button x-on:click="scrollRow(-1)"
                                class="hidden md:flex opacity-0 group-hover/row:opacity-100 absolute left-0 top-10 bottom-2 w-12 z-20 bg-gradient-to-r from-[#040714]/90 to-transparent items-center justify-center text-white hover:scale-110 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        </button>

                        <!-- Track Horizontal 16:9 Cards -->
                        <div x-ref="track" class="flex gap-5 overflow-x-auto no-scrollbar px-6 md:px-14 py-3 scroll-smooth">
                            <template x-for="item in items" :key="item.id">
                                <div x-on:click="openDetail(item.slug)"
                                     class="disney-card cursor-pointer flex-none w-[240px] sm:w-[270px] md:w-[300px] aspect-video rounded-lg overflow-hidden bg-[#1e1f2a] relative group">
                                    <img x-bind:src="item.backdrop_url" x-bind:alt="item.title" loading="lazy" class="w-full h-full object-cover">

                                    <!-- Overlay Judul & Metadata -->
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-[#040714]/20 to-transparent opacity-85 group-hover:opacity-100 transition"></div>

                                    <div class="absolute bottom-3 left-3.5 right-3.5 flex items-end justify-between gap-2">
                                        <div class="min-w-0">
                                            <p class="font-bold text-sm md:text-base text-white truncate" x-text="item.title"></p>
                                            <p class="text-xs text-[#cacaca] mt-0.5" x-text="item.release_year + ' • ' + item.duration_label"></p>
                                        </div>
                                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded bg-white/15 text-white shrink-0" x-text="item.age_rating"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Tombol Navigasi Slider Kanan -->
                        <button x-on:click="scrollRow(1)"
                                class="hidden md:flex opacity-0 group-hover/row:opacity-100 absolute right-0 top-10 bottom-2 w-12 z-20 bg-gradient-to-l from-[#040714]/90 to-transparent items-center justify-center text-white hover:scale-110 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </template>
            </section>
        </main>

        <!-- ===================================================== -->
        <!-- 4. HALAMAN LIVE SEARCH & FILTER (BRAND/MOVIES/SERIES) -->
        <!-- ===================================================== -->
        <main x-show="currentTab === 'search' || currentTab === 'movie' || currentTab === 'series' || currentTab === 'brand'"
              class="pt-28 px-6 md:px-14 pb-24 min-h-screen">

            <!-- Input Pencarian Besar Khas Disney+ -->
            <div x-show="currentTab === 'search'" class="mb-10 relative">
                <input type="text"
                       x-model="searchQuery"
                       x-on:input.debounce.350ms="fetchFilteredContents()"
                       placeholder="Title, character, or genre"
                       class="w-full bg-[#4b4e5a] focus:bg-[#64687a] text-white placeholder-[#cacaca] text-xl md:text-2xl font-semibold px-6 py-5 rounded-lg shadow-xl focus:outline-none transition">
                <button x-show="searchQuery.length > 0"
                        x-on:click="searchQuery = ''; fetchFilteredContents()"
                        class="absolute right-5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-white/15 hover:bg-white/30 text-white flex items-center justify-center font-bold transition">
                    ✕
                </button>
            </div>

            <!-- Header Kategori / Hasil -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <h2 class="text-2xl md:text-3xl font-bold" x-text="filterHeading"></h2>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-white/10 text-[#cacaca]" x-text="filteredContents.length + ' Judul'"></span>
                </div>
                <button x-show="currentTab !== 'search'" x-on:click="switchTab('home')" class="text-sm font-semibold text-blue-400 hover:text-blue-300 transition">
                    ← Kembali ke Home
                </button>
            </div>

            <!-- Grid Kartu 16:9 -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template x-for="item in filteredContents" :key="item.id">
                    <div x-on:click="openDetail(item.slug)"
                         class="disney-card cursor-pointer relative aspect-video rounded-lg overflow-hidden bg-[#1e1f2a] group">
                        <img x-bind:src="item.backdrop_url" x-bind:alt="item.title" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3.5 right-3.5">
                            <p class="font-bold text-base text-white truncate" x-text="item.title"></p>
                            <p class="text-xs text-[#cacaca] mt-0.5" x-text="(item.brand?.name || '') + ' • ' + item.release_year + ' • ' + item.duration_label"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="filteredContents.length === 0" class="text-center py-20">
                <p class="text-lg text-[#cacaca]">Tidak ada film atau serial yang cocok dengan pencarianmu.</p>
            </div>
        </main>

        <!-- ===================================================== -->
        <!-- 5. HALAMAN MY WATCHLIST                               -->
        <!-- ===================================================== -->
        <main x-show="currentTab === 'watchlist'" class="pt-28 px-6 md:px-14 pb-24 min-h-screen">
            <h2 class="text-2xl md:text-3xl font-bold mb-1">My Watchlist</h2>
            <p class="text-sm text-[#cacaca] mb-8">Daftar tontonan tersimpan untuk profil <span class="text-white font-semibold" x-text="activeProfile?.name"></span></p>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template x-for="item in watchlistContents" :key="item.id">
                    <div class="disney-card relative aspect-video rounded-lg overflow-hidden bg-[#1e1f2a] group">
                        <img x-on:click="openDetail(item.slug)" x-bind:src="item.backdrop_url" class="w-full h-full object-cover cursor-pointer">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-transparent to-transparent pointer-events-none"></div>

                        <button x-on:click.stop="toggleWatchlist(item.id)"
                                class="absolute top-2.5 right-2.5 bg-black/75 hover:bg-red-600 text-white text-xs font-semibold px-3 py-1 rounded-md border border-white/20 transition">
                            Hapus
                        </button>

                        <div x-on:click="openDetail(item.slug)" class="absolute bottom-3 left-3.5 right-3.5 cursor-pointer">
                            <p class="font-bold text-base text-white truncate" x-text="item.title"></p>
                            <p class="text-xs text-[#cacaca] mt-0.5" x-text="item.release_year + ' • ' + item.duration_label"></p>
                        </div>
                    </div>
                </template>
            </div>

            <div x-show="watchlistContents.length === 0" class="text-center py-20 border border-dashed border-white/15 rounded-xl">
                <p class="text-lg font-semibold text-white mb-1">Watchlist kamu masih kosong</p>
                <p class="text-sm text-[#cacaca]">Klik tombol "+" pada film atau serial favoritmu untuk menyimpannya di sini.</p>
            </div>
        </main>

        <!-- ===================================================== -->
        <!-- 6. HALAMAN DETAIL FILM/SERIAL & VIDEO PLAYER          -->
        <!-- ===================================================== -->
        <div x-show="selectedContent"
             x-transition.opacity.duration.300ms
             x-cloak
             class="fixed inset-0 z-40 bg-[#040714] overflow-y-auto">
            <template x-if="selectedContent">
                <div class="relative min-h-screen">
                    <!-- Backdrop Layar Penuh -->
                    <div class="fixed inset-0 z-0">
                        <img x-bind:src="selectedContent.detail.backdrop_url" class="w-full h-full object-cover opacity-55">
                        <div class="absolute inset-0 bg-gradient-to-r from-[#040714] via-[#040714]/85 to-transparent"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-[#040714]/45 to-transparent"></div>
                    </div>

                    <!-- Tombol Kembali / Tutup -->
                    <button x-on:click="selectedContent = null; isPlaying = false"
                            class="fixed top-6 right-6 md:right-10 z-30 w-11 h-11 rounded-full bg-black/60 border border-white/30 hover:bg-white hover:text-black text-white flex items-center justify-center text-lg font-bold transition">
                        ✕
                    </button>

                    <!-- Konten Utama Detail -->
                    <div class="relative z-10 pt-24 pb-20 px-6 md:px-16 max-w-6xl">
                        <span class="inline-block text-xs font-extrabold uppercase tracking-[2px] text-blue-400 mb-2"
                              x-text="selectedContent.detail.brand?.name"></span>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold tracking-tight mb-4"
                            x-text="selectedContent.detail.title"></h1>

                        <!-- Baris Metadata Polimorfik -->
                        <div class="flex flex-wrap items-center gap-2.5 text-xs md:text-sm text-[#cacaca] font-medium mb-7">
                            <span x-text="selectedContent.detail.release_year"></span>
                            <span>•</span>
                            <span class="text-white font-bold" x-text="selectedContent.detail.duration_label"></span>
                            <span class="px-2 py-0.5 bg-white/15 rounded text-white font-bold text-xs" x-text="selectedContent.detail.age_rating"></span>
                            <span class="px-2.5 py-0.5 border border-white/30 rounded text-xs text-white/90" x-text="selectedContent.detail.video_quality"></span>
                        </div>

                        <!-- Baris Tombol Aksi -->
                        <div class="flex flex-wrap items-center gap-4 mb-8">
                            <button x-on:click="isPlaying = true"
                                    class="bg-[#f9f9f9] text-[#040714] hover:bg-white/80 font-bold text-sm tracking-[1.5px] uppercase px-8 py-3.5 rounded flex items-center gap-2.5 shadow-lg transition">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                <span>PLAY</span>
                            </button>

                            <button x-on:click="isPlaying = true"
                                    class="bg-black/40 border border-white text-white hover:bg-white/20 font-bold text-sm tracking-[1.5px] uppercase px-7 py-3.5 rounded transition">
                                TRAILER
                            </button>

                            <button x-on:click="toggleWatchlist(selectedContent.detail.id)"
                                    x-bind:class="isInWatchlist(selectedContent.detail.id) ? 'bg-blue-600 border-blue-400' : 'bg-black/50 border-white hover:bg-white hover:text-black'"
                                    class="w-12 h-12 rounded-full border-2 text-white flex items-center justify-center text-xl font-bold transition"
                                    title="Tambah / Hapus Watchlist">
                                <span x-text="isInWatchlist(selectedContent.detail.id) ? '✓' : '+'"></span>
                            </button>
                        </div>

                        <!-- Sinopsis -->
                        <p class="text-base md:text-lg text-[#f9f9f9]/90 leading-relaxed max-w-2xl mb-12"
                           x-text="selectedContent.detail.synopsis"></p>

                        <!-- TAB INTERAKTIF: SUGGESTED & DETAILS -->
                        <div>
                            <div class="flex items-center gap-8 border-b border-white/15 mb-6">
                                <button x-on:click="detailTab = 'suggested'"
                                        x-bind:class="detailTab === 'suggested' ? 'border-b-2 border-white text-white' : 'text-[#cacaca] hover:text-white'"
                                        class="pb-3 text-sm font-bold tracking-[1.5px] uppercase transition">
                                    SUGGESTED
                                </button>
                                <button x-on:click="detailTab = 'details'"
                                        x-bind:class="detailTab === 'details' ? 'border-b-2 border-white text-white' : 'text-[#cacaca] hover:text-white'"
                                        class="pb-3 text-sm font-bold tracking-[1.5px] uppercase transition">
                                    DETAILS
                                </button>
                            </div>

                            <!-- Isi Tab SUGGESTED -->
                            <div x-show="detailTab === 'suggested'" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                                <template x-for="sug in selectedContent.suggested" :key="sug.id">
                                    <div x-on:click="openDetail(sug.slug)"
                                         class="disney-card cursor-pointer relative aspect-video rounded-lg overflow-hidden bg-[#1e1f2a]">
                                        <img x-bind:src="sug.backdrop_url" class="w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#040714] via-transparent to-transparent"></div>
                                        <div class="absolute bottom-2.5 left-3 right-3">
                                            <p class="text-xs font-bold text-white truncate" x-text="sug.title"></p>
                                            <p class="text-[11px] text-[#cacaca]" x-text="sug.duration_label"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Isi Tab DETAILS (Output Polimorfik PBO) -->
                            <div x-show="detailTab === 'details'" class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-3xl text-sm">
                                <div>
                                    <h4 class="text-lg font-bold text-white mb-2" x-text="selectedContent.detail.title"></h4>
                                    <p class="text-[#cacaca] leading-relaxed" x-text="selectedContent.detail.synopsis"></p>
                                </div>
                                <div class="space-y-3 text-[#cacaca]">
                                    <div><span class="text-white/60 block text-xs uppercase">Studio / Brand</span><span class="text-white font-semibold" x-text="selectedContent.detail.brand?.name"></span></div>
                                    <div><span class="text-white/60 block text-xs uppercase">Tipe & Durasi</span><span class="text-white font-semibold uppercase" x-text="selectedContent.detail.type + ' • ' + selectedContent.detail.duration_label"></span></div>
                                    <div><span class="text-white/60 block text-xs uppercase">Tahun Rilis & Rating</span><span class="text-white font-semibold" x-text="selectedContent.detail.release_year + ' • ' + selectedContent.detail.age_rating"></span></div>
                                    <div><span class="text-white/60 block text-xs uppercase">Format Kualitas</span><span class="text-white font-semibold" x-text="selectedContent.detail.video_quality"></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- OVERLAY PEMUTAR VIDEO LAYAR PENUH -->
                    <div x-show="isPlaying" x-transition.opacity class="fixed inset-0 z-50 bg-black flex flex-col">
                        <div class="p-5 px-8 flex items-center justify-between bg-gradient-to-b from-black/95 to-transparent">
                            <button x-on:click="isPlaying = false" class="text-white hover:text-blue-400 font-bold text-sm flex items-center gap-2 transition">
                                ← Kembali ke Detail
                            </button>
                            <span class="font-bold text-sm text-[#cacaca]" x-text="selectedContent.detail.title"></span>
                        </div>

                        <div class="flex-1 flex items-center justify-center p-4 md:p-10">
                            <template x-if="getEmbedUrl(selectedContent.detail.trailer_url)">
                                <iframe class="w-full max-w-5xl aspect-video rounded-xl shadow-2xl border border-white/10"
                                        x-bind:src="getEmbedUrl(selectedContent.detail.trailer_url)"
                                        frameborder="0"
                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                        allowfullscreen></iframe>
                            </template>
                            <template x-if="!getEmbedUrl(selectedContent.detail.trailer_url)">
                                <video controls autoplay class="w-full max-w-5xl aspect-video rounded-xl bg-black border border-white/10">
                                    <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                                </video>
                            </template>
                        </div>
                    </div>
                </div>
            </template>
        </div>

    </div>

    <script>
        function disneyApp() {
            return {
                scrolled: false,
                loadingProfiles: true,
                profiles: [],
                activeProfile: null,
                currentTab: 'home',
                detailTab: 'suggested',
                currentSlide: 0,
                homeData: { featured_banners: [], brands: [], rows: {} },
                searchQuery: '',
                filterHeading: 'Explore',
                filteredContents: [],
                watchlistContents: [],
                watchlistIds: [],
                selectedContent: null,
                isPlaying: false,
                toast: { show: false, message: '' },

                async init() {
                    await this.fetchProfiles();
                    const savedProfileId = localStorage.getItem('disney_active_profile_id');
                    if (savedProfileId && this.profiles.length > 0) {
                        const found = this.profiles.find(p => p.id == savedProfileId);
                        if (found) {
                            this.activeProfile = found;
                            await this.fetchWatchlist();
                        }
                    }
                    await this.fetchHome();

                    // Auto-slide Hero Banner setiap 5.5 detik
                    setInterval(() => {
                        if (this.homeData.featured_banners.length > 1 && this.currentTab === 'home' && !this.selectedContent) {
                            this.nextSlide();
                        }
                    }, 5500);
                },

                nextSlide() {
                    const total = this.homeData.featured_banners.length;
                    if (total > 0) this.currentSlide = (this.currentSlide + 1) % total;
                },

                prevSlide() {
                    const total = this.homeData.featured_banners.length;
                    if (total > 0) this.currentSlide = (this.currentSlide - 1 + total) % total;
                },

                showToast(msg) {
                    this.toast.message = msg;
                    this.toast.show = true;
                    setTimeout(() => { this.toast.show = false; }, 3000);
                },

                isInWatchlist(contentId) {
                    return this.watchlistIds.includes(contentId);
                },

                getEmbedUrl(url) {
                    if (!url) return null;
                    const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/);
                    if (match && match[1]) {
                        return `https://www.youtube.com/embed/${match[1]}?autoplay=1&rel=0`;
                    }
                    return null;
                },

                async fetchProfiles() {
                    this.loadingProfiles = true;
                    try {
                        const res = await fetch('/api/v1/profiles', { headers: { 'Accept': 'application/json' } });
                        const json = await res.json();
                        this.profiles = json.data || [];
                    } finally {
                        this.loadingProfiles = false;
                    }
                },

                async selectProfile(profile) {
                    this.activeProfile = profile;
                    localStorage.setItem('disney_active_profile_id', profile.id);
                    await this.fetchWatchlist();
                },

                async fetchHome() {
                    const res = await fetch('/api/v1/home', { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    if (json.data) this.homeData = json.data;
                },

                async switchTab(tab) {
                    this.currentTab = tab;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    if (tab === 'search') {
                        this.filterHeading = 'Explore';
                        await this.fetchFilteredContents();
                    } else if (tab === 'watchlist') {
                        await this.fetchWatchlist();
                    }
                },

                async filterByType(type) {
                    this.currentTab = type;
                    this.filterHeading = type === 'movie' ? 'Movies' : 'Series';
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    const res = await fetch(`/api/v1/contents?type=${type}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.filteredContents = json.data || [];
                },

                async filterByBrand(brand) {
                    this.currentTab = 'brand';
                    this.filterHeading = brand.name;
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    const res = await fetch(`/api/v1/contents?brand=${brand.slug}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.filteredContents = json.data || [];
                },

                async fetchFilteredContents() {
                    const q = encodeURIComponent(this.searchQuery);
                    const res = await fetch(`/api/v1/contents?search=${q}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.filteredContents = json.data || [];
                },

                async openDetail(slug) {
                    const res = await fetch(`/api/v1/contents/${slug}`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    if (json.data) {
                        this.selectedContent = json.data;
                        this.detailTab = 'suggested';
                        this.isPlaying = false;
                    }
                },

                async fetchWatchlist() {
                    if (!this.activeProfile) return;
                    const res = await fetch(`/api/v1/profiles/${this.activeProfile.id}/watchlist`, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    this.watchlistContents = json.data || [];
                    this.watchlistIds = this.watchlistContents.map(item => item.id);
                },

                async toggleWatchlist(contentId) {
                    if (!this.activeProfile) return;
                    const res = await fetch(`/api/v1/profiles/${this.activeProfile.id}/watchlist/toggle`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ content_id: contentId })
                    });
                    const json = await res.json();
                    this.showToast(json.message || 'Watchlist diperbarui');
                    await this.fetchWatchlist();
                }
            }
        }
    </script>
</body>
</html>