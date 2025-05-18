<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $bracket ? $bracket->name . ' Leaderboard' : 'Mythic+ Leaderboard' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <livewire:leaderboard :bracket="$bracket" />
    </div>
</x-app-layout>
