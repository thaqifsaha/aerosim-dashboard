<section class="space-y-5">
    <p class="text-sm text-slate-500 dark:text-slate-400">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="cursor-pointer inline-flex items-center gap-2 px-5 py-2.5 rounded-lg
            text-xs font-bold uppercase tracking-wider
            bg-red-600 hover:bg-red-500 text-white
            shadow-md shadow-red-500/20 hover:shadow-red-400/30
            transition-all duration-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        {{ __('Delete Account') }}
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-base font-bold text-slate-800 dark:text-white">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-5">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password"
                    class="w-3/4 rounded-lg border border-slate-200 dark:border-white/10
                        bg-white/80 dark:bg-white/5
                        text-slate-800 dark:text-slate-200
                        px-3 py-2.5 text-sm
                        focus:outline-none focus:ring-2 focus:ring-red-500/40 focus:border-red-400
                        transition"
                    placeholder="{{ __('Password') }}">
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-5 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-lg
                        text-xs font-bold uppercase tracking-wider
                        bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-white/10
                        text-slate-700 dark:text-slate-300
                        border border-slate-200 dark:border-white/10
                        transition-all duration-200">
                    {{ __('Cancel') }}
                </button>
                <button type="submit"
                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-lg
                        text-xs font-bold uppercase tracking-wider
                        bg-red-600 hover:bg-red-500 text-white
                        shadow-md shadow-red-500/20
                        transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
