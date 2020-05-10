<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'title',
        'description',
        'slug'
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
