<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="hud-title animate-hud text-xl font-semibold">
                Contact / About
            </h2>

            <a href="{{ url()->previous() }}"
                class="px-4 py-2 text-sm font-medium tracking-wide bg-gray-200 hover:bg-gray-300 text-gray-800 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-white rounded transition">
                ← Back
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/80 dark:bg-white/5 backdrop-blur-md border border-white/40 dark:border-white/10 shadow-lg sm:rounded-xl p-6 transition">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">aerosim.MY</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Flight simulation experience centre</p>
                </div>

                <div class="mt-6 rounded-xl overflow-hidden border border-white/40 dark:border-white/10">
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

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-3">
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Address</p>
                        <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                            17-1, Jalan Timur 6/2B,<br>
                            Bandar Baru Enstek,<br>
                            Nilai, Negeri Sembilan, Malaysia
                        </p>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Contact email</p>
                        <a href="mailto:admin@aerosim.my"
                            class="mt-2 inline-block text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                            admin@aerosim.my
                        </a>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">Social</p>
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

                <div class="mt-6 rounded-lg border border-gray-200 bg-gray-50 p-4 text-sm leading-relaxed text-gray-600 dark:border-gray-700 dark:bg-gray-800/70 dark:text-gray-300">
                    Disclaimer: aerosim.my is not an Approved Training Organization (ATO). This website is not recognized or approved as a training organization by any regulatory authority, government agency, or accrediting body. The information provided on this website is for informational purposes only and should not be considered official training or certification.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
