<?php

use App\Models\User;
use Filament\Auth\Pages\Login;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'password')
        ->call('authenticate');

    $response
        ->assertHasNoErrors()
        //->assertRedirect(route('dashboard', absolute: false))
        ->assertRedirect(route('filament.user.pages..', absolute: false))
        ;

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = Livewire::test(Login::class)
        ->set('data.email', $user->email)
        ->set('data.password', 'wrong-password')
        ->call('authenticate');

    $response->assertHasErrors('data.email');

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->session([])
        ->post(route('logout-user'), [
            '_token' => csrf_token(),
        ]);

    $response->assertRedirect(route('filament.user.pages..'));

    $this->assertGuest();
});