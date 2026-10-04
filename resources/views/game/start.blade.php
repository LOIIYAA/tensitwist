<x-guest-layout>
    <div class="min-h-screen bg-[#EAF1FC] px-4 py-6 sm:py-10">
        <main class="mx-auto w-full max-w-md">

            <section
                class="rounded-[32px] border-2 border-slate-800 p-6 shadow-[0_10px_24px_rgba(44,65,100,0.14)] sm:p-8"
                style="background-color: {{ $project->theme_color }};"
            >
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-slate-700">
                            TensiTwist
                        </p>

                        <h1 class="mt-1 text-3xl font-black leading-tight text-slate-900">
                            {{ $project->title }}
                        </h1>
                    </div>

                    <span class="text-4xl">🌀</span>
                </div>

                <p class="mt-5 text-sm leading-6 text-slate-800">
                    {{ $project->description ?: 'Ayo mainkan spinner mitos atau fakta!' }}
                </p>

                <div class="mt-6 rounded-2xl border-2 border-slate-800 bg-white/80 p-4">
                    <p class="text-sm font-bold text-slate-700">
                        Siap bermain?
                    </p>

                    <p class="mt-1 text-sm leading-6 text-slate-700">
                        Ada <strong>{{ $activeQuestionCount }} pertanyaan aktif</strong>. Setiap pertanyaan hanya akan muncul satu kali dalam sesi ini.
                    </p>
                </div>

                @if ($errors->has('game'))
                    <div class="mt-5 rounded-2xl border-2 border-slate-800 bg-[#FDE2E4] p-4 text-sm font-bold text-slate-900">
                        {{ $errors->first('game') }}
                    </div>
                @endif

                <form
                    action="{{ route('game.session.create', $project) }}"
                    method="POST"
                    class="mt-6"
                >
                    @csrf

                    <label for="player_name" class="text-sm font-extrabold text-slate-900">
                        Nama Pemain <span class="font-medium text-slate-600">(opsional)</span>
                    </label>

                    <input
                        id="player_name"
                        name="player_name"
                        type="text"
                        value="{{ old('player_name') }}"
                        maxlength="100"
                        placeholder="Contoh: Fani"
                        class="mt-2 block w-full rounded-2xl border-2 border-slate-800 bg-white px-4 py-3 text-sm font-medium text-slate-900 outline-none placeholder:text-slate-400 focus:ring-4 focus:ring-slate-800/20"
                    >

                    @error('player_name')
                        <p class="mt-2 text-sm font-semibold text-rose-700">
                            {{ $message }}
                        </p>
                    @enderror

                    <button
                        type="submit"
                        @disabled($activeQuestionCount === 0)
                        class="mt-5 flex min-h-14 w-full items-center justify-center rounded-2xl border-2 border-slate-800 bg-white px-5 py-3 text-base font-black text-slate-900 transition enabled:hover:-translate-y-0.5 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        {{ $activeQuestionCount === 0 ? 'Belum Ada Pertanyaan Aktif' : 'Mulai Main →' }}
                    </button>
                </form>
            </section>

            <p class="mt-5 text-center text-xs leading-5 text-slate-600">
                TensiTwist — permainan edukasi mitos atau fakta.
            </p>
        </main>
    </div>
</x-guest-layout>