<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description', 'level', 'is_active'];

    public function permissions() { return $this->belongsToMany(Permission::class, 'role_permissions'); }
    public function employees() { return $this->belongsToMany(UserMaster::class, 'employee_roles', 'role_id', 'employee_id'); }
}
