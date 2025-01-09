<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="relative flex items-center justify-center bg-white">
    <div class="w-full max-w-5xl p-8 ">
        <!-- Header -->
        <div class="mb-8 text-center">
            <div class="absolute top-10 left-10">
                <img src="{{ asset('img/logo-pdf.jpeg') }}" class="h-auto size-32">
            </div>
            <h1 class="text-6xl font-bold uppercase">PT Birdie</h1>
            <h2 class="mt-4 text-2xl font-bold">
                Laporan Absensi Tanggal
                {{ now()->translatedFormat('d/m/Y') }}
            </h2>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full mx-auto border border-collapse border-gray-300">
                <thead>
                    <tr class="text-sm font-medium text-left bg-gray-100">
                        <th class="px-2 py-2 text-center border border-gray-300 ">No</th>
                        <th class="px-4 py-2 border border-gray-300">Nama Karyawan</th>
                        <th class="px-4 py-2 border border-gray-300">Absen Datang</th>
                        <th class="px-4 py-2 border border-gray-300">Absen Pulang</th>
                        <th class="px-4 py-2 border border-gray-300">Alasan</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Status Kehadiran</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($attendances as $a)
                        <tr>
                            <td class="px-2 py-2 text-center border border-gray-300 ">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $a->user->name }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $a->absen_datang }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $a->absen_pulang }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $a->alasan ?? '-' }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300 ">
                                @if ($a->status === 'hadir')
                                    <span
                                        class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded ">Hadir</span>
                                @elseif ($a->status === 'izin')
                                    <span
                                        class="bg-yellow-100 text-yellow-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded ">Izin</span>
                                @else
                                    <span
                                        class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded ">Tidak
                                        Hadir</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>


        </div>

        <!-- Footer -->
        <div class="flex items-center justify-end mt-8">
            <div class="">
                <img src="{{ asset('img/stempel-logo.jpeg') }}" class="h-auto size-32">
            </div>
        </div>
    </div>
</body>

</html>
