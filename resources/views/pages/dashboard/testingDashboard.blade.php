@extends('layouts.app')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="grid grid-cols-12 gap-4 md:gap-6">

    {{-- ================================================= --}}
    {{-- 1. KARTU PROFIL UTAMA (HERO) --}}
    {{-- ================================================= --}}
    <div class="col-span-12">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 md:p-10 shadow-default dark:border-gray-800 dark:bg-gray-900 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-brand-500/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>

            <div class="flex-shrink-0 relative z-10">
                <img src="https://i.postimg.cc/X7sk0qnB/IMG-3764-1-1.jpg" alt="Profile" class="w-40 h-40 md:w-48 md:h-48 rounded-full object-cover border-4 border-white shadow-lg dark:border-gray-800">
            </div>

            <div class="text-center md:text-left z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-black dark:text-white mb-2">Lucky Dio Candra Purnama</h1>
                <p class="text-brand-500 font-medium mb-4">Programmer | Guru Matematika | E-sports Coach</p>
                <p class="text-gray-500 dark:text-gray-400 text-sm md:text-base leading-relaxed max-w-3xl mb-6">
                    Halo! Saya seorang mahasiswa yang berfokus pada pengembangan perangkat lunak, membagikan pemahaman logika sebagai guru, dan merancang taktik kemenangan di arena kompetitif Mobile Legends.
                </p>

                <div class="flex items-center justify-center md:justify-start gap-4">
                    <a href="https://github.com/Luckyawan7b" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-brand-500 hover:text-white transition dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-brand-500 dark:hover:text-white">
                        <i class="fab fa-github text-lg"></i>
                    </a>
                    <a href="https://www.instagram.com/luckyyy_dcp/" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-pink-500 hover:text-white transition dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-pink-500 dark:hover:text-white">
                        <i class="fab fa-instagram text-lg"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/lucky-purnama" target="_blank" class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-500 hover:text-white transition dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-blue-500 dark:hover:text-white">
                        <i class="fab fa-linkedin-in text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 2. EMPAT PILAR (FORMAT GRID 2x2) --}}
    {{-- ================================================= --}}
    <div class="col-span-12 mt-2">
        <h2 class="text-2xl font-bold text-black dark:text-white mb-5">4 Pilar Fokus Utama</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-500/20 mb-4">
                    <i class="fas fa-code text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">Programmer</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Merancang solusi perangkat lunak (full-stack), mengelola database, dan membangun arsitektur aplikasi (MVC) yang terukur dan rapi.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-500/20 mb-4">
                    <i class="fas fa-square-root-variable text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">Guru Matematika</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Membangun dasar logika dan angka pada siswa dengan pendekatan problem-solving yang sistematis dan mudah diimplementasikan.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-500/20 mb-4">
                    <i class="fas fa-gamepad text-purple-600 dark:text-purple-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">Coach MLBB</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Menganalisis meta permainan, merancang taktik drafting, melakukan evaluasi pasca-pertandingan, dan membangun mentalitas juara.</p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm hover:shadow-md transition dark:border-gray-800 dark:bg-gray-900">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100 dark:bg-emerald-500/20 mb-4">
                    <i class="fas fa-graduation-cap text-emerald-600 dark:text-emerald-400 text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">Mahasiswa</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Terus menimba ilmu, melakukan riset teknis, menyelesaikan proyek akhir, dan beradaptasi dengan teknologi informasi terkini.</p>
            </div>

        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 3. ABOUT ME --}}
    {{-- ================================================= --}}
    <div class="col-span-12 rounded-2xl border border-gray-200 bg-white p-6 md:p-8 shadow-default dark:border-gray-800 dark:bg-gray-900 mt-2">
        <h2 class="text-2xl font-bold text-black dark:text-white mb-4">Tentang Saya</h2>
        <div class="space-y-4 text-gray-600 dark:text-gray-400 text-sm md:text-base leading-relaxed">
            <p>Saya percaya bahwa pola pikir sistematis adalah kunci kesuksesan di berbagai bidang. Sebagai seorang mahasiswa di bidang Teknologi Informasi, saya terbiasa membedah masalah kompleks, menganalisis struktur data, dan membangun aplikasi fungsional sebagai seorang programmer.</p>
            <p>Di luar baris kode, saya menyalurkan kemampuan logika saya dengan mengajar Matematika. Mengurai rumus rumit menjadi konsep yang mudah dicerna adalah tantangan yang selalu saya nikmati.</p>
            <p>Ketajaman analitis ini juga saya bawa ke arena kompetitif. Sebagai Coach E-sports Mobile Legends, kemampuan membaca situasi <em>macro awareness</em>, menyusun <em>drafting</em> hero yang optimal, dan memimpin dinamika tim adalah spesialisasi saya untuk membawa tim menuju kemenangan.</p>
        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 4. PENGALAMAN & PENCAPAIAN (2 BARIS x 3 KOLOM) --}}
    {{-- ================================================= --}}
    <div class="col-span-12 mt-2">
        <h2 class="text-2xl font-bold text-black dark:text-white mb-5">Pengalaman & Pencapaian</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden flex flex-col group hover:-translate-y-1 transition duration-300">
                <div class="h-48 w-full relative overflow-hidden">
                    <img src="https://i.postimg.cc/6QGtV8Mz/Screenshot-2026-04-11-120130.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Web Dev">
                    <span class="absolute top-3 right-3 bg-white/90 dark:bg-black/80 text-brand-500 text-xs font-bold px-3 py-1 rounded-full shadow-sm">💻 Software Dev</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-black dark:text-white mb-2">Smart-Saka</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">Membangun aplikasi full-stack menggunakan arsitektur MVC sesuai dengan kebutuhan mitra.</p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Laravel</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Tailwind</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">PostgreSQL</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden flex flex-col group hover:-translate-y-1 transition duration-300">
                <div class="h-48 w-full relative overflow-hidden">
                    <img src="https://i.postimg.cc/XJd2F99r/IMG_20240305_WA0007.jpg" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Coach">
                    <span class="absolute top-3 right-3 bg-white/90 dark:bg-black/80 text-purple-500 text-xs font-bold px-3 py-1 rounded-full shadow-sm">🎮 E-Sports</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-black dark:text-white mb-2">Coach MLBB SMP</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">Merumuskan strategi makro dan draft pick yang membawa tim meraih kemenangan.</p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Drafting</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Leadership</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden flex flex-col group hover:-translate-y-1 transition duration-300">
                <div class="h-48 w-full relative overflow-hidden">
                    <img src="https://i.postimg.cc/rw9XJb3S/Screenshot-2026-04-11-034056.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Sertifikat">
                    <span class="absolute top-3 right-3 bg-white/90 dark:bg-black/80 text-blue-500 text-xs font-bold px-3 py-1 rounded-full shadow-sm">📜 Sertifikat</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-black dark:text-white mb-2">Web Dev Competency</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">Meraih sertifikasi resmi yang memvalidasi kemampuan dalam merancang aplikasi web.</p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Problem Solving</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Algorithm</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden flex flex-col group hover:-translate-y-1 transition duration-300">
                <div class="h-48 w-full relative overflow-hidden">
                    <img src="https://i.postimg.cc/2ygPSGDm/IMG-20250727-WA0002.jpg" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Mengajar">
                    <span class="absolute top-3 right-3 bg-white/90 dark:bg-black/80 text-emerald-500 text-xs font-bold px-3 py-1 rounded-full shadow-sm">🎓 Pendidikan</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-black dark:text-white mb-2">Peningkatan Siswa</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">Membimbing siswa memahami konsep logika matematika yang berdampak pada nilai akademik.</p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Mentoring</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Komunikasi</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900 overflow-hidden flex flex-col group hover:-translate-y-1 transition duration-300">
                <div class="h-48 w-full relative overflow-hidden">
                    <img src="https://i.postimg.cc/wMQrczKD/Screenshot-2026-04-11-114451.png" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="Game">
                    <span class="absolute top-3 right-3 bg-white/90 dark:bg-black/80 text-brand-500 text-xs font-bold px-3 py-1 rounded-full shadow-sm">🕹️ Game Dev</span>
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h4 class="text-lg font-bold text-black dark:text-white mb-2">MasPur Adventure</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400 flex-1">Projek game RPG petualangan sederhana yang dikembangkan menggunakan RPGMaker.</p>
                    <div class="mt-4 flex gap-2 flex-wrap">
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Ruby</span>
                        <span class="text-xs bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 px-2 py-1 rounded">Storytelling</span>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 hover:bg-gray-100 dark:hover:bg-gray-800 transition duration-300 group flex flex-col justify-center items-center text-center p-8 shadow-sm">
                <div class="w-16 h-16 bg-white dark:bg-gray-900 rounded-full flex items-center justify-center mb-4 shadow-sm group-hover:-translate-y-1 transition duration-300">
                    <i class="fab fa-github text-gray-800 dark:text-gray-200 text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-black dark:text-white mb-2">View More</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Jelajahi repositori GitHub saya untuk melihat seluruh baris kode, project, dan kontribusi lainnya.</p>
                <a href="https://github.com/Luckyawan7b" target="_blank" class="px-6 py-2.5 bg-brand-500 hover:bg-brand-600 text-white text-sm font-medium rounded-lg transition shadow-theme-xs flex items-center gap-2">
                    Kunjungi GitHub <i class="fas fa-arrow-right text-xs"></i>
                </a>
            </div>

        </div>
    </div>

    {{-- ================================================= --}}
    {{-- 5. JEJAK LANGKAH COACH (TIMELINE GAYA TAILADMIN) --}}
    {{-- ================================================= --}}
    <div class="col-span-12 rounded-2xl border border-gray-200 bg-white p-6 md:p-8 shadow-default dark:border-gray-800 dark:bg-gray-900 mt-2">
        <h2 class="text-2xl font-bold text-black dark:text-white mb-8">Jejak Langkah <span class="text-purple-500">Coach MLBB</span></h2>

        <div class="flex flex-col gap-8">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/L4QsKgJM/IMG_20260212_WA0007.jpg" alt="Juara 1" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🏆 Juara 1 Pancasila Youth Challenge 2026</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Februari 2026</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Puncak pencapaian di turnamen tingkat SMP terbesar yang pernah kami ikuti. Pertandingan final ini menjadi ajang balas dendam yang manis dan pembuktian nyata dari adaptasi taktik serta mentalitas juara.</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/P55mh1xw/smasdha.jpg" alt="Juara 1" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🏆 Juara 1 Smasdha Competition</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Januari 2026</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Sebuah comeback gemilang setelah kegagalan pahit. Kami melakukan evaluasi besar-besaran dan intensifikasi porsi latihan. Dedikasi tersebut terbayar lunas dengan mengangkat trofi juara.</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/k5prwNV5/semarak-2025.jpg" alt="Juara 1" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🏆 Juara 1 Semarak Aliyah 2025</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Januari 2025</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Mencetak sejarah baru dengan menjuarai kompetisi ini. Eksekusi yang presisi memampukan kami meruntuhkan dominasi tim unggulan yang sebelumnya memegang gelar juara dua tahun berturut-turut.</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/GtSbD0kq/hut-pancasila.jpg" alt="Juara 1" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🏆 Juara 1 HUT Yayasan Panca Prasetya</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Januari 2025</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Momen pembuktian dari hasil evaluasi setahun penuh. Dengan fokus pada penguatan mental in-game, kami berhasil mengamankan gelar juara untuk pertama kalinya dan menumbangkan sang juara bertahan.</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/28zHbb6K/IMG-20240221-WA0014.jpg" alt="Juara 3" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🥉 Juara 3 SMEA Championship 2024</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Februari 2024</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Menghadapi tantangan berat dengan melawan tim lintas tingkat (SMP vs SMA/SMK). Bertahan hingga posisi ketiga membuktikan bahwa proses pembentukan mentalitas juara tidak terjadi secara instan.</p>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-gray-800">

            <div class="flex flex-col sm:flex-row gap-5 items-start">
                <img src="https://i.postimg.cc/J0F531cK/wahas24.jpg" alt="Juara 2" class="w-full sm:w-48 h-32 object-cover rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm flex-shrink-0">
                <div>
                    <h4 class="text-lg font-bold text-black dark:text-white">🥈 Juara 2 Semarak Aliyah 2024</h4>
                    <p class="text-sm font-medium text-purple-500 mb-2">Jember | Februari 2024</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">Pengalaman pertama memimpin tim di skena kompetitif. Kekalahan di laga puncak akibat tekanan psikologis menyadarkan saya bahwa persiapan mental sama krusialnya dengan mekanik permainan.</p>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
