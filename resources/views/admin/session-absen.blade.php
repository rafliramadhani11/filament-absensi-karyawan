@if (session('status') === 'absen-gagal')
    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert"
        x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
        <span class="font-medium">Qr Code Tidak Sesuai !</span>
    </div>
@endif

@if (session('status') === 'code-kadaluarsa')
    <div class="p-4 mb-4 text-2xl text-red-800 rounded-lg bg-yellow-50 " x-data="{ show: true }" x-show="show"
        x-transition x-init="setTimeout(() => show = false, 3000)">
        Qr Code Sudah Kedaluwarsa.
    </div>
@endif

@if (session('status') === 'absen-already')
    <div class="p-4 mb-4 text-2xl text-red-800 rounded-lg bg-yellow-50 " x-data="{ show: true }" x-show="show"
        x-transition x-init="setTimeout(() => show = false, 3000)">
        Anda Sudah Melakukan Absensi .
    </div>
@endif

@if (session('status') === 'absen-updated')
    <div class="p-4 mb-4 text-2xl text-red-800 rounded-lg bg-yellow-50 " x-data="{ show: true }" x-show="show"
        x-transition x-init="setTimeout(() => show = false, 3000)">
        Berhasil Melakukan Absensi .
    </div>
@endif
