<?php

namespace App\Filament\Resources\Parcerias;

use App\Filament\Resources\Parcerias\Pages\CreateParceria;
use App\Filament\Resources\Parcerias\Pages\EditParceria;
use App\Filament\Resources\Parcerias\Pages\ListParcerias;
use App\Filament\Resources\Parcerias\Schemas\ParceriaForm;
use App\Filament\Resources\Parcerias\Tables\ParceriasTable;
use App\Models\Parceria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ParceriaResource extends Resource
{
    protected static ?string $model = Parceria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCheck;

    protected static string|UnitEnum|null $navigationGroup = 'Recursos públicos';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'parceria';

    protected static ?string $pluralModelLabel = 'Parcerias e convênios';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'parcerias';

    protected static ?string $recordTitleAttribute = 'numero';

    public static function form(Schema $schema): Schema
    {
        return ParceriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ParceriasTable::configure($table);
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
            'index' => ListParcerias::route('/'),
            'create' => CreateParceria::route('/create'),
            'edit' => EditParceria::route('/{record}/edit'),
        ];
    }
}
