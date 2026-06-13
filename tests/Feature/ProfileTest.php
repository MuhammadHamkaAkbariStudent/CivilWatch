<?php

use App\Models\User;
use App\Models\Report;
use App\Models\District;

test('profile page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/profile');

    $response->assertOk();
});

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $user->refresh();

    $this->assertSame('Test User', $user->name);
    $this->assertSame('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->patch('/profile', [
            'name' => 'Test User',
            'email' => $user->email,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/profile');

    $this->assertNotNull($user->refresh()->email_verified_at);
});

test('user can delete their account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertGuest();
    $this->assertNull($user->fresh());
});

test('correct password must be provided to delete account', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from('/profile')
        ->delete('/profile', [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrorsIn('userDeletion', 'password')
        ->assertRedirect('/profile');

    $this->assertNotNull($user->fresh());
});

test('deleting user account keeps their reports but sets user_id to null', function () {
    $user = User::factory()->create();
    
    // Create a district first
    $district = District::create(['name' => 'Kecamatan Test']);

    // Create a report by this user
    $report = Report::create([
        'user_id' => $user->id,
        'district_id' => $district->id,
        'title' => 'Laporan Test Anonymization',
        'description' => 'Ini deskripsi laporan test.',
        'status' => 'pending',
    ]);

    // Act as the user and delete the profile
    $response = $this
        ->actingAs($user)
        ->delete('/profile', [
            'password' => 'password',
        ]);

    $response->assertRedirect('/');

    // Assert user is deleted
    $this->assertNull($user->fresh());

    // Assert report is still in database and has user_id set to null
    $report->refresh();
    $this->assertNotNull($report);
    $this->assertNull($report->user_id);
    $this->assertSame('Laporan Test Anonymization', $report->title);
});

