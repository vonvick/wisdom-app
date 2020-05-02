<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name,
        'last_name' => $faker->name,
        'role_id' => $faker->biasedNumberBetween(1, 4),
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => Hash::make(config('app.seeders.user.password')),
        'remember_token' => Str::random(10),
    ];
});

$factory->state(User::class, 'super admins', [
    'role_id' => 1,
    'password' => Hash::make(config('app.seeders.user.super_admin_password'))
]);

$factory->state(User::class, 'admins', [
    'role_id' => 2,
    'password' => Hash::make(config('app.seeders.user.admin_password'))
]);

$factory->state(User::class, 'executives', [
    'role_id' => 3,
    'password' => Hash::make(config('app.seeders.user.executive_password'))
]);

$factory->state(User::class, 'members', [
    'role_id' => 4,
    'password' => Hash::make(config('app.seeders.user.member_password'))
]);
