<?php

namespace App\Livewire;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PemantauanGaji extends Component implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $hadirs;

    public function mount()
    {
        $user = Auth::user();
        $this->hadirs = Attendance::where('user_id', $user->id)
            ->get()
            ->groupBy(function ($attendance) {
                return Carbon::parse($attendance->created_at)->translatedFormat('F Y');
            })
            ->map(function ($group) {
                $statusCounts = $group->pluck('status')->countBy();
                $alfaIzinCount = $statusCounts->only(['tidak hadir', 'izin'])->sum();

                $totalJamLembur = 0;
                foreach ($group as $attendance) {
                    $waktuPulangCarbon = Carbon::parse($attendance->absen_datang);
                    if ($waktuPulangCarbon->greaterThanOrEqualTo($waktuPulangCarbon->copy()->hour(17))) {
                        $totalJamLembur += abs(intval($waktuPulangCarbon->diffInHours($waktuPulangCarbon->copy()->hour(17))));
                    }
                }

                $totalIzin = $group->pluck('status')->filter(fn ($status) => $status === 'izin')->count();
                $totalAlfa = $group->pluck('status')->filter(fn ($status) => $status === 'tidak hadir')->count();

                $potonganIzin = $totalIzin * 50000;
                $potonganAlfa = $totalAlfa * 100000;
                $gajiPokok = $group->count() * 200000;
                $bayaranLembur = $totalJamLembur * 25000;
                $potongan = $potonganIzin + $potonganAlfa;

                return [
                    'totalData' => $group->count(),
                    'totalAlfa' => $totalAlfa,
                    'totalIzin' => $totalIzin,
                    'alfaIzinCount' => $alfaIzinCount,
                    'totalJamLembur' => $totalJamLembur,
                    'gajiPokok' => $gajiPokok,
                    'bayaranLembur' => $bayaranLembur,
                    'potongan' => $potongan,
                    'totalGaji' => $gajiPokok - $potongan,
                ];
            });
    }

    // public function downloadPdf(): Action
    // {
    //     return Action::make('Generate Pdf')
    //         ->iconButton()
    //         ->icon('heroicon-o-arrow-down-tray')
    //         ->url(
    //             fn() => route('pdf.division.karyawan.download', ['bulan' => ])
    //         );
    //     // ->url(
    //     //     fn (Model $record) => route('pdf.division.karyawan.download', ['division' => $record->id])
    //     // );
    // }

    public function render()
    {
        return view('livewire.pemantauan-gaji');
    }
}
