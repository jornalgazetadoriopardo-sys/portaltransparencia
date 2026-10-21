<?php

namespace App\Filament\Resources\PerguntaFrequentes;

use App\Filament\Resources\PerguntaFrequentes\Pages\CreatePerguntaFrequente;
use App\Filament\Resources\PerguntaFrequentes\Pages\EditPerguntaFrequente;
use App\Filament\Resources\PerguntaFrequentes\Pages\ListPerguntaFrequentes;
use App\Filament\Resources\PerguntaFrequentes\Schemas\PerguntaFrequenteForm;
use App\Filament\Resources\PerguntaFrequentes\Tables\PerguntaFrequentesTable;
use App\Models\PerguntaFrequente;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PerguntaFrequenteResource extends Resource
{
    protected static ?string $model = PerguntaFrequente::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|UnitEnum|null $navigationGroup = 'Atendimento';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'pergunta frequente';

    protected static ?string $pluralModelLabel = 'Perguntas frequentes';

    protected static bool $hasTitleCaseModelLabel = false;

    protected static ?string $slug = 'perguntas-frequentes';

    protected static ?string $recordTitleAttribute = 'pergunta';

    public static function form(Schema $schema): Schema
    {
        return PerguntaFrequenteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PerguntaFrequentesTable::configure($table);
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
            'index' => ListPerguntaFrequentes::route('/'),
            'create' => CreatePerguntaFrequente::route('/create'),
            'edit' => EditPerguntaFrequente::route('/{record}/edit'),
        ];
    }
}
