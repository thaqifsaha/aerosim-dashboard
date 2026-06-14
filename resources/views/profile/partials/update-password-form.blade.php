<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('Current Password') }}
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('New Password') }}
            </label>
            <input id="update_password_password" name="password" type="password"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="mb-1.5 block text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">
                {{ __('Confirm Password') }}
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full rounded-lg border border-slate-200 dark:border-white/10
                    bg-white/80 dark:bg-white/5
                    text-slate-800 dark:text-slate-200
                    px-3 py-2.5 text-sm
                    focus:outline-none focus:ring-2 focus:ring-cyan-500/40 focus:border-cyan-400
                    transition"
                autocomplete="new-password">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
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

            @if (session('status') === 'password-updated')
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
