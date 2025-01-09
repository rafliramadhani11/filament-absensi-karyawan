<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class StatsOverview extends StatsOverviewWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 0;

    public static function canView(): bool
    {
        return Auth::user()->is_hrd;
    }

    protected function getStats(): array
    {
        $startDate = Carbon::parse($this->filters['startDate']) ?? null;
        $endDate = Carbon::parse($this->filters['endDate']) ?? null;
        // $startDate = ! is_null($this->filters['startDate'] ?? null) ?
        //     Carbon::parse($this->filters['startDate']) :
        //     null;

        // $endDate = ! is_null($this->filters['endDate'] ?? null) ?
        //     Carbon::parse($this->filters['endDate']) :
        //     now();

        $diffInDays = $startDate ? $startDate->diffInDays($endDate) : 0;

        return [
            Stat::make(
                label: 'Absensi',
                value: Attendance::query()
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),
            )->description("Total Absensi dari {$diffInDays} hari"),

            Stat::make(
                label: 'User Terbuat',
                value: User::query()
                    ->where('is_admin', false)
                    ->where('is_hrd', false)
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),
            )->description("Dibuat Dalam {$diffInDays} hari"),

            Stat::make(
                label: 'Divisi Terbuat',
                value: Division::query()
                    ->where('name', '!=', 'Admin')
                    ->where('name', '!=', 'Human Resource Development')
                    ->when($startDate, fn (Builder $query) => $query->whereDate('created_at', '>=', $startDate))
                    ->when($endDate, fn (Builder $query) => $query->whereDate('created_at', '<=', $endDate))
                    ->count(),
            )->description("Terbuat Dalam {$diffInDays} hari"),

            // Stat::make('Total Absensi', Attendance::whereBetween('created_at', [$startDate, $endDate])
            //     ->where('status', 'hadir')
            //     ->count())
            //     ->description("Hadir dari {$diffInDays} hari")
            //     ->descriptionIcon('heroicon-m-arrow-trending-up'),

            // Stat::make('User Terbuat',
            //     User::where('is_hrd', false)
            //         ->where('is_admin', false)
            //         ->count()
            // ),

            // Stat::make('Division Terbuat',
            //     Division::count()
            // ),
        ];
    }
}
