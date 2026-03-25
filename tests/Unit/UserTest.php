<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a user', function () {
    $user = User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
});

it('has links relationship', function () {
    $user = User::factory()->create();

    expect($user->links())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
});