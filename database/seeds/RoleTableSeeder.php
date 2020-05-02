<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Articles
        $role_super_admin = new Role;
        $role_super_admin->title = 'Super Admin';
        $role_super_admin->slug = 'super_admin';
        $role_super_admin->description = 'This role is for the super admin of the site';
        $role_super_admin->save();

        $role_admin = new Role;
        $role_admin->title = 'Admin';
        $role_admin->slug = 'admin';
        $role_admin->description = 'This role is for site admins and elevated executive members';
        $role_admin->save();

        $role_executive = new Role;
        $role_executive->title = 'executive';
        $role_executive->slug = 'executive';
        $role_executive->description = 'This role is for executive committee members';
        $role_executive->save();

        $role_member = new Role;
        $role_member->title = 'member';
        $role_member->slug = 'member';
        $role_member->description = 'This role is for general members';
        $role_member->save();
    }
}
