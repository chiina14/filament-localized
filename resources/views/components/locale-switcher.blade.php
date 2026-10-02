@php
    use Belaaredj\FilamentLocalized\Components\LocaleSwitcher;
    use Belaaredj\FilamentLocalized\Support\LocaleManager;

    $currentLocale = LocaleSwitcher::current();
    $locales = LocaleSwitcher::locales();
@endphp

@if (LocaleSwitcher::isEnabled())
    <x-filament::dropdown placement="bottom-end">
        <x-slot name="trigger">
            <x-filament::button color="gray" size="sm" :aria-label="__('Change language')">
                <span style="display: flex; align-items: center; gap: 0.5rem; direction: ltr; white-space: nowrap">
                    @if (LocaleSwitcher::showFlag())
                        <span
                            style="display: inline-flex; flex: 0 0 1rem; width: 1rem; height: 1rem; overflow: hidden; align-items: center; justify-content: center; border-radius: 50%; line-height: 1">
                            @if (is_string(LocaleSwitcher::flag($currentLocale)) &&
                                    filter_var(LocaleSwitcher::flag($currentLocale), FILTER_VALIDATE_URL))
                                <img src="{{ LocaleSwitcher::flag($currentLocale) }}" alt=""
                                    style="display: block; width: 100%; height: 100%; object-fit: cover" />
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
                    <span style="display: flex; width: 100%; align-items: center; gap: 0.75rem; direction: ltr">
                        @if (LocaleSwitcher::showFlag())
                            <span
                                style="display: inline-flex; flex: 0 0 1rem; width: 1rem; height: 1rem; overflow: hidden; align-items: center; justify-content: center; border-radius: 50%; line-height: 1">
                                @if (is_string($locale['flag'] ?? null) && filter_var($locale['flag'], FILTER_VALIDATE_URL))
                                    <img src="{{ $locale['flag'] }}" alt=""
                                        style="display: block; width: 100%; height: 100%; object-fit: cover" />
                                @else
                                    {{ $locale['flag'] ?? LocaleSwitcher::flagFallback($code) }}
                                @endif
                            </span>
                        @endif

                        @if (LocaleSwitcher::showLabel())
                            <span style="flex: 1">
                                {{ $locale['label'] ?? strtoupper($code) }}
                            </span>
                        @endif

                        @if (LocaleSwitcher::showShort())
                            <span style="font-size: 0.75rem; line-height: 1rem; color: rgb(107 114 128)">
                                {{ $locale['short'] ?? strtoupper($code) }}
                            </span>
                        @endif
                    </span>
                </x-filament::dropdown.list.item>
            @endforeach
        </x-filament::dropdown.list>
    </x-filament::dropdown>
@endif
