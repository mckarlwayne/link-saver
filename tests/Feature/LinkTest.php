<?php

use App\Models\Link;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows authenticated users to create links', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/links', [
        'title' => 'Test Link',
        'url' => 'https://example.com',
    ]);

    $response->assertRedirect('/links');
    $this->assertDatabaseHas('links', [
        'title' => 'Test Link',
        'url' => 'https://example.com',
        'user_id' => $user->id,
    ]);
});

it('prevents unauthenticated users from creating links', function () {
    $response = $this->post('/links', [
        'title' => 'Test Link',
        'url' => 'https://example.com',
    ]);

    $response->assertRedirect('/');
});

it('validates link creation', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/links', [
        'title' => '',
        'url' => 'invalid-url',
    ]);

    $response->assertSessionHasErrors(['title', 'url']);
});