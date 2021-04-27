<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'active',
        'level'
    ];

    protected $casts = [
        'active' => 'bool',
        'level' => 'int',
    ];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param  Permission $permission
     * @return boolean
     */
    public function hasPermission(Permission $permission, User $user)
    {
        if (is_string($permission)) {
            return $user->role()->permissions()->contains('name', $permission);
        }
        return !! $user->roles()->permissions()->intersect($this->roles)->count();
    }

    /**
     * Determine if the role has the given permission.
     *
     * @param  mixed $permission
     * @return boolean
     */
    public function inRole($permission)
    {
        if (is_string($permission)) {
            return $this->permissions->contains('name', $permission);
        }
        return !! $permission->intersect($this->permissions)->count();
    }
}
