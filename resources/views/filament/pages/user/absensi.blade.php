<x-filament-panels::page>
    {{-- Scan Qr --}}
    <div wire:ignore>
        <div id="reader" width="10px" class="md:w-1/2 md:translate-x-1/2"></div>
    </div>

    {{-- Session --}}
    @if (session('status') === 'absen-gagal')
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            <span class="font-medium">Qr Code Tidak Sesuai !</span>
        </div>
    @endif
    @if (session('status') === 'code-kadaluarsa')
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"
            x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            <span class="font-medium">Qr Code Sudah Kedaluwarsa.</span>
        </div>
    @endif
    @if (session('status') === 'absen-already')
        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
            role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            <span class="font-medium">Anda Sudah Melakukan Absensi .</span>
        </div>
    @endif
    @if (session('status') === 'absen-updated')
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
            <span class="font-medium">Berhasil Melakukan Absensi .</span>
        </div>
    @endif
    {{-- ----------------------------------------------------------------- --}}

    {{-- Table --}}
    {{ $this->table }}

    {{-- Script --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            window.location.href = decodedText;
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 300,
                    height: 300
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>
    {{-- <script>
        let isScanned = false; // Flag untuk mengontrol pemindaian
        let html5QrCode;

        function onScanSuccess(decodedText, decodedResult) {
            if (!isScanned) {
                isScanned = true; // Set flag menjadi true untuk mencegah pemindaian ulang

                // Hentikan scanner
                html5QrCode.stop().then(() => {
                    console.log("Scanner dihentikan.");
                    // Redirect ke URL dari QR Code
                    window.location.href = decodedText;
                }).catch((err) => {
                    console.error("Gagal menghentikan scanner: ", err);
                });
            }
        }

        function onScanFailure(error) {
            // console.warn(`Code scan error = ${error}`);
        }

        // Inisialisasi scanner
        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", {
                fps: 10,
                qrbox: {
                    width: 300,
                    height: 300
                }
            },
            /* verbose= */
            false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script> --}}

</x-filament-panels::page>
