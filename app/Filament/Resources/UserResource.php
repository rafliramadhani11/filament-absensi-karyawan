<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\Pages\ListUsers;
use App\Filament\Resources\UserResource\Pages\ViewUser;
use App\Filament\Resources\UserResource\RelationManagers\AttendancesRelationManager;
use App\Filament\Resources\UserResource\Widgets\KaryawanAttendancesChart;
use App\Models\Division;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $modelLabel = 'Karyawan';

    // protected static ?string $navigationGroup = 'Manajemen';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_admin', false)
            ->where('is_hrd', false)
            ->count();
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->is_hrd;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('is_admin', false)
            ->where('is_hrd', false);
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make()->columns(3)
                    ->schema([
                        Infolists\Components\Section::make('Profile Karyawan')->columnSpan(2)
                            ->columns(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Karyawan'),
                                Infolists\Components\TextEntry::make('email')
                                    ->label('Email'),
                                Infolists\Components\TextEntry::make('birth_date')
                                    ->label('Tanggal Lahir'),
                                Infolists\Components\TextEntry::make('gender')
                                    ->label('Jenis Kelamin'),
                                Infolists\Components\TextEntry::make('phone')
                                    ->label('No Handphone'),
                                Infolists\Components\TextEntry::make('address')
                                    ->label('Alamat Tinggal'),
                            ]),
                        Infolists\Components\Section::make()->columnSpan(1)
                            ->columns(2)
                            ->schema([
                                Infolists\Components\TextEntry::make('created_at')
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('updated_at')
                                    ->dateTime(),
                            ]),
                    ]),
                Infolists\Components\Section::make('Divisi')->columnSpan(1)
                    ->columns(2)
                    ->schema([
                        Infolists\Components\TextEntry::make('division.name'),
                        Infolists\Components\TextEntry::make('role'),
                    ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()->columns(3)
                    ->schema([
                        Section::make('Akun Karyawan')->columnSpan(2)->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required(),

                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->revealable()
                                    ->confirmed()
                                    ->required(),

                                Forms\Components\TextInput::make('password_confirmation')
                                    ->label('Password Konfirmasi')
                                    ->password()
                                    ->revealable()
                                    ->required(),
                            ]),
                        Section::make('Divisi')->columnSpan(1)
                            ->schema([
                                Forms\Components\Select::make('division_id')
                                    ->label('Divisi')
                                    ->options(Division::all()
                                        ->where('name', '!=', 'Admin')
                                        ->where('name', '!=', 'Human Resource Development')
                                        ->pluck('name', 'id'))
                                    ->searchable()
                                    ->createOptionForm([
                                        Grid::make()->columns(['sm' => 2])
                                            ->schema([
                                                Forms\Components\TextInput::make('name')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->unique('divisions', 'name')
                                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                                Forms\Components\TextInput::make('slug')
                                                    ->readOnly()
                                                    ->required(),
                                            ]),
                                    ])->createOptionModalHeading('Buat divisi baru')
                                    ->createOptionUsing(function (array $data) {
                                        return Division::create($data)->getKey();
                                    })
                                    ->required(),

                                Forms\Components\Select::make('role')
                                    ->label('Role Divisi')
                                    ->options([
                                        'Kepala Divisi' => 'Kepala Divisi',
                                        'Anggota Divisi' => 'Anggota Divisi',
                                    ])
                                    ->native(false)
                                    ->required(),
                            ]),
                    ]),

                Section::make('Profile Karyawan')->columns([
                    'sm' => 2,
                ])->schema([
                    Forms\Components\TextInput::make('nik')
                        ->label('Nomor Induk Kependudukan (NIK)')
                        ->minLength(16)
                        ->numeric()
                        ->required(),

                    Forms\Components\TextInput::make('name')
                        ->label('Nama Karyawan')
                        ->minLength(3)
                        ->required(),

                    Forms\Components\TextInput::make('phone')
                        ->label('No Handhpone')
                        ->mask('9999-9999-9999')
                        ->tel()
                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                        ->required(),

                    Forms\Components\Select::make('gender')
                        ->label('Jenis Kelamin')
                        ->options([
                            'Laki - Laki' => 'Laki - Laki',
                            'Perempuan' => 'Perempuan',
                        ])
                        ->native(false)
                        ->required(),

                    Forms\Components\DatePicker::make('birth_date')
                        ->format('Y-m-d')
                        ->native(false)
                        ->prefixIcon('heroicon-m-calendar')
                        ->required(),

                    Forms\Components\TextInput::make('address')
                        ->label('Alamat Tinggal')
                        ->minLength(5)
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(User::query()->where('is_admin', false)
                ->where('is_hrd', false))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (User $record): string => $record->email)
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('birth_date')
                    ->label('Tanggal Lahir'),
                Tables\Columns\TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->label('Alamat Tinggal')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Action::make('Generate pdf')
                    ->label('Download Data Karyawan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(
                        fn () => route('pdf.karyawan.download')
                    )->hidden(function () {
                        $userCount = User::query()
                            ->where('is_admin', false)
                            ->where('is_hrd', false)
                            ->count();

                        return $userCount === 0;
                    }),
            ])
            ->actions([
                Action::make('Generate pdf')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(
                        fn (Model $record) => route('pdf.karyawan.absensi.download', ['user' => $record->id]) // Pastikan 'user' adalah ID
                    ),
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->form([
                            Grid::make(2)
                                ->schema([
                                    Forms\Components\TextInput::make('name')
                                        ->label('Nama Karyawan')
                                        ->minLength(3)
                                        ->required(),

                                    Forms\Components\TextInput::make('phone')
                                        ->label('No Handhpone')
                                        ->mask('9999-9999-9999')
                                        ->tel()
                                        ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                                        ->required(),

                                    Forms\Components\Select::make('gender')
                                        ->label('Jenis Kelamin')
                                        ->options([
                                            'Laki - Laki' => 'Laki - Laki',
                                            'Perempuan' => 'Perempuan',
                                        ])
                                        ->native(false)
                                        ->required(),

                                    Forms\Components\DatePicker::make('birth_date')
                                        ->format('Y-m-d')
                                        ->native(false)
                                        ->prefixIcon('heroicon-m-calendar')
                                        ->required(),

                                    Forms\Components\TextInput::make('address')
                                        ->label('Alamat Tinggal')
                                        ->minLength(5)
                                        ->required(),
                                ]),

                        ]),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->modalDescription('Absensi pada karyawan ini juga akan terhapus permanen, apa kamu yakin?'),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getWidgets(): array
    {
        return [
            KaryawanAttendancesChart::class,
        ];
    }

    public static function getRelations(): array
    {
        return [
            AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'view' => ViewUser::route('/{record}'),
        ];
    }
}
