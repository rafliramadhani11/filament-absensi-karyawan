<div>
    <x-filament::modal id="showQrCodeModal" close-on-click-outside>
        <x-slot name="header">
            <h3>Preview QR Code</h3>
        </x-slot>
        <img src="{{ $qrCode }}" alt="QR Code" class="w-1/2 mx-auto">
    </x-filament::modal>
</div>
