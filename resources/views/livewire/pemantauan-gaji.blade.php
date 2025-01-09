{{-- <div>
    <h2>Gaji Bulanan</h2>
    @foreach ($hadirs as $bulan => $data)
        <h3>{{ $bulan }}</h3>
        <p>Total Kehadiran: {{ $data['totalData'] }}</p>
        <p>Total Alfa: {{ $data['totalAlfa'] }}</p>
        <p>Total Izin: {{ $data['totalIzin'] }}</p>
        <p>Total Jam Lembur: {{ $data['totalJamLembur'] }}</p>
        <p>Gaji Pokok: Rp {{ number_format($data['gajiPokok'], 0, ',', '.') }}</p>
        <p>Bayaran Lembur: Rp {{ number_format($data['bayaranLembur'], 0, ',', '.') }}</p>
        <p>Potongan: Rp {{ number_format($data['potongan'], 0, ',', '.') }}</p>
        <p>Total Gaji: Rp {{ number_format($data['totalGaji'], 0, ',', '.') }}</p>
    @endforeach
</div> --}}

<div>
    <div class="overflow-x-auto bg-white border border-gray-200 rounded-lg ">
        <table class="w-full">
            <thead>
                <tr class="text-sm leading-normal text-gray-700 uppercase bg-gray-100">
                    <th class="px-6 py-3 text-left">Bulan</th>
                    <th class="px-6 py-3 text-left">Total Kehadiran</th>
                    <th class="px-6 py-3 text-left">Total Izin</th>
                    <th class="px-6 py-3 text-left">Total Tidak Hadir</th>
                    <th class="px-6 py-3 text-left">Gaji Pokok</th>
                    <th class="px-6 py-3 text-left">Potongan</th>
                    <th class="px-6 py-3 text-left">Total Gaji</th>
                    <th class="px-6 py-3 text-left"></th>
                </tr>
            </thead>
            <tbody class="text-sm font-light text-gray-600">
                @foreach ($hadirs as $bulan => $data)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="px-6 py-3 text-left">{{ $bulan }}</td>
                        <td class="px-6 py-3 text-left">{{ $data['totalData'] }}</td>
                        <td class="px-6 py-3 text-left">{{ $data['totalIzin'] }}</td>
                        <td class="px-6 py-3 text-left">{{ $data['totalAlfa'] }}</td>
                        <td class="px-6 py-3 text-left">
                            Rp {{ number_format($data['gajiPokok'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            Rp {{ number_format($data['potongan'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            Rp {{ number_format($data['totalGaji'], 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-3 text-left">
                            <a href="{{ route('pdf.gaji.download', [
                                'bulan' => $bulan,
                                'totalData' => $data['totalData'],
                                'totalIzin' => $data['totalIzin'],
                                'totalAlfa' => $data['totalAlfa'],
                                'gajiPokok' => $data['gajiPokok'],
                                'potongan' => $data['potongan'],
                                'totalGaji' => $data['totalGaji'],
                            ]) }}"
                                class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                @svg('download')
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
