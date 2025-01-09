<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div>
        {{ QrCode::size(200)->generate(
            route('karyawan.absen-harian.absenPulang', [
                'userId' => $getRecord()->id,
                'time' => Carbon\Carbon::now()->startOfMinute()->floorMinutes(10)->format('H:i'),
            ]),
        ) }}
    </div>

</x-dynamic-component>
