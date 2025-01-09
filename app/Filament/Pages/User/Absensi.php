<?php

namespace App\Filament\Pages\User;

use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Absensi extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    // protected static ?string $navigationGroup = 'Manajemen';

    protected static string $view = 'filament.pages.user.absensi';

    public function getTableQuery(): Builder
    {
        return Attendance::query()
            ->groupBy(DB::raw('YEAR(date)'), DB::raw('MONTH(date)'));
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return ! $user->is_admin && ! $user->is_hrd;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Attendance::where('user_id', Auth::user()->id)
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('created_at'),
                // TextColumn::make('created_at')
                //     ->label('Dibuat')
                //     ->formatStateUsing(
                //         fn ($state) => Carbon::parse($state)->translatedFormat('l')
                //     )
                //     ->description(
                //         fn ($state) => Carbon::parse($state)->translatedFormat('j F Y')
                //     ),

                // TextColumn::make('absen_datang')
                //     ->formatStateUsing(
                //         fn ($state) => Carbon::parse($state)->translatedFormat('H:i')
                //     ),
                // TextColumn::make('absen_pulang')
                //     ->formatStateUsing(
                //         fn ($state) => Carbon::parse($state)->translatedFormat('H:i')
                //     ),
                // TextColumn::make('alasan')
                //     ->words(2, '')
                //     ->searchable(),
                // TextColumn::make('status')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'hadir' => 'success',
                //         'izin' => 'warning',
                //         'tidak hadir' => 'danger',
                //         'proses' => 'warning'
                //     })
                //     ->icon(fn (string $state): string => match ($state) {
                //         'hadir' => 'heroicon-o-check-circle',
                //         'izin' => 'heroicon-o-envelope',
                //         'tidak hadir' => 'heroicon-o-x-circle',
                //         'proses' => 'heroicon-o-clock'
                //     }),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Buat Izin')
                    ->modalHeading('Buat Izin')
                    ->icon('heroicon-o-envelope')
                    ->form([
                        TextInput::make('izin')
                            ->label('Alasan'),
                    ])->visible(
                        function () {
                            $currentDate = Carbon::now()->format('Y-m-d');

                            $latestAbsenHadir = Attendance::where('user_id', Auth::id())
                                ->whereDate('created_at', $currentDate)
                                ->whereNotNull('absen_datang')
                                ->first();

                            return $latestAbsenHadir === null;
                        }
                    ),
            ]
            )
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'tidak hadir' => 'Tidak Hadir',
                    ]),
            ]);
    }
}
