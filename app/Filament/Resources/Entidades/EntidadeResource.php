<?php

namespace App\Filament\Resources\Entidades;

use App\Filament\Resources\Entidades\Pages\CreateEntidade;
use App\Filament\Resources\Entidades\Pages\EditEntidade;
use App\Filament\Resources\Entidades\Pages\ListEntidades;
use App\Filament\Resources\Entidades\Schemas\EntidadeForm;
use App\Filament\Resources\Entidades\Tables\EntidadesTable;
use App\Models\Entidade;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class EntidadeResource extends Resource
{
    protected static ?string $model = Entidade::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static string|UnitEnum|null $navigationGroup = 'Institucional';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'dados da entidade';

    protected static ?string $pluralModelLabel = 'Dados da entidade';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'entidade';

    protected static ?string $recordTitleAttribute = 'nome';

    public static function canCreate(): bool
    {
        return ! Entidade::query()->exists();
    }

    public static function form(Schema $schema): Schema
    {
        return EntidadeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EntidadesTable::configure($table);
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
            'index' => ListEntidades::route('/'),
            'create' => CreateEntidade::route('/create'),
            'edit' => EditEntidade::route('/{record}/edit'),
        ];
    }
}
