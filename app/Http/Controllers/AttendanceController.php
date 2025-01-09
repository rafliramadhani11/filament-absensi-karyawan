<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function absenDatang(Request $request)
    {
        if (! $request->all()) {
            abort(403, 'hmmmmmm, wht r u doin');
        }

        $currentInterval = Carbon::now()->startOfMinute()->floorMinutes(10)->format('H:i');
        $currentDate = Carbon::now()->format('Y-m-d');

        $latestAbsenHadir = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', $currentDate)
            ->whereNotNull('absen_datang')
            ->first();

        if ($request->userId != Auth::id()) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'absen-gagal');
        }

        if ($request->time !== $currentInterval) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'code-kadaluarsa');
        }

        if ($latestAbsenHadir) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'absen-already');
        }

        Attendance::create([
            'user_id' => $request->userId,
            'absen_datang' => $request->time,
            'status' => 'proses',
        ]);

        return redirect()
            ->route('filament.admin.pages.absensi')
            ->with('status', 'absen-updated');
    }

    public function absenPulang(Request $request)
    {
        if (! $request->all()) {
            abort(403, 'hmmmmmm, wht r u doin');
        }

        $currentInterval = Carbon::now()
            ->startOfMinute()
            ->floorMinutes(10)->format('H:i');

        $latestAbsenPulang = Attendance::where('user_id', Auth::id())
            ->whereDate('created_at', now()->toDateString())
            ->whereNotNull('absen_pulang')
            ->value('absen_pulang');
        // dd($latestAbsenPulang, $request, $currentInterval);
        if ($request->userId != Auth::id()) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'absen-gagal');
        }

        if ($request->time !== $currentInterval) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'code-kadaluarsa');
        }

        if ($latestAbsenPulang) {
            return redirect()
                ->route('filament.admin.pages.absensi')
                ->with('status', 'absen-already');
        }

        Attendance::updateOrCreate(
            ['user_id' => $request->userId, 'absen_pulang' => null],
            [
                'absen_pulang' => $request->time,
                'status' => 'hadir',
            ]
        );

        return redirect()
            ->route('filament.admin.pages.absensi')
            ->with('status', 'absen-updated');

    }
}
