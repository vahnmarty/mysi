<?php

use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Http\Livewire\Auth\LoginPage;

use Database\Seeders\RolesTableSeeder;
use App\Providers\RouteServiceProvider;


// test('user role is created', function () {
//     $this->seed(RolesTableSeeder::class);
//     $user = Role::where('name', 'user')->exists();
//     expect($user)->toBe(true);
// });

// test('admin role is created', function () {
//     $this->seed(RolesTableSeeder::class);
//     $user = Role::where('name', 'admin')->exists();
//     expect($user)->toBe(true);
// });

// test('login screen can be rendered', function () {
//     $response = $this->get('/login');

//     $response->assertStatus(200);
// });

// use function Pest\Livewire\livewire;

// it('can login', function () {
//     $this->seed(RolesTableSeeder::class);
//     $newData = User::factory()->make();
 
//     livewire(LoginPage::class)
//         ->fillForm([
//             'email' => $newData->email,
//             'password' => 'password'
//         ])
//         ->call('login')
//         ->assertSuccessful();

// });

// test('users can authenticate using the login screen', function () {

//     $this->seed(RolesTableSeeder::class);

//     $user = User::factory()->create();

//     $response = $this->post('/login', [
//         'email' => $user->email,
//         'password' => 'password',
//     ]);

//     $this->assertAuthenticated();
// });

// test('users can not authenticate with invalid password', function () {
//     $user = User::factory()->create();

//     $this->post('/login', [
//         'email' => $user->email,
//         'password' => 'wrong-password',
//     ]);

//     $this->assertGuest();
// });
