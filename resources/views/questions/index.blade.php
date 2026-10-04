<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 sm:py-8">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6">

            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
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
                            Kelola Pertanyaan
                        </h1>
                    </div>
                </div>

                <a
                    href="{{ route('projects.questions.create', $project) }}"
                    class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-4 py-2.5 text-sm font-extrabold text-slate-900"
                >
                    + Tambah
                </a>
            </div>

            @if (session('success'))
                <div class="mt-5 rounded-2xl border-2 border-slate-800 bg-[#A8EACD] px-4 py-3 text-sm font-bold text-slate-900">
                    {{ session('success') }}
                </div>
            @endif

            @if ($questions->isEmpty())
                <section class="mt-6 rounded-[28px] border-2 border-dashed border-slate-500 bg-white p-8 text-center sm:p-12">
                    <div class="text-5xl">💡</div>

                    <h2 class="mt-4 text-xl font-black text-slate-900">
                        Belum ada pertanyaan
                    </h2>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                        Tambahkan pernyataan tentang hipertensi untuk digunakan dalam permainan spinner.
                    </p>

                    <a
                        href="{{ route('projects.questions.create', $project) }}"
                        class="mt-5 inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#93E5F2] px-5 py-2.5 text-sm font-extrabold text-slate-900"
                    >
                        Tambah Pertanyaan Pertama
                    </a>
                </section>
            @else
                <section class="mt-6 space-y-4">
                    @foreach ($questions as $index => $question)
                        <article class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)]">
                            <div class="flex items-start gap-3">
                                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#CFC0F2] text-sm font-black text-slate-900">
                                    {{ $index + 1 }}
                                </span>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                        <p class="font-bold leading-6 text-slate-900">
                                            {{ $question->question_text }}
                                        </p>

                                        <div class="flex shrink-0 flex-wrap gap-2">
                                            <span class="rounded-full border-2 border-slate-800 px-3 py-1 text-xs font-extrabold uppercase text-slate-900 {{ $question->correct_answer === 'mitos' ? 'bg-[#EFC0E7]' : 'bg-[#93E5F2]' }}">
                                                {{ $question->correct_answer }}
                                            </span>

                                            @if ($question->is_active)
                                                <span class="rounded-full border-2 border-slate-800 bg-[#A8EACD] px-3 py-1 text-xs font-bold text-slate-900">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="rounded-full border-2 border-slate-800 bg-slate-200 px-3 py-1 text-xs font-bold text-slate-700">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($question->explanation)
                                        <div class="mt-4 rounded-2xl border-2 border-slate-800 bg-slate-50 p-4">
                                            <p class="text-xs font-extrabold uppercase tracking-wide text-slate-600">
                                                Penjelasan
                                            </p>

                                            <p class="mt-1 text-sm leading-6 text-slate-700">
                                                {{ $question->explanation }}
                                            </p>
                                        </div>
                                    @endif

                                    <div class="mt-4 grid grid-cols-2 gap-3 sm:flex sm:justify-end">
                                        <a
                                            href="{{ route('projects.questions.edit', [$project, $question]) }}"
                                            class="inline-flex min-h-10 items-center justify-center rounded-xl border-2 border-slate-800 bg-[#F9D978] px-4 py-2 text-xs font-extrabold text-slate-900"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('projects.questions.destroy', [$project, $question]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Hapus pertanyaan ini? Tindakan ini tidak dapat dibatalkan.');"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button
                                                type="submit"
                                                class="flex min-h-10 w-full items-center justify-center rounded-xl border-2 border-slate-800 bg-[#F5B6BE] px-4 py-2 text-xs font-extrabold text-slate-900 sm:w-auto"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </section>
            @endif
        </div>
    </div>
</x-app-layout>