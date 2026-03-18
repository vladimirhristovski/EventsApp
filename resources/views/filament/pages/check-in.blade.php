<x-filament-panels::page>
    <x-filament::section>
        <form wire:submit="checkIn">
            {{ $this->form }}
            <br>
            <x-filament::button type="submit" class="mt-4">
                Check In
            </x-filament::button>
        </form>
    </x-filament::section>

    @if ($message)
        <x-filament::section>
            <div
                style="padding: 16px; border-radius: 8px; background-color: {{ $success ? '#d1fae5' : '#fee2e2' }}; color: {{ $success ? '#065f46' : '#991b1b' }}; font-size: 1.1rem;">
                {{ $message }}
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
