<?php

declare(strict_types=1);

namespace Belaaredj\FilamentLocalized;

use Belaaredj\FilamentLocalized\Http\Middleware\ApplyLocale;
use Filament\Contracts\Plugin;
use Filament\Panel;
use Filament\View\PanelsRenderHook;

class FilamentLocalizedPlugin implements Plugin
{
    protected bool $localeSwitcher = true;

    protected string $localeSwitcherHook =
        PanelsRenderHook::TOPBAR_END;

    public function getId(): string
    {
        return 'filament-localized';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->middleware(
                [
                    ApplyLocale::class,
                ],
                isPersistent: true,
            )
            ->renderHook(
                $this->localeSwitcherHook,
<<<<<<< HEAD
                fn (): string => $this->localeSwitcher
=======
                fn(): string => $this->localeSwitcher
                    && (bool) config(
                        'filament-localized.locale_switcher.enabled',
                        true,
                    )
>>>>>>> 9abade2 (fix)
                    ? view(
                        'filament-localized::components.locale-switcher'
                    )->render()
                    : '',
            );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(
            app(static::class)->getId()
        );

        return $plugin;
    }

    public function localeSwitcher(
        bool $condition = true,
    ): static {
        $this->localeSwitcher = $condition;

        return $this;
    }

    public function localeSwitcherHook(
        string $hook,
    ): static {
        $this->localeSwitcherHook = $hook;

        return $this;
    }
}
