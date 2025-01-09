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
                Laporan Divisi
                {{ $division->name }}
            </h2>
        </div>

        {{-- <div class="flex items-start justify-between mb-8">
            <table>
                <tbody>
                    <tr>
                        <td>NIK</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->nik }}</td>
                    </tr>
                    <tr>
                        <td>Nama Karyawan</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->birth_date }}</td>
                    </tr>
                    <tr>
                        <td>Jenis Kelamin</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->gender }}</td>
                    </tr>
                    <tr>
                        <td>No Handphone</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <td>Alamat Tinggal</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->address }}</td>
                    </tr>
                </tbody>
            </table>

            <table>
                <tbody>
                    <tr>
                        <td>Divisi</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->division->name }}</td>
                    </tr>
                    <tr>
                        <td>Role</td>
                        <td class="px-5"> : </td>
                        <td>{{ $user->role }}</td>
                    </tr>
                </tbody>
            </table>
        </div> --}}

        <!-- Table -->
        <div class="relative overflow-x-auto">
            <table class="min-w-full mx-auto border border-collapse border-gray-300">
                <thead>
                    <tr class="text-sm font-medium text-left bg-gray-100">
                        <th class="px-2 py-2 text-center border border-gray-300 ">No.</th>
                        <th class="px-4 py-2 border border-gray-300">Nama Karywan</th>
                        <th class="px-4 py-2 border border-gray-300">Tanggal Lahir</th>
                        <th class="px-4 py-2 border border-gray-300">Jenis Kelamin</th>
                        <th class="px-4 py-2 text-center border border-gray-300">Alamat Tinggal</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($division->users as $user)
                        <tr>
                            <td class="px-4 py-2 text-center border border-gray-300 ">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-4 py-2 font-medium border border-gray-300">
                                <span class="block text-sm">{{ $user->name }}</span>
                                <span class="block text-xs">{{ $user->email }}</span>
                            </td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->birth_date }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300">{{ $user->gender }}</td>
                            <td class="px-4 py-2 font-medium border border-gray-300 ">
                                {{ $user->address }}
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
