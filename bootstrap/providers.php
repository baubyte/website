<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\LegacyDatabaseServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    LegacyDatabaseServiceProvider::class,
];
