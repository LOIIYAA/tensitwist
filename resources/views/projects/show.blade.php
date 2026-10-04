<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 sm:py-8">
        <div class="mx-auto w-full max-w-4xl px-4 sm:px-6">

            <div class="flex items-start justify-between gap-3">
                <div class="flex items-start gap-3">
                    <a href="{{ route('projects.index') }}"
                        class="inline-flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white text-xl font-black text-slate-900"
                        aria-label="Kembali ke daftar project">
                        ←
                    </a>

                    <div>
                        <p class="text-sm font-semibold text-slate-600">
                            Kelola Project
                        </p>

                        <h1 class="text-2xl font-black leading-tight text-slate-900 sm:text-3xl">
                            {{ $project->title }}
                        </h1>
                    </div>
                </div>

                <a href="{{ route('projects.edit', $project) }}"
                    class="inline-flex min-h-11 shrink-0 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#F9D978] px-4 py-2.5 text-sm font-extrabold text-slate-900">
                    Edit
                </a>
            </div>

            @if (session('success'))
                <div
                    class="mt-5 rounded-2xl border-2 border-slate-800 bg-[#A8EACD] px-4 py-3 text-sm font-bold text-slate-900">
                    {{ session('success') }}
                </div>
            @endif

            <section
                class="mt-6 rounded-[28px] border-2 border-slate-800 p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7"
                style="background-color: {{ $project->theme_color }};">
                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border-2 border-slate-800 bg-white px-3 py-1 text-xs font-extrabold uppercase text-slate-900">
                        {{ $project->status }}
                    </span>

                    <span
                        class="rounded-full border-2 border-slate-800 bg-white/80 px-3 py-1 text-xs font-bold text-slate-900">
                        {{ $project->questions->count() }} Pertanyaan
                    </span>
                </div>

                <p class="mt-5 text-sm leading-6 text-slate-800">
                    {{ $project->description ?: 'Belum ada deskripsi untuk project ini.' }}
                </p>

                <div class="mt-6 rounded-2xl border-2 border-slate-800 bg-white/80 p-4">
                    <p class="text-xs font-bold uppercase tracking-wide text-slate-600">
                        Link permainan publik
                    </p>

                    @if ($project->status === 'published')
                        <div class="mt-2 flex flex-col gap-3 sm:flex-row sm:items-center">
                            <code
                                class="block flex-1 break-all rounded-xl border-2 border-slate-800 bg-white px-3 py-2 text-xs font-semibold text-slate-800">
                                    {{ url('/play/' . $project->slug) }}
                                </code>

                            <a href="{{ route('game.start', $project) }}"
                                class="inline-flex min-h-10 items-center justify-center rounded-xl border-2 border-slate-800 bg-[#93E5F2] px-4 py-2 text-xs font-extrabold text-slate-900">
                                Mainkan
                            </a>
                        </div>
                    @else
                        <p class="mt-2 text-sm leading-6 text-slate-700">
                            Project masih draft. Ubah status menjadi <strong>published</strong> melalui tombol Edit agar
                            link permainan dapat dibuka publik.
                        </p>
                    @endif
                </div>
            </section>

            <section class="mt-7">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-600">
                            Materi permainan
                        </p>

                        <h2 class="text-xl font-black text-slate-900 sm:text-2xl">
                            Daftar Pertanyaan
                        </h2>

                        <a href="{{ route('projects.questions.index', $project) }}"
                            class="mt-1 inline-block text-sm font-bold text-slate-700 underline">
                            Kelola semua pertanyaan
                        </a>
                    </div>

                    <a href="{{ route('projects.questions.create', $project) }}"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-4 py-2.5 text-sm font-extrabold text-slate-900">
                        + Tambah
                    </a>
                </div>

                @if ($project->questions->isEmpty())
                    <div class="mt-4 rounded-[28px] border-2 border-dashed border-slate-500 bg-white p-7 text-center">
                        <div class="text-4xl">💡</div>

                        <h3 class="mt-3 text-lg font-black text-slate-900">
                            Belum ada pertanyaan
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Tambahkan pernyataan seputar hipertensi, lalu tentukan apakah jawaban yang benar adalah mitos
                            atau fakta.
                        </p>
                    </div>
                @else
                    <div class="mt-4 space-y-3">
                        @foreach ($project->questions as $index => $question)
                            <article class="rounded-3xl border-2 border-slate-800 bg-white p-4 sm:p-5">
                                <div class="flex items-start gap-3">
                                    <span
                                        class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-xl border-2 border-slate-800 bg-[#CFC0F2] text-sm font-black text-slate-900">
                                        {{ $index + 1 }}
                                    </span>

                                    <div class="min-w-0 flex-1">
                                        <p class="font-bold leading-6 text-slate-900">
                                            {{ $question->question_text }}
                                        </p>

                                        <div class="mt-3 flex flex-wrap items-center gap-2">
                                            <span
                                                class="rounded-full border-2 border-slate-800 px-3 py-1 text-xs font-extrabold uppercase text-slate-900 {{ $question->correct_answer === 'mitos' ? 'bg-[#EFC0E7]' : 'bg-[#93E5F2]' }}">
                                                {{ $question->correct_answer }}
                                            </span>

                                            @if (!$question->is_active)
                                                <span
                                                    class="rounded-full border-2 border-slate-800 bg-slate-200 px-3 py-1 text-xs font-bold text-slate-700">
                                                    Nonaktif
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>