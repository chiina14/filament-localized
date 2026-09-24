@php
    use Belaaredj\FilamentLocalized\Components\LocaleSwitcher;
    use Belaaredj\FilamentLocalized\Support\LocaleManager;

    $currentLocale = LocaleSwitcher::current();
    $locales = LocaleSwitcher::locales();
@endphp

@if (LocaleSwitcher::isEnabled())
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <x-filament::button color="gray" size="sm" icon="heroicon-o-language" :aria-label="__('Change language')">
                <span class="flex items-center gap-2">
                    @if (LocaleSwitcher::showFlag())
                        <span class="text-base leading-none">
                            {{ LocaleManager::flag($currentLocale) }}
                        </span>
                    @endif

                    @if (LocaleSwitcher::showLabel())
                        <span>
                            {{ LocaleManager::label($currentLocale) }}
                        </span>
                    @endif

                    @if (LocaleSwitcher::showShort())
                        <span class="text-xs">
                            {{ LocaleManager::short($currentLocale) }}
                        </span>
                    @endif
                </span>
            </x-filament::button>
        </x-slot>

        <x-filament::dropdown.list>
            @foreach ($locales as $code => $locale)
                <x-filament::dropdown.list.item tag="a" :href="route('filament-localized.locale', ['locale' => $code])" :icon="$code === $currentLocale ? 'heroicon-m-check' : null">
                    <span class="flex w-full items-center gap-3">
                        @if (LocaleSwitcher::showFlag())
                            <span class="inline-flex w-6 shrink-0 items-center justify-center text-lg leading-none">
                                {{ $locale['flag'] ?? '' }}
                            </span>
                        @endif

                        @if (LocaleSwitcher::showLabel())
                            <span class="flex-1">
                                {{ $locale['label'] ?? strtoupper($code) }}
                            </span>
                        @endif

                        @if (LocaleSwitcher::showShort())
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $locale['short'] ?? strtoupper($code) }}
                            </span>
                        @endif
                    </span>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
@endif
