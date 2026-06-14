<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('Name') }}
            </label>
            <input id="name" name="name" type="text"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('Email') }}
            </label>
            <input id="email" name="email" type="email"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                value="{{ old('email', $user->email) }}" required autocomplete="username">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-600 dark:text-slate-400">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-cyan-200 rounded focus:outline-none transition">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-semibold text-sm text-emerald-600 dark:text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <label for="phone_number" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('Phone Number') }}
            </label>
            <input id="phone_number" name="phone_number" type="text"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                value="{{ old('phone_number', $user->phone_number) }}"
                placeholder="Ex: 0123456789"
                autocomplete="tel">
            <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit"
                class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
                    text-xs font-bold uppercase tracking-wider
                    bg-cyan-500 hover:bg-cyan-400 text-white
                    shadow-md shadow-cyan-500/20 hover:shadow-cyan-400/30
                    transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
