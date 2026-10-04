<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 sm:py-8">
        <div class="mx-auto w-full max-w-2xl px-4 sm:px-6">

            <div class="mb-5 flex items-center gap-3">
                <a
                    href="{{ route('dashboard') }}"
                    class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white text-xl font-black text-slate-900"
                    aria-label="Kembali ke dashboard"
                >
                    ←
                </a>

                <div>
                    <p class="text-sm font-semibold text-slate-600">
                        Project Baru
                    </p>

                    <h1 class="text-2xl font-black text-slate-900 sm:text-3xl">
                        Buat Spinner Baru
                    </h1>
                </div>
            </div>

            <form
                action="{{ route('projects.store') }}"
                method="POST"
                class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7"
            >
                @csrf

                <div>
                    <label for="title" class="text-sm font-extrabold text-slate-900">
                        Nama Project <span class="text-rose-600">*</span>
                    </label>

                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title') }}"
                        placeholder="Contoh: Mitos atau Fakta Hipertensi"
                        maxlength="150"
                        required
                        autofocus
                        class="mt-2 block w-full rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >

                    @error('title')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="description" class="text-sm font-extrabold text-slate-900">
                        Deskripsi <span class="font-medium text-slate-500">(opsional)</span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="1000"
                        placeholder="Ceritakan tujuan atau materi dari permainan ini..."
                        class="mt-2 block w-full resize-none rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <p class="text-sm font-extrabold text-slate-900">
                        Pilih Warna Project <span class="text-rose-600">*</span>
                    </p>

                    <p class="mt-1 text-sm text-slate-600">
                        Warna ini akan tampil pada kartu project di dashboard.
                    </p>

                    <div class="mt-3 grid grid-cols-5 gap-3">
                        @php
                            $colors = [
                                '#93E5F2',
                                '#F9D978',
                                '#EFC0E7',
                                '#A8EACD',
                                '#CFC0F2',
                            ];
                            $selectedColor = old('theme_color', '#93E5F2');
                        @endphp

                        @foreach ($colors as $color)
                            <label class="cursor-pointer">
                                <input
                                    type="radio"
                                    name="theme_color"
                                    value="{{ $color }}"
                                    class="peer sr-only"
                                    @checked($selectedColor === $color)
                                >

                                <span
                                    class="flex aspect-square items-center justify-center rounded-2xl border-2 border-slate-800 text-lg opacity-70 transition peer-checked:scale-105 peer-checked:opacity-100 peer-checked:ring-4 peer-checked:ring-slate-800/20"
                                    style="background-color: {{ $color }};"
                                >
                                    <span class="hidden font-black peer-checked:inline">✓</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    @error('theme_color')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white px-5 py-3 text-sm font-extrabold text-slate-900"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-5 py-3 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd]"
                    >
                        Simpan Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>