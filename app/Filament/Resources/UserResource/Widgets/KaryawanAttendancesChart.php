<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class KaryawanAttendancesChart extends ChartWidget
{
    protected static ?string $heading = 'Total Kehadiran';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    protected static string $color = 'success';

    public ?string $filter = 'year';

    public ?User $record;

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Hari ini',
            'week' => 'Minggu kemarin',
            'month' => 'Bulan Kemarin',
            'year' => 'Tahun ini',
            'last_year' => 'Tahun Kemarin',
        ];
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        // Tentukan rentang waktu berdasarkan filter
        [$start, $end] = match ($activeFilter) {
            'today' => [now()->startOfDay(), now()->endOfDay()],
            'week' => [now()->startOfWeek(), now()->endOfWeek()],
            'month' => [now()->startOfMonth(), now()->endOfMonth()],
            'year' => [now()->startOfYear(), now()->endOfYear()],
            'last_year' => [now()->subYear()->startOfYear(), now()->subYear()->endOfYear()],
            default => [now()->startOfYear(), now()->endOfYear()],
        };

        $hadirData = Trend::query(
            Attendance::query()->where('user_id', $this->record->id)->where('status', 'hadir')
        )
            ->between(start: $start, end: $end)
            ->perMonth()
            ->count();

        $izinData = Trend::query(
            Attendance::query()->where('user_id', $this->record->id)->where('status', 'izin')
        )
            ->between(start: $start, end: $end)
            ->perMonth()
            ->count();

        $tidakHadirData = Trend::query(
            Attendance::query()->where('user_id', $this->record->id)->where('status', 'tidak hadir')
        )
            ->between(start: $start, end: $end)
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Hadir',
                    'data' => $hadirData->map(fn (TrendValue $value) => $value->aggregate),
                    'tension' => 0.5,
                    'backgroundColor' => 'rgba(75, 192, 75, 0.2)', // Hijau
                    'borderColor' => 'rgba(75, 192, 75, 1)',
                    'borderWidth' => 1.5,
                ],
                [
                    'label' => 'Izin',
                    'data' => $izinData->map(fn (TrendValue $value) => $value->aggregate),
                    'tension' => 0.5,
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)', // Kuning
                    'borderColor' => 'rgba(255, 206, 86, 1)',
                    'borderWidth' => 1.5,
                ],
                [
                    'label' => 'Tidak Hadir',
                    'data' => $tidakHadirData->map(fn (TrendValue $value) => $value->aggregate),
                    'tension' => 0.5,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)', // Merah
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1.5,
                ],
            ],
            'labels' => $hadirData->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
