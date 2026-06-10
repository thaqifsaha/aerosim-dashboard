<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-['Montserrat'] text-xl font-bold tracking-wide text-slate-800 dark:text-white">
                Contact / About
            </h2>
            <a href="{{ url()->previous() }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                    bg-slate-100 hover:bg-slate-200 text-slate-700
                    dark:bg-white/5 dark:hover:bg-white/10 dark:text-slate-300
                    border border-slate-200 dark:border-white/10 transition cursor-pointer">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-5">

            {{-- Brand header card --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-5 flex items-center gap-4">
                    <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-cyan-500/10 dark:bg-cyan-500/10 border border-cyan-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-['Montserrat'] text-lg font-bold text-slate-800 dark:text-white tracking-wide">aerosim.MY</h3>
                        <p class="text-xs font-medium text-slate-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Flight Simulation Experience Centre</p>
                    </div>
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Location</h4>
                </div>
                <div class="overflow-hidden">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3985.234886228174!2d101.7662007!3d2.7466173!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xaf14386885bb5531%3A0x3757508407b34bd6!2sAerosim.my!5e0!3m2!1sen!2smy!4v1779206865265!5m2!1sen!2smy"
                        width="100%"
                        height="100%"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="block w-full h-64 md:h-80">
                    </iframe>
                </div>
            </div>

            {{-- Contact details --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h4 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Contact Information</h4>
                </div>

                <div class="px-6 py-6 grid grid-cols-1 gap-6 md:grid-cols-3">

                    {{-- Address --}}
                    <div class="flex gap-3">
                        <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center border border-slate-200 dark:border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Address</p>
                            <p class="text-sm text-slate-700 dark:text-slate-300 leading-relaxed">
                                17-1, Jalan Timur 6/2B,<br>
                                Bandar Baru Enstek,<br>
                                Nilai, Negeri Sembilan, Malaysia
                            </p>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="flex gap-3">
                        <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center border border-slate-200 dark:border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-1.5">Contact Email</p>
                            <a href="mailto:admin@aerosim.my"
                                class="text-sm text-cyan-600 hover:text-cyan-500 dark:text-cyan-400 dark:hover:text-cyan-300 transition cursor-pointer">
                                admin@aerosim.my
                            </a>
                        </div>
                    </div>

                    {{-- Social --}}
                    <div class="flex gap-3">
                        <div class="mt-0.5 flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center border border-slate-200 dark:border-white/10">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-slate-400 dark:text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 mb-3">Social</p>
                            <div class="mt-3 flex items-center gap-4">
                            <a href="https://wa.link/2r1mnr" target="_blank" rel="noopener noreferrer"
                                class="flex h-8 w-8 items-center justify-center transition hover:scale-105 hover:opacity-80">
                                <img src="{{ asset('images/whatsapp.png') }}" alt="WhatsApp" class="h-7 w-7 object-contain">
                            </a>
                            <a href="https://www.facebook.com/aerosim.my" target="_blank" rel="noopener noreferrer"
                                class="flex h-14 w-14 items-center justify-center transition hover:scale-105 hover:opacity-80">
                                <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="h-11 w-11 object-contain">
                            </a>
                            <a href="https://www.instagram.com/aerosim.my" target="_blank" rel="noopener noreferrer"
                                class="flex h-10 w-10 items-center justify-center transition hover:scale-105 hover:opacity-80">
                                <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="h-8 w-8 object-contain">
                            </a>
                        </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Disclaimer --}}
            <div class="rounded-xl border border-amber-200/60 bg-amber-50/80 dark:border-amber-500/15 dark:bg-amber-950/20 backdrop-blur-sm px-5 py-4 flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-500 dark:text-amber-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-xs text-amber-700 dark:text-amber-300 leading-relaxed">
                    <span class="font-bold">Disclaimer:</span>
                    aerosim.my is not an Approved Training Organization (ATO). This website is not recognized or approved as a training organization by any regulatory authority, government agency, or accrediting body. The information provided on this website is for informational purposes only and should not be considered official training or certification.
                </p>
            </div>

        </div>
    </div>
</x-app-layout>
