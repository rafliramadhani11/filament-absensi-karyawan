<x-filament-panels::page>
    <x-filament::tabs label="Content tabs">
        <x-filament::tabs.item>
            Semua Bulan
        </x-filament::tabs.item>
        <x-filament::tabs.item>
            Bulan Ini
        </x-filament::tabs.item>
    </x-filament::tabs>

    {{ $this->table }}
</x-filament-panels::page>
