<?php

namespace App\Filament\Resources\Dirigentes;

use App\Filament\Resources\Dirigentes\Pages\CreateDirigente;
use App\Filament\Resources\Dirigentes\Pages\EditDirigente;
use App\Filament\Resources\Dirigentes\Pages\ListDirigentes;
use App\Filament\Resources\Dirigentes\Schemas\DirigenteForm;
use App\Filament\Resources\Dirigentes\Tables\DirigentesTable;
use App\Models\Dirigente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class DirigenteResource extends Resource
{
    protected static ?string $model = Dirigente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static string|UnitEnum|null $navigationGroup = 'Institucional';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'dirigente';

    protected static ?string $pluralModelLabel = 'Dirigentes e conselheiros';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'dirigentes';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function form(Schema $schema): Schema
    {
        return DirigenteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DirigentesTable::configure($table);
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
            'index' => ListDirigentes::route('/'),
            'create' => CreateDirigente::route('/create'),
            'edit' => EditDirigente::route('/{record}/edit'),
        ];
    }
}
