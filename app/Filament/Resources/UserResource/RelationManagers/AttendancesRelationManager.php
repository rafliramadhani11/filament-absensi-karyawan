<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Absensi';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('created_at')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('created_at')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->formatStateUsing(
                        fn ($state) => Carbon::parse($state)->translatedFormat('j F Y')
                    ),
                Tables\Columns\TextColumn::make('absen_datang')
                    ->formatStateUsing(
                        fn ($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),
                Tables\Columns\TextColumn::make('absen_pulang')
                    ->formatStateUsing(
                        fn ($state) => Carbon::parse($state)->translatedFormat('H:i')
                    ),
                Tables\Columns\TextColumn::make('alasan')
                    ->words(2, '')
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir' => 'success',
                        'izin' => 'warning',
                        'tidak hadir' => 'danger',
                        'proses' => 'gray',
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'hadir' => 'heroicon-o-check-circle',
                        'izin' => 'heroicon-o-envelope',
                        'tidak hadir' => 'heroicon-o-x-circle',
                        'proses' => 'heroicon-o-clock',
                    }),

            ])
            ->headerActions([
                //
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'hadir' => 'Hadir',
                        'izin' => 'Izin',
                        'tidak hadir' => 'Tidak Hadir',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\DateTimePicker::make('absen_datang')
                                    ->label('Absen Datang')
                                    ->date(false)
                                    ->seconds(false)
                                    ->native(false)
                                    ->visible(
                                        fn (Get $get): bool => $get('status') === 'hadir'
                                    )
                                    ->required(),

                                Forms\Components\DateTimePicker::make('absen_pulang')
                                    ->label('Absen Pulang')
                                    ->date(false)
                                    ->seconds(false)
                                    ->native(false)
                                    ->visible(
                                        fn (Get $get): bool => $get('status') === 'hadir'
                                    )
                                    ->required(),

                                Forms\Components\TextInput::make('alasan')
                                    ->minLength(3)
                                    ->hidden(
                                        fn (Get $get): bool => $get('status') === 'hadir'
                                    ),

                                Forms\Components\ToggleButtons::make('status')
                                    ->options([
                                        'hadir' => 'Hadir',
                                        'izin' => 'Izin',
                                        'tidak hadir' => 'Tidak Hadir',
                                    ])
                                    ->colors([
                                        'hadir' => 'success',
                                        'izin' => 'warning',
                                        'tidak hadir' => 'danger',
                                    ])
                                    ->grouped(),

                                // Forms\Components\Select::make('gender')
                                //     ->label('Jenis Kelamin')
                                //     ->options([
                                //         'Laki - Laki' => 'Laki - Laki',
                                //         'Perempuan' => 'Perempuan',
                                //     ])
                                //     ->native(false)
                                //     ->required(),

                                // Forms\Components\DatePicker::make('birth_date')
                                //     ->format('Y-m-d')
                                //     ->native(false)
                                //     ->prefixIcon('heroicon-m-calendar')
                                //     ->required(),

                                // Forms\Components\TextInput::make('address')
                                // ->label('Alamat Tinggal')
                                // ->minLength(5)
                                // ->required(),
                            ]),

                    ]),
                // Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
