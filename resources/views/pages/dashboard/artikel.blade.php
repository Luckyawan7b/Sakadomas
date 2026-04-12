@extends('layouts.app')

@section('content')
    <div class="grid grid-cols-12 gap-4 md:gap-6">
        <h1 class="mb-5 text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-7">Dashboard</h1>

        <div class="col-span-12">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-md dark:border-gray-800 dark:bg-gray-900">

                <!-- Header -->
                <div class="flex items-center gap-4">
                    {{-- <img src="https://i.postimg.cc/FzRY2Wdz/Whats-App-Image-2026-04-08-at-22-34-36.jpg" --}}
                    <img src="./images/hohi.jpeg"
                        class="w-20 h-20 rounded-xl object-cover border-2 border-gray-300 dark:border-gray-700"
                        alt="Hoshi">

                    <div>
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                            Mas Hoshi 🎮
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            (HoshiController)
                        </p>
                    </div>
                </div>

                <!-- Badge -->
                <div class="mt-4 flex flex-wrap gap-2">

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 whitespace-nowrap">
                        MVP TIM 🚀
                    </span>

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 whitespace-nowrap">
                        Retri Goat 🎯
                    </span>

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 whitespace-nowrap">
                        EX-BEBAN 😌
                    </span>

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300 whitespace-nowrap">
                        Improve 🙏
                    </span>

                    <span
                        class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300 whitespace-nowrap">
                        Sun Dancok 🥀
                    </span>

                </div>

                <!-- Content -->
                <div class="mt-6 space-y-4 text-gray-700 dark:text-gray-300">

                    <p>
                        Hari ini kita memberikan apresiasi kepada <b>Mas Hoshi</b> atas performa luar biasa dalam permainan
                        Mobile Legends.
                    </p>

                    <p>
                        Dari yang dulu sering membuat tim “belajar sabar”, kini justru menjadi sosok yang menggendong tim
                        menuju kemenangan.
                    </p>

                    <p>
                        Transformasi ini adalah bukti bahwa perkembangan itu nyata—bahkan dari <i>beban tim</i> bisa naik
                        rank menjadi <b>MVP tim</b>.
                    </p>

                    <p class="font-semibold text-gray-900 dark:text-white">
                        Tetap konsisten ya… karena kami belum siap kalau kamu balik ke versi lama 😏
                    </p>
                    {{-- <img src="https://i.postimg.cc/9F7SGW0R/Whats-App-Image-2026-04-08-at-17-25-30.jpg"> --}}
                    <img src="./images/history.jpeg">
                </div>

                <!-- Before vs After -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                        Perjalanan Karir 🎯
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <!-- BEFORE -->
                        <div class="group">

                            <!-- Judul (BEFORE di luar gambar) -->
                            <p
                                class="text-sm font-semibold text-gray-800 dark:text-white mb-2 border-l-4 border-red-500 pl-2">
                                BEFORE 😵
                            </p>

                            <!-- Gambar -->
                            <div
                                class="relative w-full h-40 md:h-32 overflow-hidden rounded-xl border border-gray-300 dark:border-gray-700 bg-gray-100 dark:bg-gray-800">
                                {{-- <img src="https://i.postimg.cc/dth82sFL/Whats_App_Image_2026_04_08_at_17_30_23.jpg" --}}
                                <img src="./images/valen.jpeg" class="w-full h-full object-contain md:object-cover">
                            </div>

                            <!-- Deskripsi (dibesarkan) -->
                            <p class="text-sm md:text-base text-center mt-3 font-medium text-gray-700 dark:text-gray-300">
                                Masa sulit (valen bawok)
                            </p>

                        </div>

                        <!-- Apresiasi Moonton -->

                        <!-- AFTER -->
                        <div class="group">

                            <!-- Judul (AFTER di luar gambar) -->
                            <p
                                class="text-sm font-semibold text-gray-800 dark:text-white mb-2 border-l-4 border-green-500 pl-2">
                                AFTER 🔥
                            </p>

                            <!-- Gambar -->
                            <div
                                class="w-full h-40 md:h-32 overflow-hidden rounded-xl border border-gray-300 dark:border-gray-700">
                                {{-- <img src="https://i.postimg.cc/W3pZhjSV/112131.jpg" class="w-full h-full object-cover"> --}}
                                <img src="./images/joy.jpeg" class="w-full h-full object-contain md:object-cover">
                            </div>

                            <!-- Deskripsi (dibesarkan) -->
                            <p class="text-sm md:text-base text-center mt-3 font-medium text-gray-700 dark:text-gray-300">
                                Mode ON aktif dinyalakan 😂
                            </p>

                        </div>

                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">
                            Apresiasi Resmi 🏆
                        </h3>

                        <div
                            class="rounded-2xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">

                            <!-- Judul -->
                            <p
                                class="text-sm font-semibold text-gray-800 dark:text-white mb-3 border-l-4 border-blue-500 pl-2">
                                Diapresiasi oleh Moonton 📩
                            </p>

                            <!-- Gambar 1:1 -->
                            <div class="w-full max-w-xs mx-auto">
                                <div
                                    class="aspect-square overflow-hidden rounded-xl border border-gray-300 dark:border-gray-600">
                                    {{-- <img src="https://i.postimg.cc/mhxyBzvJ/Screenshot_2026_04_08_232607.png" --}}
                                    <img src="./images/mail.png" class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <p class="text-sm md:text-base text-center mt-4 font-medium text-gray-700 dark:text-gray-300">
                                Sebuah pencapaian luar biasa, di mana performa Mas Hoshi akhirnya diakui secara tidak resmi
                                oleh pihak Moonton melalui email.
                            </p>

                            <!-- Sarkas halus -->
                            <p class="text-xs text-center mt-2 text-gray-500 dark:text-gray-400 italic">
                                Dari yang dulu hama banget… sekarang prestasinya bisa jadi warisan UNESCO 😌
                            </p>

                        </div>
                    </div>
                </div>

                <!-- Stats -->
                <div class="grid gap-4 mt-6">
                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-center">
                        <p class="text-sm text-gray-500">Kill</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">999969</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-center">
                        <p class="text-sm text-gray-500">Assist</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">0 </p>
                        <p class="text-sm text-gray-500">selalu last hit 🎯</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-center">
                        <p class="text-sm text-gray-500">Retri Miss</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white">0 </p>
                        <p class="text-sm text-gray-500">butuh lawan retri yang setara 🔥</p>
                    </div>

                    <div class="p-4 rounded-xl bg-gray-100 dark:bg-gray-800 text-center">
                        <p class="text-sm text-gray-500">Level Skill</p>
                        <p class="text-xl font-bold text-green-600">Diatas Sutsujin 🔥</p>
                    </div>
                </div>

            </div>
        </div>
        {{-- <div class="col-span-12 space-y-6 xl:col-span-7">
      <x-ecommerce.ecommerce-metrics />
      <x-ecommerce.monthly-sale />
    </div> --}}
        {{-- <div class="col-span-12 xl:col-span-5">
        <x-ecommerce.monthly-target />
    </div>

    <div class="col-span-12">
      <x-ecommerce.statistics-chart />
    </div>

    <div class="col-span-12 xl:col-span-5">
      <x-ecommerce.customer-demographic />
    </div>

    <div class="col-span-12 xl:col-span-7">
      <x-ecommerce.recent-orders />
    </div> --}}
    </div>
@endsection
