<x-app-layout>
    <div id="game-app" data-next-question-url="{{ route('game.next-question', $session) }}"
        data-submit-answer-url="{{ route('game.submit-answer', $session) }}"
        data-dashboard-url="{{ route('dashboard') }}" data-result-url="{{ route('game.result', $session) }}"
        data-spinner-sound="{{ asset('sounds/spinner.mp3') }}" data-success-sound="{{ asset('sounds/success.mp3') }}"
        data-failure-sound="{{ asset('sounds/failure.mp3') }}" data-spinner-questions='@json(
            $spinnerQuestions->map(fn($question) => [
                "id" => $question->id,
                "label" => $question->spinner_label ?: "Pertanyaan",
            ])->values()
        )' class="min-h-screen bg-[#EAF1FC] py-6 text-slate-900 sm:py-8">

        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">
            {{-- Header game --}}
            <section
                class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600">
                            TensiTwist · Mitos atau Fakta Hipertensi
                        </p>

                        <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                            {{ $session->project->title ?? 'Mitos atau Fakta?' }}
                        </h1>

                        <p class="mt-2 text-sm leading-6 text-slate-600 sm:text-base">
                            Pemain:
                            <span class="font-extrabold text-slate-900">
                                {{ $session->player_name }}
                            </span>
                            · Putar roda dan jawab setiap pernyataan dengan tepat.
                        </p>
                    </div>

                    <div
                        class="grid grid-cols-3 overflow-hidden rounded-2xl border-2 border-slate-800 bg-white text-center">
                        <div class="min-w-20 border-r-2 border-slate-800 bg-[#A8EACD] px-3 py-3 sm:min-w-24 sm:px-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-wide text-slate-700 sm:text-xs">
                                Benar
                            </p>

                            <p id="correct-count" class="mt-1 text-2xl font-black text-slate-900">
                                {{ $session->correct_count }}
                            </p>
                        </div>

                        <div class="min-w-20 border-r-2 border-slate-800 bg-[#FFD6E5] px-3 py-3 sm:min-w-24 sm:px-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-wide text-slate-700 sm:text-xs">
                                Salah
                            </p>

                            <p id="wrong-count" class="mt-1 text-2xl font-black text-slate-900">
                                {{ $session->wrong_count }}
                            </p>
                        </div>

                        <div class="min-w-20 bg-[#F9D978] px-3 py-3 sm:min-w-24 sm:px-4">
                            <p class="text-[11px] font-extrabold uppercase tracking-wide text-slate-700 sm:text-xs">
                                Progres
                            </p>

                            <p id="progress-count" class="mt-1 text-lg font-black text-slate-900 sm:text-2xl">
                                {{ $session->answers()->count() }} / {{ $session->total_questions }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 border-t-2 border-slate-200 pt-5">
                    <button id="end-game-button" type="button"
                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#FFD6E5] px-4 py-2.5 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#F7B7CE] active:translate-y-0">
                        Akhiri Permainan
                    </button>
                </div>
            </section>

            <main class="mt-7 grid items-start gap-6 lg:grid-cols-[1.08fr_0.92fr]">
                {{-- Kartu spinner --}}
                <section
                    class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium text-slate-600">
                                Putaran berikutnya
                            </p>

                            <h2 class="mt-1 text-2xl font-black text-slate-900 sm:text-3xl">
                                Putar Spinner 🌀
                            </h2>
                        </div>

                        <span
                            class="rounded-full border-2 border-slate-800 bg-[#93E5F2] px-3 py-1 text-xs font-extrabold text-slate-900">
                            Acak
                        </span>
                    </div>

                    <div class="mt-6 flex justify-center">
                        <div class="relative flex aspect-square w-full max-w-[430px] items-center justify-center">
                            {{-- Pointer --}}
                            <div
                                class="absolute -top-1 z-20 h-0 w-0 border-x-[22px] border-t-[40px] border-x-transparent border-t-slate-800">
                            </div>

                            {{-- Roda spinner --}}
                            <div id="spinner-wheel"
                                class="relative z-10 flex h-[min(76vw,390px)] w-[min(76vw,390px)] items-center justify-center rounded-full border-[7px] border-slate-800 shadow-[0_10px_0_rgba(44,65,100,0.22)] transition-transform duration-[3600ms] ease-[cubic-bezier(0.12,0.77,0.18,1)]"
                                style="background: conic-gradient(
        from -45deg,
        #93E5F2 0deg 90deg,
        #F9D978 90deg 180deg,
        #FFD6E5 180deg 270deg,
        #A8EACD 270deg 360deg
    );">
                                {{-- Dua garis pembagi menjadi empat sektor --}}
                                <div class="pointer-events-none absolute inset-0 rounded-full" style="background:
            linear-gradient(45deg, transparent 49.4%, rgba(30,41,59,.78) 49.6%, rgba(30,41,59,.78) 50.4%, transparent 50.6%),
            linear-gradient(135deg, transparent 49.4%, rgba(30,41,59,.78) 49.6%, rgba(30,41,59,.78) 50.4%, transparent 50.6%);
        "></div>

                                {{-- Label sektor kiri atas --}}
                                <div data-wheel-label="0"
                                    class="absolute left-[13%] top-[22%] z-10 w-[27%] -rotate-45 text-center text-[11px] font-black leading-tight text-slate-900 sm:text-sm">
                                </div>

                                {{-- Label sektor kanan atas --}}
                                <div data-wheel-label="1"
                                    class="absolute right-[13%] top-[22%] z-10 w-[27%] rotate-45 text-center text-[11px] font-black leading-tight text-slate-900 sm:text-sm">
                                </div>

                                {{-- Label sektor kanan bawah --}}
                                <div data-wheel-label="2"
                                    class="absolute bottom-[22%] right-[13%] z-10 w-[27%] -rotate-45 text-center text-[11px] font-black leading-tight text-slate-900 sm:text-sm">
                                </div>

                                {{-- Label sektor kiri bawah --}}
                                <div data-wheel-label="3"
                                    class="absolute bottom-[22%] left-[13%] z-10 w-[27%] rotate-45 text-center text-[11px] font-black leading-tight text-slate-900 sm:text-sm">
                                </div>

                                {{-- Tengah roda --}}
                                <div
                                    class="relative z-20 flex h-28 w-28 items-center justify-center rounded-full border-[6px] border-slate-800 bg-white text-center shadow-[0_5px_0_rgba(44,65,100,0.20)] sm:h-32 sm:w-32">
                                    <span class="text-sm font-black leading-tight text-slate-900 sm:text-base">
                                        TENSI<br>TWIST
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button id="spin-button" type="button"
                        class="mx-auto mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-5 py-3 text-base font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd] active:translate-y-0 sm:w-auto sm:min-w-64">
                        🌀 Putar Spinner
                    </button>

                    <p id="game-status"
                        class="mt-5 hidden rounded-2xl border-2 border-slate-800 bg-[#FFD6E5] px-4 py-3 text-center text-sm font-bold text-slate-900">
                    </p>
                </section>

                {{-- Kartu panduan --}}
                <aside
                    class="rounded-[28px] border-2 border-slate-800 bg-[#93E5F2] p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="text-sm font-medium text-slate-700">
                                Cara bermain
                            </p>

                            <h2 class="mt-1 text-2xl font-black leading-tight text-slate-900 sm:text-3xl">
                                Buktikan kamu jago! 🩺
                            </h2>
                        </div>

                        <span class="text-4xl">🧠</span>
                    </div>

                    <div class="mt-7 space-y-4">
                        <div class="flex gap-3 rounded-2xl border-2 border-slate-800 bg-white p-4">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-slate-800 bg-[#F9D978] font-black text-slate-900">
                                1
                            </span>

                            <p class="pt-1 text-sm leading-6 text-slate-700">
                                Klik tombol <span class="font-extrabold text-slate-900">Putar Spinner</span> untuk
                                mengambil pertanyaan secara acak.
                            </p>
                        </div>

                        <div class="flex gap-3 rounded-2xl border-2 border-slate-800 bg-white p-4">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-slate-800 bg-[#A8EACD] font-black text-slate-900">
                                2
                            </span>

                            <p class="pt-1 text-sm leading-6 text-slate-700">
                                Tentukan apakah pernyataan itu termasuk <span
                                    class="font-extrabold text-slate-900">Mitos</span> atau <span
                                    class="font-extrabold text-slate-900">Fakta</span>.
                            </p>
                        </div>

                        <div class="flex gap-3 rounded-2xl border-2 border-slate-800 bg-white p-4">
                            <span
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full border-2 border-slate-800 bg-[#FFD6E5] font-black text-slate-900">
                                3
                            </span>

                            <p class="pt-1 text-sm leading-6 text-slate-700">
                                Baca penjelasannya, lalu lanjutkan sampai semua pertanyaan selesai.
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 rounded-2xl border-2 border-slate-800 bg-[#F9D978] p-4">
                        <p class="text-sm font-extrabold text-slate-900">
                            Tips: setiap pertanyaan hanya dapat dijawab satu kali. Pilih dengan teliti, ya!
                        </p>
                    </div>
                </aside>
            </main>
        </div>

        {{-- Modal pertanyaan --}}
        <div id="question-modal"
            class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
            <div
                class="w-full max-w-2xl rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_12px_0_rgba(30,41,59,0.25)] sm:p-8">
                <div class="flex items-center justify-between gap-3">
                    <span
                        class="rounded-full border-2 border-slate-800 bg-[#F9D978] px-3 py-1 text-xs font-extrabold uppercase text-slate-900">
                        Pertanyaan Spinner
                    </span>

                    <span class="text-3xl">🌀</span>
                </div>

                <h2 id="question-text"
                    class="mt-6 text-center text-2xl font-black leading-snug text-slate-900 sm:text-3xl">
                    Memuat pertanyaan...
                </h2>

                <p class="mt-4 text-center text-sm leading-6 text-slate-600 sm:text-base">
                    Menurutmu, pernyataan ini adalah mitos atau fakta?
                </p>

                <div class="mt-7 grid gap-4 sm:grid-cols-2">
                    <button type="button" data-answer="mitos"
                        class="min-h-16 rounded-2xl border-2 border-slate-800 bg-[#FFD6E5] px-6 py-4 text-lg font-black text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#F7B7CE] active:translate-y-0">
                        ✕ MITOS
                    </button>

                    <button type="button" data-answer="fakta"
                        class="min-h-16 rounded-2xl border-2 border-slate-800 bg-[#A8EACD] px-6 py-4 text-lg font-black text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#7FD8B0] active:translate-y-0">
                        ✓ FAKTA
                    </button>
                </div>
            </div>
        </div>

        {{-- Modal hasil --}}
        <div id="result-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 px-4 py-6">
            <div
                class="w-full max-w-xl rounded-[28px] border-2 border-slate-800 bg-white p-5 text-center shadow-[0_12px_0_rgba(30,41,59,0.25)] sm:p-8">
                <div id="result-icon" class="text-6xl">
                    🎉
                </div>

                <h2 id="result-title" class="mt-4 text-3xl font-black text-slate-900">
                    Benar!
                </h2>

                <p id="result-answer" class="mt-3 text-lg font-extrabold text-slate-900">
                    Jawabanmu tepat.
                </p>

                <div class="mt-6 rounded-2xl border-2 border-slate-800 bg-[#EAF1FC] p-5 text-left">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-600">
                        Penjelasan
                    </p>

                    <p id="result-explanation" class="mt-2 text-sm leading-6 text-slate-700 sm:text-base"></p>
                </div>

                <button id="next-round-button" type="button"
                    class="mt-7 inline-flex min-h-12 w-full items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-6 py-3 text-base font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd] active:translate-y-0">
                    Putar Lagi
                </button>
            </div>
        </div>
    </div>
</x-app-layout>