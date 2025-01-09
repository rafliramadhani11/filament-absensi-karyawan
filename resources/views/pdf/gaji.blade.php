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
            <h2 class="mt-4 text-2xl font-bold">{{ Auth::user()->name }}</h2>
            <p class="mt-2 text-sm font-medium">Gaji Bulan {{ $data['bulan'] }}</p>
        </div>

        <div class="flex items-start justify-between mb-8">
            <table>
                <tbody>
                    <tr>
                        <td>Gaji Kehadiran</td>
                        <td class="px-5"> : </td>
                        <td>Rp 200.000</td>
                    </tr>
                    <tr>
                        <td>Potongan Izin</td>
                        <td class="px-5"> : </td>
                        <td>Rp 50.000</td>
                    </tr>
                    <tr>
                        <td>Potongan Tidak Hadir</td>
                        <td class="px-5"> : </td>
                        <td>Rp 100.000</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full mx-auto border border-collapse border-gray-300">
                <thead>
                    <tr class="text-sm font-medium text-left bg-gray-100">
                        <th class="px-4 py-2 border border-gray-300">Total Hadir</th>
                        <th class="px-4 py-2 border border-gray-300">Total Izin</th>
                        <th class="px-4 py-2 border border-gray-300">Total Tidak Hadir</th>
                        <th class="px-4 py-2 border border-gray-300">Gaji Pokok</th>
                        <th class="px-4 py-2 border border-gray-300">Potongan</th>
                        <th class="px-4 py-2 border border-gray-300">Total Gaji</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    <tr>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            {{ $data['totalData'] }} Hari
                        </td>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            {{ $data['totalIzin'] }} Hari
                        </td>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            {{ $data['totalAlfa'] }} Hari
                        </td>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            Rp {{ number_format($data['gajiPokok'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            Rp {{ number_format($data['potongan'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-2 font-medium border border-gray-300">
                            Rp {{ number_format($data['totalGaji'], 0, ',', '.') }}
                        </td>
                    </tr>
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
