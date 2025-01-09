<?php

namespace App\Filament\Resources\DivisionResource\RelationManagers;

use App\Models\Division;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Grid;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    protected static ?string $title = 'Karyawan';

    public function isReadOnly(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Karyawan')
                    ->minLength(3)
                    ->required(),

                // Grid::make()->columns(3)
                //     ->schema([
                //         Section::make('Akun Karyawan')->columnSpan(2)->columns(2)
                //             ->schema([
                //                 Forms\Components\TextInput::make('email')
                //                     ->email()
                //                     ->required(),

                //                 Forms\Components\TextInput::make('password')
                //                     ->password()
                //                     ->revealable()
                //                     ->confirmed()
                //                     ->required(),

                //                 Forms\Components\TextInput::make('password_confirmation')
                //                     ->label('Password Konfirmasi')
                //                     ->password()
                //                     ->revealable()
                //                     ->required(),
                //             ]),
                //         Section::make('Divisi')->columnSpan(1)
                //             ->schema([
                //                 Forms\Components\Select::make('division_id')
                //                     ->label('Divisi')
                //                     ->options(Division::all()->pluck('name', 'id'))
                //                     ->searchable()
                //                     ->createOptionForm([
                //                         Grid::make()->columns(['sm' => 2])
                //                             ->schema([
                //                                 Forms\Components\TextInput::make('name')
                //                                     ->required()
                //                                     ->live(onBlur: true)
                //                                     ->unique('divisions', 'name')
                //                                     ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                //                                 Forms\Components\TextInput::make('slug')
                //                                     ->readOnly()
                //                                     ->required(),
                //                             ]),
                //                     ])->createOptionModalHeading('Buat divisi baru')
                //                     ->createOptionUsing(function (array $data) {
                //                         return Division::create($data)->getKey();
                //                     })
                //                     ->required(),

                //                 Forms\Components\Select::make('role')
                //                     ->label('Role Divisi')
                //                     ->options([
                //                         'Kepala Divisi' => 'Kepala Divisi',
                //                         'Anggota Divisi' => 'Anggota Divisi',
                //                     ])
                //                     ->native(false)
                //                     ->required(),
                //             ]),
                //     ]),

                // Section::make('Profile Karyawan')->columns([
                //     'sm' => 2,
                // ])->schema([
                //     Forms\Components\TextInput::make('name')
                //         ->label('Nama Karyawan')
                //         ->minLength(3)
                //         ->required(),

                //     Forms\Components\TextInput::make('phone')
                //         ->label('No Handhpone')
                //         ->mask('9999-9999-9999')
                //         ->tel()
                //         ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/')
                //         ->required(),

                //     Forms\Components\Select::make('gender')
                //         ->label('Jenis Kelamin')
                //         ->options([
                //             'Laki - Laki' => 'Laki - Laki',
                //             'Perempuan' => 'Perempuan',
                //         ])
                //         ->native(false)
                //         ->required(),

                //     Forms\Components\DatePicker::make('birth_date')
                //         ->format('Y-m-d')
                //         ->native(false)
                //         ->prefixIcon('heroicon-m-calendar')
                //         ->required(),

                //     Forms\Components\TextInput::make('address')
                //         ->label('Alamat Tinggal')
                //         ->minLength(5)
                //         ->required(),
                // ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
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
                // Tables\Actions\CreateAction::make(),
            ])
            ->actions([
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
                    Tables\Actions\ViewAction::make()
                        ->infolist([
                            Section::make()
                                ->columns(3)
                                ->schema([
                                    TextEntry::make('name')
                                        ->label('Nama Karyawan'),
                                    TextEntry::make('email')
                                        ->label('Email'),
                                    TextEntry::make('birth_date')
                                        ->label('Tanggal Lahir'),
                                    TextEntry::make('gender')
                                        ->label('Jenis Kelamin'),
                                    TextEntry::make('phone')
                                        ->label('No Handphone'),
                                    TextEntry::make('address')
                                        ->label('Alamat Tinggal'),
                                ]),

                        ]),
                    Tables\Actions\DeleteAction::make(),
                ]),

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
