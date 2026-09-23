<?php

namespace Hamada\FilamentLocalized\Tests\Components\Forms;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Hamada\FilamentLocalized\Components\Forms\LocalizedTabs;
use Livewire\Component;
use Livewire\Livewire;

class LocalizedTabsLivewireTest extends Component implements HasSchemas
{
    use InteractsWithSchemas;

    public ?array $data = [];

    public function getSchema(string $name): ?Schema
    {
        if ($name !== 'default') {
            return null;
        }

        return Schema::make($this)
            ->components([
                LocalizedTabs::make([
                    TextInput::make('name'),
                ]),
            ])
            ->statePath('data');
    }

    public function render()
    {
        return <<<'HTML'
        <div>
            {{ $this->getSchema('default') }}
        </div>
        HTML;
    }
}

it('mounts localized tabs inside a real schema', function () {
    $component = Livewire::test(
        LocalizedTabsLivewireTest::class
    );

    expect($component->instance()->getSchema('default'))
        ->toBeInstanceOf(Schema::class);
});

it('contains the localized tabs component in the schema', function () {
    $component = Livewire::test(
        LocalizedTabsLivewireTest::class
    );

    expect(
        $component->instance()
            ->getSchema('default')
            ->getComponents()
    )->toHaveCount(1);

    expect(
        $component->instance()
            ->getSchema('default')
            ->getComponents()[0]
    )->toBeInstanceOf(Tabs::class);
});

it('creates localized fields with the correct state paths', function () {
    $component = Livewire::test(
        LocalizedTabsLivewireTest::class
    );

    $schema = $component->instance()->getSchema('default');

    /** @var Tabs $tabs */
    $tabs = $schema->getComponents()[0];

    $childSchema = $tabs->getChildSchemas()['default'];

    $tabComponents = $childSchema->getComponents();

    expect($tabComponents)
        ->toHaveCount(3);

    $expectedPaths = [
        'data.name.ar',
        'data.name.fr',
        'data.name.en',
    ];

    foreach ($tabComponents as $index => $tab) {
        $tabSchema = $tab->getChildSchemas()['default'];

        $field = $tabSchema->getComponents()[0];

        expect($field->getStatePath())
            ->toBe($expectedPaths[$index]);
    }
});

it('stores localized values in the expected form state', function () {
    $component = Livewire::test(
        LocalizedTabsLivewireTest::class
    );

    $component->set('data.name.ar', 'الروبوتات');
    $component->set('data.name.fr', 'Robotique');
    $component->set('data.name.en', 'Robotics');

    expect($component->instance()->data)
        ->toBe([
            'name' => [
                'ar' => 'الروبوتات',
                'fr' => 'Robotique',
                'en' => 'Robotics',
            ],
        ]);
});
