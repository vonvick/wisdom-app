<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Users
        factory(User::class)->states('super admins')->create();
        factory(User::class)->states('admins')->create();
        factory(User::class, 2)->states('executives')->create();
        factory(User::class, 5)->states('members')->create();
    }
}
