<?php

namespace App\Filament\Pages;

use App\Models\User;
use Carbon\Carbon;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Pages\Page;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class QrCode extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-qr-code';

    protected static string $view = 'filament.pages.qr-code';

    // protected ?User $record;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user->is_admin;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::where('is_admin', false)
                    ->where('is_hrd', false)
            )
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Karyawan')
                    ->sortable(),

                ViewColumn::make('absen-datang-qrCode')
                    ->label('Absen Datang')
                    ->view('admin.absen-datang-qrCode'),
                // ->hidden(function () {
                //     $currentTime = Carbon::now()->format('H:i');

                //     return $currentTime < '02:45' || $currentTime >= '02:46';
                // }),

                ViewColumn::make('absen-pulang-qrCode')
                    ->label('Absen Pulang')
                    ->view('admin.absen-pulang-qrCode'),
            ])
            ->actions([
                Action::make('view')
                    ->label('Detail')
                    ->button()
                    ->icon('heroicon-o-eye')
                    ->infolist([
                        TextEntry::make('name')
                            ->label('Nama Karyawan'),
                        Grid::make()
                            ->schema([
                                ViewEntry::make('AbsenDatangQrCode')
                                    ->label('Absen Datang')
                                    ->view('infolists.components.absen-datang-qr-code'),

                                ViewEntry::make('AbsenDatangQrCode')
                                    ->label('Absen Pulang')
                                    ->view('infolists.components.absen-pulang-qr-code'),
                            ]),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCloseButton(true)
                    ->modalCancelActionLabel('Tutup'),
            ]);
    }
}
