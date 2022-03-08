<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Jet') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('update-profile-information-form')
            <x-section-border/>
            @livewire('profile.update-password-form')
            <x-section-border/>
        </div>
    </div>
</x-app-layout>
