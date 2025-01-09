<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceResource\Pages\ListAttendances;
use App\Models\Attendance;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\View;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class AttendanceResource extends Resource
{
    protected static ?string $model = Attendance::class;

    protected static ?string $modelLabel = 'Absensi';

    // protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    public static function canViewAny(): bool
    {
        return Auth::user()->is_hrd;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('absen_datang'),
                Forms\Components\TextInput::make('absen_pulang'),
                Forms\Components\TextInput::make('status')
                    ->required(),
                Forms\Components\TextInput::make('alasan')
                    ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Nama Karyawan')
                    ->sortable(),
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
                    })
                    ->icon(fn (string $state): string => match ($state) {
                        'hadir' => 'heroicon-o-check-circle',
                        'izin' => 'heroicon-o-envelope',
                        'tidak hadir' => 'heroicon-o-x-circle',
                    }),
            ])
            ->headerActions([
                Action::make('Generate pdf')
                    ->label('Absensi Hari Ini')
                    ->url(
                        fn () => route('pdf.absensi.thisDay.download')
                    )->icon('heroicon-o-arrow-down-tray'),

                Action::make('Generate pdf')
                    ->label('Absensi Bulan Ini')
                    ->url(
                        fn () => route('pdf.absensi.thisMonth.download')
                    )->icon('heroicon-o-arrow-down-tray'),
            ])
            ->filters([
                DateRangeFilter::make('created_at')
                    ->label('Dibuat'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAttendances::route('/'),
            // 'create' => Pages\CreateAttendance::route('/create'),
            // 'view' => Pages\ViewAttendance::route('/{record}'),
            // 'edit' => Pages\EditAttendance::route('/{record}/edit'),
        ];
    }
}
