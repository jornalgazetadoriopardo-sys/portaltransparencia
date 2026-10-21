<?php

namespace App\Filament\Resources\Receitas;

use App\Filament\Resources\Receitas\Pages\CreateReceita;
use App\Filament\Resources\Receitas\Pages\EditReceita;
use App\Filament\Resources\Receitas\Pages\ListReceitas;
use App\Filament\Resources\Receitas\Schemas\ReceitaForm;
use App\Filament\Resources\Receitas\Tables\ReceitasTable;
use App\Models\Receita;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ReceitaResource extends Resource
{
    protected static ?string $model = Receita::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowTrendingUp;

    protected static string|UnitEnum|null $navigationGroup = 'Financeiro';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'receita';

    protected static ?string $pluralModelLabel = 'Receitas';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'receitas';

    protected static ?string $recordTitleAttribute = 'descricao';

    public static function form(Schema $schema): Schema
    {
        return ReceitaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReceitasTable::configure($table);
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
            'index' => ListReceitas::route('/'),
            'create' => CreateReceita::route('/create'),
            'edit' => EditReceita::route('/{record}/edit'),
        ];
    }
}
