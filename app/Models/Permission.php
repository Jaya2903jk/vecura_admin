<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = ['name', 'module', 'description', 'is_active'];

    public function roles() { return $this->belongsToMany(Role::class, 'role_permissions'); }
}
