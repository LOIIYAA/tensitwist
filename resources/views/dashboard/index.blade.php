<x-app-layout>
    <div class="min-h-screen bg-[#EAF1FC] py-6 sm:py-8">
        <div class="mx-auto w-full max-w-6xl px-4 sm:px-6 lg:px-8">

            <section class="rounded-[28px] border-2 border-slate-800 bg-white p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)] sm:p-7">
                <p class="text-sm font-medium text-slate-600">
                    Selamat datang kembali
                </p>

                <div class="mt-1 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <h1 class="text-3xl font-black tracking-tight text-slate-900 sm:text-4xl">
                            Halo, {{ Auth::user()->name }}! 👋
                        </h1>

                        <p class="mt-2 max-w-xl text-sm leading-6 text-slate-600 sm:text-base">
                            Buat, atur, dan bagikan permainan Mitos atau Fakta bertema hipertensi.
                        </p>
                    </div>

                    <a
                        href="{{ route('projects.create') }}"
                        class="inline-flex min-h-12 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#4DA8E8] px-5 py-3 text-sm font-extrabold text-slate-900 transition hover:-translate-y-0.5 hover:bg-[#3298dd]"
                    >
                        + Buat Project
                    </a>
                </div>
            </section>

            @if (session('success'))
                <div class="mt-5 rounded-2xl border-2 border-slate-800 bg-[#A8EACD] px-4 py-3 text-sm font-bold text-slate-900">
                    {{ session('success') }}
                </div>
            @endif

            <section class="mt-7">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-black text-slate-900 sm:text-2xl">
                        Project Saya
                    </h2>

                    <span class="rounded-full border-2 border-slate-800 bg-[#F9D978] px-3 py-1 text-xs font-extrabold text-slate-900">
                        {{ $projects->count() }} Project
                    </span>
                </div>

                @if ($projects->isEmpty())
                    <div class="mt-4 rounded-[28px] border-2 border-dashed border-slate-500 bg-white p-7 text-center sm:p-10">
                        <div class="text-5xl">🩺</div>

                        <h3 class="mt-4 text-xl font-black text-slate-900">
                            Belum ada project
                        </h3>

                        <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-600">
                            Mulai buat project spinner untuk mengajak pemain belajar tentang mitos dan fakta hipertensi.
                        </p>

                        <a
                            href="{{ route('projects.create') }}"
                            class="mt-5 inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#93E5F2] px-5 py-2.5 text-sm font-extrabold text-slate-900"
                        >
                            Buat Project Pertama
                        </a>
                    </div>
                @else
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                        @foreach ($projects as $project)
                            <article
                                class="rounded-[28px] border-2 border-slate-800 p-5 shadow-[0_8px_18px_rgba(44,65,100,0.12)]"
                                style="background-color: {{ $project->theme_color }};"
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <span class="rounded-full border-2 border-slate-800 bg-white px-3 py-1 text-xs font-extrabold uppercase text-slate-900">
                                        {{ $project->status }}
                                    </span>

                                    <span class="text-2xl">🌀</span>
                                </div>

                                <h3 class="mt-5 text-xl font-black leading-tight text-slate-900">
                                    {{ $project->title }}
                                </h3>

                                <p class="mt-2 min-h-12 text-sm leading-6 text-slate-700">
                                    {{ $project->description ?: 'Belum ada deskripsi untuk project ini.' }}
                                </p>

                                <div class="mt-5 rounded-2xl border-2 border-slate-800 bg-white/80 px-4 py-3">
                                    <p class="text-xs font-semibold text-slate-600">
                                        Total pertanyaan
                                    </p>

                                    <p class="mt-1 text-2xl font-black text-slate-900">
                                        {{ $project->questions_count }}
                                    </p>
                                </div>

                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <a
                                        href="{{ route('projects.show', $project) }}"
                                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-white px-3 py-2 text-sm font-extrabold text-slate-900"
                                    >
                                        Kelola
                                    </a>

                                    <a
                                        href="{{ route('projects.edit', $project) }}"
                                        class="inline-flex min-h-11 items-center justify-center rounded-2xl border-2 border-slate-800 bg-[#F9D978] px-3 py-2 text-sm font-extrabold text-slate-900"
                                    >
                                        Edit
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </section>
        </div>
    </div>
</x-app-layout>