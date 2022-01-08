<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions',
            'role_id',
            'permission_id'
        );
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'role_id');
    }

    /**
     * Determine if the user may perform the given permission.
     *
     * @param Permission $permission
     * @param User $user
     * @return boolean
     */
    public function hasPermission(Permission $permission, User $user): bool
    {
        if (is_string($permission)) {
            return $user->role()->permissions()->contains('name', $permission);
        }
        return (bool)$user->role()->permissions()->intersect($this)->count();
    }

    /**
     * Determine if the role has the given permission.
     *
     * @param  mixed $permission
     * @return boolean
     */
    public function inRole($permission): bool
    {
        if (is_string($permission)) {
            return $this->permissions()->contains('name', $permission);
        }
        return (bool)$permission->intersect($this->permissions())->count();
    }
}
