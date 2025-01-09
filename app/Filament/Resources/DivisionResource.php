<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DivisionResource\Pages;
use App\Filament\Resources\DivisionResource\RelationManagers\UsersRelationManager;
use App\Models\Division;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DivisionResource extends Resource
{
    protected static ?string $model = Division::class;

    // protected static ?string $navigationGroup = 'Manajemen';

    protected static ?string $modelLabel = 'Divisi';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('name', '!=', 'Admin')
            ->where('name', '!=', 'Human Resource Development')
            ->count();
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->is_hrd;
    }

    public static function infoList(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Grid::make()->columns(3)
                    ->schema([
                        Infolists\Components\Section::make('Divisi')->columnSpan(2)
                            ->columns(3)
                            ->schema([
                                Infolists\Components\TextEntry::make('name')
                                    ->label('Nama Divisi'),
                                Infolists\Components\TextEntry::make('slug'),
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
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->unique('divisions', 'name')
                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                Forms\Components\TextInput::make('slug')
                    ->readOnly()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Division::query()
                ->where('name', '!=', 'Admin')
                ->where('name', '!=', 'Human Resource Development')
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Total Karyawan')
                    ->counts('users')
                    ->formatStateUsing(
                        fn ($state) => $state.' Karyawan'
                    ),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Action::make('Generate pdf')
                    ->label('')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(
                        fn (Model $record) => route('pdf.division.karyawan.download', ['division' => $record->id])
                    ),
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                        ->modalDescription('Semua data karyawan dan absensinya akan terhapus, apak kamu yakin?'),
                    Tables\Actions\ForceDeleteAction::make()
                        ->label('Hapus Permanen'),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDivisions::route('/'),
            // 'create' => Pages\CreateDivision::route('/create'),
            'view' => Pages\ViewDivision::route('/{record}'),
        ];
    }
}
