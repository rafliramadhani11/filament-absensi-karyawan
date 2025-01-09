<?php

namespace App\Filament\Pages;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PemantauanGaji extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string $view = 'filament.pages.pemantauan-gaji';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return ! $user->is_admin && ! $user->is_hrd;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                // Attendance::query()
                //     ->with('user')
                //     ->selectRaw('id, status, DATE(created_at) as created_date')
                //     ->orderBy('created_date', 'desc')
                User::query()
                    ->where('is_admin', false)
                    ->where('is_hrd', false)
                    ->selectRaw('id, name, DATE(created_at) as created_date')
                    ->withCount([
                        'attendances as total_hadir' => function ($query) {
                            $query->where('status', 'hadir');
                        },
                    ])
                    ->orderBy('created_date', 'desc')
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Karyawan'),

                TextColumn::make('created_date')
                    ->label('Bulan')
                    ->formatStateUsing(
                        fn ($state) => Carbon::parse($state)->translatedFormat('F')
                    ),
            ]);
    }
}
