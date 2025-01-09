<?php

namespace App\Filament\Resources\DivisionResource\Widgets;

use App\Models\Division;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class DivisionChart extends ChartWidget
{
    protected static ?string $heading = 'Persentase Kehadiran per Divisi';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    protected static ?int $sort = 2;

    protected static string $color = 'primary';

    public ?string $filter = '5';

    public static function canView(): bool
    {
        return Auth::user()->is_hrd;
    }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                y: {
                    ticks: {
                        callback: (value) => value+ '%' ,
                    },
                },
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    JS);
    }

    protected function getFilters(): ?array
    {
        return [
            5 => '5',
            10 => '10',
            15 => '15',
            30 => '30',
            50 => '50',
        ];
    }

    protected function getData(): array
    {
        $currentMonth = now()->month;
        $workingDays = 22; // Jumlah hari kerja per bulan
        $activeFilter = (int) $this->filter; // Mengambil nilai filter aktif

        // Ambil data Division beserta attendances melalui users, dibatasi oleh filter aktif
        $divisions = Division::with(['users.attendances' => function ($query) use ($currentMonth) {
            $query->where('status', 'hadir')
                ->whereMonth('created_at', $currentMonth);
        }])
            ->limit($activeFilter)
            ->get();

        // Hitung persentase kehadiran per divisi
        $attendancePercentages = $divisions->map(function ($division) use ($workingDays) {
            $totalUsers = $division->users->count();

            // Total kehadiran dalam divisi
            $totalAttendances = $division->users->flatMap(function ($user) {
                return $user->attendances;
            })->count();

            // Hitung total kehadiran maksimal
            $maxPossibleAttendances = $totalUsers * $workingDays;

            // Persentase kehadiran
            $attendancePercentage = $maxPossibleAttendances > 0
                ? round(($totalAttendances / $maxPossibleAttendances) * 100, 2)
                : 0;

            return [
                'division_name' => $division->name,
                'attendance_percentage' => $attendancePercentage,
                'total_attendances' => $totalAttendances,
                'total_users' => $totalUsers,
            ];
        });

        return [
            'datasets' => [
                [
                    'data' => $attendancePercentages->pluck('attendance_percentage')->toArray(),
                    'borderRadius' => 8,
                    'backgroundColor' => '#0f172a',
                    'borderColor' => 'rgb(24,24,26)',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $attendancePercentages->pluck('division_name')
                ->map(fn ($name) => implode(' ', array_slice(explode(' ', $name), 0, 2)))
                ->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
