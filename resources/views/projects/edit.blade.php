<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 sm:py-8">
        <div class="mx-auto w-full max-w-2xl px-4 sm:px-6">

            <div class="mb-5 flex items-start gap-3">
                <a
                    href="{{ route('projects.show', $project) }}"
                    class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white text-xl font-black text-slate-900"
                    aria-label="Kembali ke project"
                >
                    ←
                </a>

                <div>
                    <p class="text-sm font-semibold text-slate-600">
                        Pengaturan Project
                    </p>

                    <h1 class="text-2xl font-black text-slate-900 sm:text-3xl">
                        Edit Project
                    </h1>
                </div>
            </div>

            <form
                action="{{ route('projects.update', $project) }}"
                method="POST"
                class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7"
            >
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="text-sm font-extrabold text-slate-900">
                        Nama Project <span class="text-rose-600">*</span>
                    </label>

                    <input
                        id="title"
                        name="title"
                        type="text"
                        value="{{ old('title', $project->title) }}"
                        maxlength="150"
                        required
                        autofocus
                        class="mt-2 block w-full rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
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
                        class="mt-2 block w-full resize-none rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >{{ old('description', $project->description) }}</textarea>

                    @error('description')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <p class="text-sm font-extrabold text-slate-900">
                        Warna Project <span class="text-rose-600">*</span>
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
                            $selectedColor = old('theme_color', $project->theme_color);
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

                <div class="mt-6">
                    <p class="text-sm font-extrabold text-slate-900">
                        Status Project <span class="text-rose-600">*</span>
                    </p>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Hanya project berstatus published yang dapat diakses melalui link permainan publik.
                    </p>

                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="status"
                                value="draft"
                                class="peer sr-only"
                                @checked(old('status', $project->status) === 'draft')
                                required
                            >

                            <span class="flex min-h-16 flex-col items-center justify-center rounded-2xl border-2 border-slate-800 bg-slate-100 px-3 text-center text-sm font-black text-slate-900 opacity-60 transition peer-checked:scale-[1.02] peer-checked:opacity-100 peer-checked:ring-4 peer-checked:ring-slate-800/20">
                                DRAFT
                                <small class="mt-1 text-xs font-medium">Belum bisa dimainkan</small>
                            </span>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="status"
                                value="published"
                                class="peer sr-only"
                                @checked(old('status', $project->status) === 'published')
                                required
                            >

                            <span class="flex min-h-16 flex-col items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#A8EACD] px-3 text-center text-sm font-black text-slate-900 opacity-60 transition peer-checked:scale-[1.02] peer-checked:opacity-100 peer-checked:ring-4 peer-checked:ring-slate-800/20">
                                PUBLISHED
                                <small class="mt-1 text-xs font-medium">Bisa dibuka publik</small>
                            </span>
                        </label>
                    </div>

                    @error('status')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('projects.show', $project) }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white px-5 py-3 text-sm font-extrabold text-slate-900"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-5 py-3 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd]"
                    >
                        Simpan Perubahan
                    </button>
                </div>
            </form>

            <section class="mt-6 rounded-[28px] border-2 border-slate-800 bg-[#FDE2E4] p-5 sm:p-6">
                <h2 class="text-lg font-black text-slate-900">
                    Zona Berbahaya
                </h2>

                <p class="mt-2 text-sm leading-6 text-slate-700">
                    Menghapus project juga akan menghapus seluruh pertanyaan, sesi permainan, dan riwayat jawaban yang terkait.
                </p>

                <form
                    action="{{ route('projects.destroy', $project) }}"
                    method="POST"
                    class="mt-4"
                    onsubmit="return confirm('Hapus project ini beserta semua pertanyaan dan riwayat permainannya? Tindakan ini tidak dapat dibatalkan.');"
                >
                    @csrf
                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#F5B6BE] px-5 py-2.5 text-sm font-extrabold text-slate-900"
                    >
                        Hapus Project
                    </button>
                </form>
            </section>
        </div>
    </div>
</x-app-layout>