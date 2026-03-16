@php
    $kecamatan = \App\Models\kecamatanModel::all();
    $desa = \App\Models\desaModel::all();
@endphp

<div x-data='{
    modalGantiPassword: {{ $errors->has("password_lama") || $errors->has("password_baru") ? "true" : "false" }},

    selectedKecamatan: "{{ Auth::user()?->desa?->id_kecamatan ?? '' }}",
    modalEditProfile: false,
    selectedKecamatan: "{{ Auth::user()?->desa?->id_kecamatan ?? '' }}",
    selectedDesa: "{{ Auth::user()->id_desa ?? '' }}",
    semuaDesa: @json($desa),
    get filteredDesa() {
        if (!this.selectedKecamatan) return [];
        return this.semuaDesa.filter(d => d.id_kecamatan == this.selectedKecamatan);
    }
}'>
    <div class="p-5 mb-6 border border-gray-200 rounded-2xl dark:border-gray-800 lg:p-6">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
            <div class="w-full">
                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90 lg:mb-6">
                    Informasi Personal
                </h4>

                <div class="grid grid-cols-1 gap-4 lg:grid-cols-2 lg:gap-7 2xl:gap-x-32">
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Auth::user()->nama ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Username</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Auth::user()->username ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Alamat Email</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Auth::user()->email ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Nomor Handphone</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Auth::user()->no_hp ?? '-' }}</p>
                    </div>
                    {{-- <div>
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Peran (Role)</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90 capitalize">{{ Auth::user()->role ?? '-' }}</p>
                    </div> --}}
                    <div class="lg:col-span-2">
                        <p class="mb-2 text-xs leading-normal text-gray-500 dark:text-gray-400">Alamat Lengkap</p>
                        <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ Auth::user()->alamat ?? '-' }}</p>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
                            @if(Auth::user()?->desa)
                                Desa {{ Auth::user()?->desa?->nama_desa }}, Kecamatan {{ Auth::user()?->desa?->kecamatan?->nama_kecamatan }}
                            @else
                                Wilayah belum diatur
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <button @click="modalEditProfile = true" class="edit-button shrink-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                <svg class="fill-current" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z" fill="" />
                </svg>
                Edit Profil
            </button>
            <button @click="modalGantiPassword = true" type="button" class="edit-button shrink-0 inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Ganti Password
            </button>
        </div>
    </div>

    <template x-teleport="body">
        <div x-show="modalEditProfile" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
            @click.self="modalEditProfile = false">
            <div class="no-scrollbar relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="px-2 pr-14">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Edit Profil Anda</h4>
                    <p class="mb-6 text-sm text-gray-500 dark:text-gray-400 lg:mb-7">Perbarui detail informasi akun Anda di bawah ini.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col">
                    @csrf
                    @method('PUT')

                    <div class="custom-scrollbar overflow-y-auto p-2">
                        <div class="grid grid-cols-1 gap-x-6 gap-y-5 lg:grid-cols-2">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Nama Lengkap</label>
                                <input type="text" name="nama" value="{{ Auth::user()->nama }}" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Username</label>
                                <input type="text" name="username" value="{{ Auth::user()->username }}" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Email</label>
                                <input type="email" name="email" value="{{ Auth::user()->email }}" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">No Handphone</label>
                                <input type="text" name="no_hp" value="{{ Auth::user()->no_hp }}" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Kecamatan</label>
                                <select x-model="selectedKecamatan" @change="selectedDesa = ''" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="" disabled>Pilih Kecamatan</option>
                                    @if(isset($kecamatan))
                                        @foreach($kecamatan as $kec)
                                            <option value="{{ $kec->id_kecamatan }}">{{ $kec->nama_kecamatan }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Desa</label>
                                <select name="id_desa" x-model="selectedDesa" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                    <option value="" disabled>Pilih Desa</option>
                                    <template x-for="d in filteredDesa" :key="d.id_desa">
                                        <option :value="d.id_desa" x-text="d.nama_desa" :selected="d.id_desa == selectedDesa"></option>
                                    </template>
                                </select>
                            </div>

                            <div class="col-span-1 lg:col-span-2">
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Alamat Lengkap</label>
                                <input type="text" name="alamat" value="{{ Auth::user()->alamat }}" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                            </div>

                            {{-- Input role disembunyikan / tidak diikutkan agar user tidak bisa mengganti role-nya sendiri --}}

                        </div>
                    </div>
                    <div class="flex items-center gap-3 px-2 mt-6 lg:justify-end">
                        <button @click="modalEditProfile = false" type="button" class="flex w-full justify-center rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] sm:w-auto">
                            Batal
                        </button>
                        <button type="submit" class="flex w-full justify-center rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 sm:w-auto">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div x-show="modalGantiPassword" style="display: none;" class="fixed inset-0 z-[99999] flex items-center justify-center bg-black/50 px-4 py-5 backdrop-blur-sm"
            @click.self="modalGantiPassword = false">
            <div class="relative w-full max-w-[500px] rounded-3xl bg-white p-4 dark:bg-gray-900 lg:p-11"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100">

                <div class="mb-6">
                    <h4 class="mb-2 text-2xl font-semibold text-gray-800 dark:text-white/90">Ganti Password</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pastikan untuk mengingat password baru Anda.</p>
                </div>

                {{-- Alert Error Khusus Password --}}
                @error('password_lama')
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400">{{ $message }}</div>
                @enderror
                @error('password_baru')
                    <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400">{{ $message }}</div>
                @enderror

                <form method="POST" action="{{ route('profile.password') }}" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Lama</label>
                        <input type="password" name="password_lama" required class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Password Baru</label>
                        <input type="password" name="password_baru" required minlength="6" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Konfirmasi Password Baru</label>
                        <input type="password" name="password_baru_confirmation" required minlength="6" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 focus:border-brand-300 focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                    </div>

                    <div class="flex items-center gap-3 mt-4 justify-end">
                        <button @click="modalGantiPassword = false" type="button" class="rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                            Batal
                        </button>
                        <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </template>
</div>
