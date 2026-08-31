<x-filament-widgets::widget class="fi-account-widget">
    <x-filament::section>
        <div class="fi-avatar flex shrink-0 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 h-10 w-10">
            <x-filament::icon
                icon="heroicon-o-globe-alt"
                class="h-6 w-6 text-gray-500 dark:text-gray-400"
            />
        </div>

        <div class="fi-account-widget-main">
            <h2 class="fi-account-widget-heading">
                Website
            </h2>

            <p class="fi-account-widget-user-name">
                Home Público
            </p>
        </div>

        <div class="fi-filament-info-widget-links">
            <x-filament::link
                color="gray"
                icon="heroicon-m-arrow-top-right-on-square"
                labeled-from="sm"
                tag="a"
                href="{{ route('home') }}"
                target="_blank"
            >
                Visitar
            </x-filament::button>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
