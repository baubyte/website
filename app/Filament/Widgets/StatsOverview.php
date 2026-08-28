<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class StatsOverview extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected int|array|null $columns = 2;

    protected function getStats(): array
    {
        return [

            Stat::make('Visitas totales', Cache::get('site_visits', 0))
                ->description('Visitas a la página principal')
                ->descriptionIcon('heroicon-m-eye')
                ->color('primary'),
            Stat::make('Leads / Contactos', Lead::count())
                ->description('Mensajes de contacto recibidos')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('success'),
        ];
    }
}
