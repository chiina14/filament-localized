<?php

use Belaaredj\FilamentLocalized\Components\Forms\LocalizedSelect;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('test_localized_skills', function (Blueprint $table) {
        $table->id();
        $table->json('name');
        $table->timestamps();
    });

    Schema::create('test_localized_students', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->foreignId('skill_id')
            ->nullable()
            ->constrained('test_localized_skills')
            ->nullOnDelete();
        $table->timestamps();
    });

    Schema::create('test_localized_projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('test_localized_project_skill', function (Blueprint $table) {
        $table->foreignId('project_id')
            ->constrained('test_localized_projects')
            ->cascadeOnDelete();

        $table->foreignId('skill_id')
            ->constrained('test_localized_skills')
            ->cascadeOnDelete();

        $table->primary(['project_id', 'skill_id']);
    });

    TestLocalizedSkill::query()->create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    TestLocalizedSkill::query()->create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    TestLocalizedSkill::query()->create([
        'name' => [
            'ar' => 'الإلكترونيات',
            'fr' => 'Électronique',
            'en' => 'Electronics',
        ],
    ]);

    TestLocalizedStudent::query()->create([
        'full_name' => 'Student One',
        'skill_id' => 1,
    ]);

    TestLocalizedStudent::query()->create([
        'full_name' => 'Student Two',
        'skill_id' => 2,
    ]);

    TestLocalizedProject::query()->create([
        'name' => 'Project One',
    ]);
});

afterEach(function () {
    Schema::dropIfExists('test_localized_project_skill');
    Schema::dropIfExists('test_localized_projects');
    Schema::dropIfExists('test_localized_students');
    Schema::dropIfExists('test_localized_skills');
});

it('creates a real localized select with a belongs to relationship', function () {
    $select = LocalizedSelect::make('skill_id')
        ->relationship('skill')
        ->localizedTitle('name')
        ->localizedSearch()
        ->searchable();

    expect($select)
        ->toBeInstanceOf(LocalizedSelect::class)
        ->toBeInstanceOf(Select::class);

    expect($select->hasRelationship())
        ->toBeTrue();

    expect($select->getRelationshipName())
        ->toBe('skill');

    expect($select->getLocalizedTitleAttribute())
        ->toBe('name');

    expect($select->isLocalizedSearchEnabled())
        ->toBeTrue();

    expect($select->isSearchable())
        ->toBeTrue();
});

it('searches a belongs to relationship in arabic through localized select', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    $student = TestLocalizedStudent::query()->firstOrFail();

    $results = $select->applySearchConstraint(
        $student->skill()->getQuery(),
        'الروبوتات',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(1);
});

it('searches a belongs to relationship in french through localized select', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    $student = TestLocalizedStudent::query()->firstOrFail();

    $results = $select->applySearchConstraint(
        $student->skill()->getQuery(),
        'Robotique',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(1);
});

it('searches a belongs to relationship in english through localized select', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    $student = TestLocalizedStudent::query()->firstOrFail();

    $results = $select->applySearchConstraint(
        $student->skill()->getQuery(),
        'Robotics',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(1);
});

it('searches a partial translation through localized select', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    $student = TestLocalizedStudent::query()->firstOrFail();

    $results = $select->applySearchConstraint(
        $student->skill()->getQuery(),
        'Robot',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(1);
});

it('returns no results for an unknown translation through localized select', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name')
        ->localizedSearch();

    $student = TestLocalizedStudent::query()->firstOrFail();

    $results = $select->applySearchConstraint(
        $student->skill()->getQuery(),
        'Mathematics',
    )->get();

    expect($results)
        ->toHaveCount(0);
});

it('supports multiple belongs to many relationships', function () {
    $select = LocalizedSelect::make('skills')
        ->multiple()
        ->relationship('skills')
        ->localizedTitle('name')
        ->localizedSearch()
        ->searchable();

    expect($select->isMultiple())
        ->toBeTrue();

    expect($select->hasRelationship())
        ->toBeTrue();

    expect($select->getRelationshipName())
        ->toBe('skills');

    expect($select->isLocalizedSearchEnabled())
        ->toBeTrue();
});

it('searches a belongs to many relationship in french through localized select', function () {
    $project = TestLocalizedProject::query()->firstOrFail();

    $project->skills()->attach([
        1,
        2,
        3,
    ]);

    $select = LocalizedSelect::make('skills')
        ->multiple()
        ->localizedTitle('name')
        ->localizedSearch();

    $results = $select->applySearchConstraint(
        $project->skills()->getQuery(),
        'Programmation',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(2);
});

it('searches a belongs to many relationship in arabic through localized select', function () {
    $project = TestLocalizedProject::query()->firstOrFail();

    $project->skills()->attach([
        1,
        2,
        3,
    ]);

    $select = LocalizedSelect::make('skills')
        ->multiple()
        ->localizedTitle('name')
        ->localizedSearch();

    $results = $select->applySearchConstraint(
        $project->skills()->getQuery(),
        'الإلكترونيات',
    )->get();

    expect($results)
        ->toHaveCount(1)
        ->and($results->first()->id)
        ->toBe(3);
});

it('does not enable localized search by default', function () {
    $select = LocalizedSelect::make('skill_id')
        ->localizedTitle('name');

    expect($select->isLocalizedSearchEnabled())
        ->toBeFalse();
});

class TestLocalizedSkill extends Model
{
    protected $table = 'test_localized_skills';

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
    ];
}

class TestLocalizedStudent extends Model
{
    protected $table = 'test_localized_students';

    protected $guarded = [];

    public function skill()
    {
        return $this->belongsTo(
            TestLocalizedSkill::class,
            'skill_id',
        );
    }
}

class TestLocalizedProject extends Model
{
    protected $table = 'test_localized_projects';

    protected $guarded = [];

    public function skills()
    {
        return $this->belongsToMany(
            TestLocalizedSkill::class,
            'test_localized_project_skill',
            'project_id',
            'skill_id',
        );
    }
}
