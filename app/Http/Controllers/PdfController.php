<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class PdfController extends Controller
{
    public function karyawan()
    {
        $users = User::where('is_admin', 'false')
            ->where('is_hrd', false)
            ->get();

        return pdf()->view('pdf.karyawan', compact('users'))
        // return view('pdf.karyawan', compact('users'));
            ->name('karyawan '.now()->month().'.pdf')
            ->download();
    }

    public function absensiThisMonth()
    {
        $attendances = Attendance::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        return pdf()->view('pdf.absensi-thisMonth', compact('attendances'))
            ->name('Absensi-'.now()->translatedFormat('F').'.pdf')
            ->download();
    }

    public function absensiThisDay()
    {
        $attendances = Attendance::whereDate('created_at', now()->toDateString())
            ->get();

        return pdf()->view('pdf.absensi-thisDay', compact('attendances'))
            ->name('Absensi Hari '.now()->translatedFormat('l').'.pdf')
            ->download();
    }

    public function karyawanAbsensi(User $user)
    {

        // return view('pdf.karyawan-absensi', compact('user'));
        return pdf()->view('pdf.karyawan-absensi', compact('user'))
            ->name('Data & Absensi '.$user->name.'.pdf')
            ->download();
    }

    public function divisionKaryawan(Division $division)
    {
        // return view('pdf.division-karyawan', compact('division'));
        return pdf()->view('pdf.division-karyawan', compact('division'))
            ->name('Divisi '.$division->name.'.pdf')
            ->download();
    }

    public function gaji(Request $request)
    {
        $bulan = $request->input('bulan');
        $totalData = $request->input('totalData');
        $totalIzin = $request->input('totalIzin');
        $totalAlfa = $request->input('totalAlfa');
        $gajiPokok = $request->input('gajiPokok');
        $potongan = $request->input('potongan');
        $totalGaji = $request->input('totalGaji');

        // Lakukan pemrosesan atau generate PDF
        $data = [
            'bulan' => $bulan,
            'totalData' => $totalData,
            'totalIzin' => $totalIzin,
            'totalAlfa' => $totalAlfa,
            'gajiPokok' => $gajiPokok,
            'potongan' => $potongan,
            'totalGaji' => $totalGaji,
        ];

        return pdf()->view('pdf.gaji', compact('data'))
            ->name('Gaji Bulan '.$data['bulan'].'.pdf')
            ->download();
    }
}
