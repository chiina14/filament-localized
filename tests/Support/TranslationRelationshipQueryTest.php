<?php

use Belaaredj\FilamentLocalized\Support\TranslationQuery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('test_skills', function (Blueprint $table) {
        $table->id();
        $table->json('name');
        $table->timestamps();
    });

    Schema::create('test_students', function (Blueprint $table) {
        $table->id();
        $table->string('full_name');
        $table->foreignId('skill_id')
            ->nullable()
            ->constrained('test_skills')
            ->nullOnDelete();
        $table->timestamps();
    });

    Schema::create('test_projects', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });

    Schema::create('test_project_skill', function (Blueprint $table) {
        $table->foreignId('project_id')
            ->constrained('test_projects')
            ->cascadeOnDelete();

        $table->foreignId('skill_id')
            ->constrained('test_skills')
            ->cascadeOnDelete();

        $table->primary(['project_id', 'skill_id']);
    });

    TestSkill::query()->create([
        'name' => [
            'ar' => 'الروبوتات',
            'fr' => 'Robotique',
            'en' => 'Robotics',
        ],
    ]);

    TestSkill::query()->create([
        'name' => [
            'ar' => 'البرمجة',
            'fr' => 'Programmation',
            'en' => 'Programming',
        ],
    ]);

    TestSkill::query()->create([
        'name' => [
            'ar' => 'الإلكترونيات',
            'fr' => 'Électronique',
            'en' => 'Electronics',
        ],
    ]);

    TestStudent::query()->create([
        'full_name' => 'Student One',
        'skill_id' => 1,
    ]);

    TestStudent::query()->create([
        'full_name' => 'Student Two',
        'skill_id' => 2,
    ]);

    TestProject::query()->create([
        'name' => 'Project One',
    ]);
});

afterEach(function () {
    Schema::dropIfExists('test_project_skill');
    Schema::dropIfExists('test_students');
    Schema::dropIfExists('test_projects');
    Schema::dropIfExists('test_skills');
});

it('searches a belongs to relationship using arabic translation', function () {
    $student = TestStudent::query()->first();

    $results = TranslationQuery::modify(
        query: $student->skill()->getQuery(),
        column: 'name',
        search: 'الروبوتات',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(1);
});

it('searches a belongs to relationship using french translation', function () {
    $student = TestStudent::query()->first();

    $results = TranslationQuery::modify(
        query: $student->skill()->getQuery(),
        column: 'name',
        search: 'Robotique',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(1);
});

it('searches a belongs to relationship using english translation', function () {
    $student = TestStudent::query()->first();

    $results = TranslationQuery::modify(
        query: $student->skill()->getQuery(),
        column: 'name',
        search: 'Robotics',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(1);
});

it('searches a belongs to relationship using a partial translation', function () {
    $student = TestStudent::query()->first();

    $results = TranslationQuery::modify(
        query: $student->skill()->getQuery(),
        column: 'name',
        search: 'Robot',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(1);
});

it('returns no belongs to relationship results for an unknown translation', function () {
    $student = TestStudent::query()->first();

    $results = TranslationQuery::modify(
        query: $student->skill()->getQuery(),
        column: 'name',
        search: 'Mathematics',
    )->get();

    expect($results)->toHaveCount(0);
});

it('searches a belongs to many relationship using translations', function () {
    $project = TestProject::query()->first();

    $project->skills()->attach([
        1,
        2,
        3,
    ]);

    $results = TranslationQuery::modify(
        query: $project->skills()->getQuery(),
        column: 'name',
        search: 'Programmation',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(2);
});

it('searches a belongs to many relationship in arabic', function () {
    $project = TestProject::query()->first();

    $project->skills()->attach([
        1,
        2,
        3,
    ]);

    $results = TranslationQuery::modify(
        query: $project->skills()->getQuery(),
        column: 'name',
        search: 'الإلكترونيات',
    )->get();

    expect($results)->toHaveCount(1)
        ->and($results->first()->id)->toBe(3);
});

class TestSkill extends Model
{
    protected $table = 'test_skills';

    protected $guarded = [];

    protected $casts = [
        'name' => 'array',
    ];
}

class TestStudent extends Model
{
    protected $table = 'test_students';

    protected $guarded = [];

    public function skill()
    {
        return $this->belongsTo(TestSkill::class, 'skill_id');
    }
}

class TestProject extends Model
{
    protected $table = 'test_projects';

    protected $guarded = [];

    public function skills()
    {
        return $this->belongsToMany(
            TestSkill::class,
            'test_project_skill',
            'project_id',
            'skill_id',
        );
    }
}
