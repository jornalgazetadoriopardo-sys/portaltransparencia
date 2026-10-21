<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')->label('Nome')->required()->maxLength(255),
                TextInput::make('email')->label('E-mail')->email()->required()->unique(ignoreRecord: true)->maxLength(255),
                TextInput::make('password')
                    ->label('Senha')
                    ->password()
                    ->revealable()
                    ->minLength(10)
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->helperText('Mínimo de 10 caracteres. Deixe em branco para manter a senha atual.'),
            ]);
    }
}
