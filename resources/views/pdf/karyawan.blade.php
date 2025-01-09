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
            <h2 class="mt-4 text-2xl font-bold">Data Karyawan</h2>
            <p class="mt-2 text-sm">{{ now()->translatedFormat('l, j F Y') }}</p>
        </div>

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full mx-auto border border-collapse border-gray-300">
                <thead>
                    <tr class="text-sm font-medium text-left bg-gray-100">
                        <th class="px-4 py-2 border border-gray-300">No</th>
                        <th class="px-4 py-2 border border-gray-300">NIK</th>
                        <th class="px-4 py-2 border border-gray-300">Nama Karyawan</th>
                        <th class="px-4 py-2 border border-gray-300">Jenis Kelamin</th>
                        <th class="px-4 py-2 border border-gray-300">Alamat</th>
                        <th class="px-4 py-2 border border-gray-300">Divisi</th>
                        <th class="px-4 py-2 border border-gray-300">Jabatan</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-4 py-2 text-center border border-gray-300">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->nik }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->name }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->gender }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->address }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->division->name }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->role }}</td>
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
