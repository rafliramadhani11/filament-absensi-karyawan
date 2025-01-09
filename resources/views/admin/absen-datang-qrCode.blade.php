<div class="py-5">
    {{ QrCode::size(90)->generate(
        route('karyawan.absen-harian.absenDatang', [
            'userId' => $getRecord()->id,
            'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(10)->format('H:i'),
        ]),
    ) }}
</div>
