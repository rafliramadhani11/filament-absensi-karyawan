<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Facades\Auth;

class Absensi extends ChartWidget
{
    protected static ?string $heading = 'Total Absensi';

    protected int|string|array $columnSpan = 'full';

    protected static ?string $maxHeight = '200px';

    protected static string $color = 'success';

    public ?string $filter = 'year';

    use InteractsWithPageFilters;

    public static function canView(): bool
    {
        $user = Auth::user();

        return ! $user->is_admin && ! $user->is_hrd;
    }

    protected function getData(): array
    {
        $data = Trend::query(
            Attendance::query()
                ->where('user_id', Auth::user()->id)
                ->where('status', 'hadir')
        )->between(
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
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
