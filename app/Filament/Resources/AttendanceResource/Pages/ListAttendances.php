<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use App\Models\Attendance;
use Filament\Actions\Action;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListAttendances extends ListRecords
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('Generate pdf')
            //     ->label('Download Absensi Hari Ini')
            //     ->url(
            //         fn () => route('pdf.absensi.thisDay.download')
            //     )->icon('heroicon-o-arrow-down-tray'),

            // Action::make('Generate pdf')
            //     ->label('Download Absensi Bulan Ini')
            //     ->url(
            //         fn () => route('pdf.absensi.thisMonth.download')
            //     )->icon('heroicon-o-arrow-down-tray'),
        ];
    }

    public function getTabs(): array
    {
        return [

            'hari ini' => Tab::make()
                ->badge(Attendance::whereDate('created_at', now()->toDateString())->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', now()->toDateString())),
            'bulan ini' => Tab::make()
                ->badge(Attendance::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)->count())
                ->modifyQueryUsing(
                    fn (Builder $query) => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                ),
            'semua' => Tab::make()
                ->badge(Attendance::count()),
            // 'hadir' => Tab::make()
            //     ->badge(Attendance::where('status', 'hadir')->count())
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'hadir')),
            // 'izin' => Tab::make()
            //     ->badge(Attendance::where('status', 'izin')->count())
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'izin')),
            // 'tidak hadir' => Tab::make()
            //     ->badge(Attendance::where('status', 'tidak hadir')->count())
            //     ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 'tidak hadir')),
        ];
    }
}
