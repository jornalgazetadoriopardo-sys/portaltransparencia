<?php

namespace App\Providers;

use App\Models\Entidade;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Hospedagens compartilhadas com MySQL antigo limitam o tamanho de índices.
        Schema::defaultStringLength(191);

        // Datas no formato brasileiro no painel, independente do idioma do navegador.
        DatePicker::configureUsing(fn (DatePicker $campo) => $campo->native(false)->displayFormat('d/m/Y'));
        DateTimePicker::configureUsing(fn (DateTimePicker $campo) => $campo->native(false)->displayFormat('d/m/Y H:i'));

        // Dados da entidade disponíveis em todas as páginas do portal.
        View::composer(['components.portal.*', 'portal.*'], function ($view) {
            static $entidade;
            $view->with('entidade', $entidade ??= Entidade::atual());
        });
    }
}
