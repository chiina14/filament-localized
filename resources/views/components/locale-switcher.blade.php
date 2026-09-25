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
                            @if (is_string(LocaleSwitcher::flag($currentLocale)) &&
                                    filter_var(LocaleSwitcher::flag($currentLocale), FILTER_VALIDATE_URL))
                                <img src="{{ LocaleSwitcher::flag($currentLocale) }}" alt=""
                                    class="h-4 w-6 object-cover" />
                            @else
                                {{ LocaleSwitcher::flag($currentLocale) ?? LocaleSwitcher::flagFallback($currentLocale) }}
                            @endif
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
                                @if (is_string($locale['flag'] ?? null) && filter_var($locale['flag'], FILTER_VALIDATE_URL))
                                    <img src="{{ $locale['flag'] }}" alt="" class="h-4 w-6 object-cover" />
                                @else
                                    {{ $locale['flag'] ?? LocaleSwitcher::flagFallback($code) }}
                                @endif
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
