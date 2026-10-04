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
                        {{ $project->title }}
                    </p>

                    <h1 class="text-2xl font-black text-slate-900 sm:text-3xl">
                        Tambah Pertanyaan
                    </h1>
                </div>
            </div>

            <form
                action="{{ route('projects.questions.store', $project) }}"
                method="POST"
                class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7"
            >
                @csrf

                <div>
                    <label for="question_text" class="text-sm font-extrabold text-slate-900">
                        Pernyataan atau Pertanyaan <span class="text-rose-600">*</span>
                    </label>

                    <textarea
                        id="question_text"
                        name="question_text"
                        rows="5"
                        maxlength="1000"
                        required
                        autofocus
                        placeholder="Contoh: Hipertensi selalu menimbulkan gejala seperti sakit kepala."
                        class="mt-2 block w-full resize-none rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >{{ old('question_text') }}</textarea>

                    @error('question_text')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="spinner_label" class="text-sm font-extrabold text-slate-900">
                        Label Sektor Spinner
                        <span class="font-medium text-slate-500">(opsional)</span>
                    </label>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Label ini akan tampil di dalam sektor roda spinner.
                        Gunakan teks singkat agar mudah dibaca.
                    </p>

                    <input
                        id="spinner_label"
                        type="text"
                        name="spinner_label"
                        value="{{ old('spinner_label') }}"
                        maxlength="50"
                        placeholder="Contoh: Mudah, Sedang, Sulit, Garam, Obat"
                        class="mt-2 block w-full rounded-2xl border-2 border-slate-800 bg-[#F9D978] px-4 py-3 text-sm font-extrabold text-slate-900 outline-none transition placeholder:font-medium placeholder:text-slate-500 focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >

                    @error('spinner_label')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <p class="text-sm font-extrabold text-slate-900">
                        Jawaban yang Benar <span class="text-rose-600">*</span>
                    </p>

                    <p class="mt-1 text-sm leading-6 text-slate-600">
                        Pilihan ini hanya tersimpan di server dan tidak ditampilkan sebelum pemain menjawab.
                    </p>

                    <div class="mt-3 grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="correct_answer"
                                value="mitos"
                                class="peer sr-only"
                                @checked(old('correct_answer') === 'mitos')
                                required
                            >

                            <span class="flex min-h-16 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#EFC0E7] px-4 text-base font-black text-slate-900 opacity-60 transition peer-checked:scale-[1.02] peer-checked:opacity-100 peer-checked:ring-4 peer-checked:ring-slate-800/20">
                                MITOS
                            </span>
                        </label>

                        <label class="cursor-pointer">
                            <input
                                type="radio"
                                name="correct_answer"
                                value="fakta"
                                class="peer sr-only"
                                @checked(old('correct_answer') === 'fakta')
                                required
                            >

                            <span class="flex min-h-16 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#93E5F2] px-4 text-base font-black text-slate-900 opacity-60 transition peer-checked:scale-[1.02] peer-checked:opacity-100 peer-checked:ring-4 peer-checked:ring-slate-800/20">
                                FAKTA
                            </span>
                        </label>
                    </div>

                    @error('correct_answer')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="mt-5">
                    <label for="explanation" class="text-sm font-extrabold text-slate-900">
                        Penjelasan Jawaban <span class="font-medium text-slate-500">(opsional)</span>
                    </label>

                    <textarea
                        id="explanation"
                        name="explanation"
                        rows="4"
                        maxlength="2000"
                        placeholder="Contoh: Hipertensi sering disebut silent killer karena banyak penderita tidak merasakan gejala."
                        class="mt-2 block w-full resize-none rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium leading-6 text-slate-900 outline-none transition placeholder:text-slate-400 focus:border-[#4DA8E8] focus:ring-4 focus:ring-[#4DA8E8]/20"
                    >{{ old('explanation') }}</textarea>

                    @error('explanation')
                        <p class="mt-2 text-sm font-semibold text-rose-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <label class="mt-5 flex cursor-pointer items-center gap-3 rounded-2xl border-2 border-slate-800 bg-[#CFC0F2] p-4">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        class="h-5 w-5 rounded border-slate-800 text-slate-900 focus:ring-slate-800"
                        @checked(old('is_active', true))
                    >

                    <span>
                        <span class="block text-sm font-extrabold text-slate-900">
                            Aktifkan pertanyaan
                        </span>

                        <span class="mt-1 block text-xs leading-5 text-slate-700">
                            Pertanyaan aktif akan dapat muncul pada spinner saat permainan dimulai.
                        </span>
                    </span>
                </label>

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
                        Simpan Pertanyaan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>