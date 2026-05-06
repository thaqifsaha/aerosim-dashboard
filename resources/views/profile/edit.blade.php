<x-app-layout>
    <x-slot name="header">
        <h2 class="hud-title animate-hud text-xl font-semibold inline-block">
            Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Profile Picture
                    </h2>

                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Upload your profile picture. This will appear on dashboards and pilot profiles.
                    </p><br>

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
                                <div class="w-32 h-32 rounded-full overflow-hidden shadow-md">

                                    @if(auth()->user()->profile_photo)
                                        <img id="profile_preview"
                                            src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                            class="w-full h-full object-cover object-center transition">
                                    @else
                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center shadow-md">
                                            <img id="profile_preview"
                                                src="{{ asset('images/default_user.png') }}"
                                                class="w-20 h-20 object-contain opacity-70 dark:opacity-80 filter grayscale dark:grayscale-0">
                                        </div>
                                    @endif

                                </div>

                                {{-- HOVER OVERLAY --}}
                                <div class="absolute inset-0 rounded-full bg-black/40 opacity-0
                                            group-hover:opacity-100 flex items-center justify-center transition">
                                    <span class="text-white text-xs">Change</span>
                                </div>

                                {{-- MINUS BUTTON --}}
                                <button type="button"
                                    onclick="{{ auth()->user()->profile_photo ? 'deleteSavedPhoto(event)' : 'removeSelectedPhoto(event)' }}"
                                    class="absolute bottom-1 right-1 z-20
                                        w-8 h-8 flex items-center justify-center
                                        rounded-full bg-red-600 hover:bg-red-700
                                        text-white text-lg font-bold shadow-md transition">
                                    −
                                </button>
                            </div>
                        </div>

                        {{-- SAVE BUTTON --}}
                        @if (session('status') === 'profile-photo-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="mt-3 text-sm text-gray-600 dark:text-gray-400 ml-[42px]"
                            >
                                {{ __('Saved.') }}
                            </p>
                        @endif                      
                        
                        @if (session('status') === 'profile-photo-deleted')
                            <p x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="mt-3 text-sm text-gray-600 dark:text-gray-400"
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

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
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

