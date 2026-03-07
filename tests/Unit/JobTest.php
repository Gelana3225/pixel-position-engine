<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Job;
use App\Models\Employer;

uses(RefreshDatabase::class); // Make sure database tables are migrated for each test

test('job belongs to an employer', function () {
    // Create an employer using the factory
    $employer = Employer::factory()->create();

    // Create a job and associate it with the employer
    $job = Job::factory()->create([
        'employer_id' => $employer->id,
    ]);

    // Assert the job's employer is the one we created
    expect($job->employer->is($employer))->toBeTrue();
});

test('can have tags', function () {
    $job = Job::factory()->create();

    $job->tag('Frontend');

    expect($job->tags)->toHaveCount(1);
});