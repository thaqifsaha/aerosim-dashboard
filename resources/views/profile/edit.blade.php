<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-cyan-500/10 border border-cyan-500/30">
                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <h2 class="font-['Montserrat'] text-xl font-bold tracking-wide text-slate-800 dark:text-white">
                Profile
            </h2>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Profile Picture --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Profile Picture</h3>
                </div>
                <div class="px-6 py-6">
                    <div class="max-w-xl">
                        <p class="mb-5 text-sm text-slate-500 dark:text-slate-400">
                            Upload your profile picture. This will appear on dashboards and pilot profiles.
                        </p>

                        {{-- FORM --}}
                        <form method="POST" action="{{ route('profile.photo.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- HIDDEN FILE INPUT --}}
                            <input type="file" id="profile_photo_input" name="profile_photo" accept="image/*" class="hidden">

                            {{-- CLICKABLE IMAGE --}}
                            <div class="relative w-32 h-32 group">

                                {{-- CLICK AREA --}}
                                <div onclick="document.getElementById('profile_photo_input').click()"
                                    class="cursor-pointer group relative w-32 h-32">

                                    {{-- OUTER CIRCLE FRAME --}}
                                    <div class="w-32 h-32 rounded-full overflow-hidden shadow-md ring-2 ring-slate-200 dark:ring-white/10">

                                        @if(auth()->user()->profile_photo_url)
                                            <img id="profile_preview"
                                                src="{{ auth()->user()->profile_photo_url }}"
                                                class="w-full h-full object-cover object-center transition">
                                        @else
                                            <div class="w-full h-full bg-slate-100 dark:bg-white/5 flex items-center justify-center shadow-md">
                                                <img id="profile_preview"
                                                    src="{{ asset('images/default_user.png') }}"
                                                    class="w-20 h-20 object-contain opacity-70 dark:opacity-80 filter grayscale dark:grayscale-0">
                                            </div>
                                        @endif

                                    </div>

                                    {{-- HOVER OVERLAY --}}
                                    <div class="absolute inset-0 rounded-full bg-black/40 opacity-0
                                                group-hover:opacity-100 flex items-center justify-center transition">
                                        <span class="text-white text-xs font-semibold">Change</span>
                                    </div>

                                    {{-- MINUS BUTTON --}}
                                    <button type="button"
                                        onclick="{{ auth()->user()->profile_photo_url ? 'deleteSavedPhoto(event)' : 'removeSelectedPhoto(event)' }}"
                                        class="cursor-pointer absolute bottom-1 right-1 z-20
                                            w-7 h-7 flex items-center justify-center
                                            rounded-full bg-red-500 hover:bg-red-600
                                            text-white text-base font-bold shadow-md transition">
                                        −
                                    </button>
                                </div>
                            </div>

                            {{-- STATUS MESSAGES --}}
                            @if (session('status') === 'profile-photo-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="mt-3 text-xs font-semibold text-emerald-600 dark:text-emerald-400"
                                >
                                    {{ __('Saved.') }}
                                </p>
                            @endif

                            @if (session('status') === 'profile-photo-deleted')
                                <p x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="mt-3 text-xs font-semibold text-slate-500 dark:text-slate-400"
                                >
                                    {{ __('Profile picture removed.') }}
                                </p>
                            @endif
                        </form>
                        <form id="delete_photo_form" method="POST" action="{{ route('profile.photo.delete') }}">
                            @csrf
                            @method('DELETE')
                        </form>
                    </div>
                </div>
            </div>

            {{-- Profile Information --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Profile Information</h3>
                </div>
                <div class="px-6 py-6 max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Update Password --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-cyan-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Update Password</h3>
                </div>
                <div class="px-6 py-6 max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Delete Account --}}
            <div class="rounded-xl bg-white/70 dark:bg-white/5 backdrop-blur-md border border-slate-200/80 dark:border-white/10 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 dark:border-white/10 flex items-center gap-2">
                    <div class="w-1 h-5 bg-red-400 rounded-full"></div>
                    <h3 class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Delete Account</h3>
                </div>
                <div class="px-6 py-6 max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>

<script>
document.getElementById('profile_photo_input').addEventListener('change', function(event) {
    const file = event.target.files[0];

    if (file) {
        const preview = document.getElementById('profile_preview');
        preview.src = URL.createObjectURL(file);

        // Auto-save after choosing picture
        this.closest('form').submit();
    }
});
</script>
<script>
function removeSelectedPhoto(event) {
    event.stopPropagation();

    const input = document.getElementById('profile_photo_input');
    const preview = document.getElementById('profile_preview');

    input.value = '';
    preview.src = "{{ asset('images/default_user.png') }}";
}

function deleteSavedPhoto(event) {
    event.stopPropagation();
    document.getElementById('delete_photo_form').submit();
}
</script>
</x-app-layout>
