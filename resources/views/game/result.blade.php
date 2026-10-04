<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 text-slate-900 sm:py-8">
        <div class="mx-auto w-full max-w-3xl px-4 sm:px-6 lg:px-8">
            <section class="rounded-[28px] border-2 border-slate-800 bg-white p-5 text-center shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-8">
                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full border-2 border-slate-800 bg-[#F9D978] text-5xl shadow-[0_6px_0_rgba(44,65,100,0.18)]">
                    🎉
                </div>

                <p class="mt-6 text-sm font-medium text-slate-600">
                    TensiTwist · Permainan selesai
                </p>

                <h1 class="mt-1 text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                    Hebat, {{ $session->player_name }}!
                </h1>

                <p class="mx-auto mt-3 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">
                    Kamu sudah menyelesaikan permainan
                    <span class="font-extrabold text-slate-900">
                        {{ $session->project->title }}
                    </span>.
                    Yuk, lihat hasilmu!
                </p>

                <div class="mt-7 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border-2 border-slate-800 bg-[#A8EACD] p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Benar
                        </p>

                        <p class="mt-1 text-4xl font-black text-slate-900">
                            {{ $session->correct_count }}
                        </p>

                        <p class="mt-1 text-xs font-semibold text-slate-700">
                            jawaban tepat
                        </p>
                    </div>

                    <div class="rounded-2xl border-2 border-slate-800 bg-[#FFD6E5] p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Salah
                        </p>

                        <p class="mt-1 text-4xl font-black text-slate-900">
                            {{ $session->wrong_count }}
                        </p>

                        <p class="mt-1 text-xs font-semibold text-slate-700">
                            masih bisa dipelajari
                        </p>
                    </div>

                    <div class="rounded-2xl border-2 border-slate-800 bg-[#93E5F2] p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wide text-slate-700">
                            Skor akhir
                        </p>

                        <p class="mt-1 text-4xl font-black text-slate-900">
                            {{ $scorePercentage }}%
                        </p>

                        <p class="mt-1 text-xs font-semibold text-slate-700">
                            dari {{ $totalQuestions }} pertanyaan
                        </p>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border-2 border-slate-800 bg-[#EAF1FC] p-5 text-left">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-600">
                                Ringkasan progres
                            </p>

                            <p class="mt-1 text-sm font-bold text-slate-900">
                                {{ $answeredCount }} dari {{ $totalQuestions }} pertanyaan sudah dijawab.
                            </p>
                        </div>

                        <span class="text-3xl">🩺</span>
                    </div>

                    <div class="mt-4 h-4 overflow-hidden rounded-full border-2 border-slate-800 bg-white">
                        <div
                            class="h-full bg-[#4DA8E8]"
                            style="width: {{ $scorePercentage }}%;"
                        ></div>
                    </div>
                </div>

                <div class="mt-7 grid gap-3 sm:grid-cols-2">
                    <a
                        href="{{ route('game.start', $session->project) }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-5 py-3 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd] active:translate-y-0"
                    >
                        🌀 Main Lagi
                    </a>

                    <a
                        href="{{ route('dashboard') }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white px-5 py-3 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#EAF1FC] active:translate-y-0"
                    >
                        Kembali ke Dashboard
                    </a>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>