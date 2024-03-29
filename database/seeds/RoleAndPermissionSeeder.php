<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $super_admin_role = Role::create([
            'name' => 'Super Admin',
            'slug' => 'super_admin',
            'description' => 'This role is for the super admin of the site'
        ]);

        $admins_role = Role::create([
            'name' => 'Admin',
            'slug' => 'admin',
            'description' => 'This role is for the admins of the site'
        ]);

        $executives_role = Role::create([
            'name' => 'Executives',
            'slug' => 'executive',
            'description' => 'This role is for the executives of the site'
        ]);

        $members_role = Role::create([
            'name' => 'Members',
            'slug' => 'member',
            'description' => 'This role is for the members of the site'
        ]);

        $roles_list = [
            'super_admin' => $super_admin_role,
            'admin' => $admins_role,
            'executives' => $executives_role,
            'members' => $members_role
        ];

        $permissions_list = [
            'all' => [
                'name' => 'All Permission',
                'slug' => '*',
                'description' => 'This permission is the ability to do everything on the app',
                'roles' => ['super_admin']
            ],
            'create_users' => [
                'name' => 'Create user',
                'slug' => 'users.create',
                'description' => 'This permission is for the ability to add a user profile',
                'roles' => ['admin']
            ],
            'update_users' => [
                'name' => 'Update user',
                'slug' => 'users.update',
                'description' => 'This permission is for the ability to update user a user profile',
                'roles' => ['admin', 'executives', 'members']
            ],
            'delete_users' => [
                'name' => 'Delete user',
                'slug' => 'users.delete',
                'description' => 'This permission is for the ability to delete a user profile',
                'roles' => ['admin']
            ],
            'restore_users' => [
                'name' => 'Restore users',
                'slug' => 'users.restore',
                'description' => 'This permission is for the ability to restore a soft deleted user',
                'roles' => ['admin']
            ],
            'view_permissions' => [
                'name' => 'View all permissions',
                'slug' => 'permissions.index',
                'description' => 'This permission is for the ability to see all permissions',
                'roles' => ['admin']
            ],
            'view_permission' => [
                'name' => 'View a permission',
                'slug' => 'permissions.show',
                'description' => 'This permission is for the ability to see a permission',
                'roles' => ['admin']
            ],
            'create_permission' => [
                'name' => 'Create a permission',
                'slug' => 'permissions.create',
                'description' => 'This permission is for the ability to create a permission',
                'roles' => ['admin']
            ],
            'view_roles' => [
                'name' => 'View all roles',
                'slug' => 'roles.index',
                'description' => 'This permission is for the ability to see all roles',
                'roles' => ['admin']
            ],
            'view_role' => [
                'name' => 'View a role',
                'slug' => 'roles.show',
                'description' => 'This permission is for the ability to see a role',
                'roles' => ['admin']
            ],
            'create_post' => [
                'name' => 'Create a post',
                'slug' => 'posts.create',
                'description' => 'This permission is for the ability to create a post',
                'roles' => ['admin']
            ],
            'update_post' => [
                'name' => 'Update a post',
                'slug' => 'posts.update',
                'description' => 'This permission is for the ability to update a post',
                'roles' => ['admin']
            ],
            'delete_post' => [
                'name' => 'Delete a post',
                'slug' => 'posts.delete',
                'description' => 'This permission is for the ability to delete a post',
                'roles' => ['admin']
            ],
            'create_media_upload' => [
                'name' => 'Media Upload',
                'slug' => 'media.upload',
                'description' => 'This permission is the ability upload a media file',
                'roles' => ['admin', 'executives', 'members']
            ],
            'delete_media_upload' => [
                'name' => 'Media Delete',
                'slug' => 'media.delete',
                'description' => 'This permission is the ability delete a media file',
                'roles' => ['admin']
            ],
            'update_media_details' => [
                'name' => 'Media Update',
                'slug' => 'media.update',
                'description' => 'This permission is the ability update a media description and privacy level',
                'roles' => ['admin', 'executives', 'members']
            ],
            'create_gallery' => [
                'name' => 'Create media gallery',
                'slug' => 'gallery.create',
                'description' => 'This permission is for the ability to create a media gallery',
                'roles' => ['admin', 'executives', 'members']
            ],
            'update_gallery' => [
                'name' => 'Update a gallery',
                'slug' => 'gallery.update',
                'description' => 'This permission is for the ability to update a media gallery',
                'roles' => ['admin']
            ],
            'delete_gallery' => [
                'name' => 'Delete A Media Gallery',
                'slug' => 'gallery.delete',
                'description' => 'This permission is for the ability to delete a media gallery',
                'roles' => ['admin']
            ]
        ];

        foreach($permissions_list as $permission) {
            $created_permission = Permission::create(['name' => $permission['name'], 'slug' => $permission['slug'], 'description' => $permission['description'] ]);

            foreach($permission['roles'] as $role) {
                $roles_list[$role]->permissions()->attach($created_permission);
            }
        }
    }
}
