<?php

namespace App\Filament\Resources\AttendanceResource\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Support\RawJs;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class AttendanceChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Absensi';

    protected static ?string $description = 'Berdasarkan setiap bulan';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    protected static ?int $sort = 2;

    // public ?string $filter = 'year';

    public static function canView(): bool
    {
        return Auth::user()->is_hrd;
    }

    // protected function getFilters(): ?array
    // {
    //     return [
    //         'today' => 'Hari ini',
    //         'week' => 'Minggu Ini',
    //         'month' => 'Bulan Ini',
    //         'year' => 'Tahun ini',
    //     ];
    // }

    protected function getOptions(): RawJs
    {
        return RawJs::make(<<<'JS'
        {
            scales: {
                y: {
                    beginAtZero: true
                },
            },
        }
    JS);
    }

    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $data = Trend::model(Attendance::class)
            ->between(
                start: Carbon::parse($this->filters['startDate']),
                end: Carbon::parse($this->filters['endDate'])
            )
            ->perMonth()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Absensi',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'tension' => 0.1,
                    'borderRadius' => 5,
                    'pointBorderWidth' => 5,
                    'borderColor' => 'rgb(24, 24, 26)',

                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => Carbon::parse($value->date)->translatedFormat('F Y')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
