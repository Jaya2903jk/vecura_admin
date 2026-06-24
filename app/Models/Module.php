<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'modules';
    protected $fillable = ['name', 'parent', 'description', 'icon', 'route_prefix', 'sort_order', 'is_active'];
    public $timestamps = true;

    public function permissions()
    {
        return $this->hasMany(Permission::class, 'module', 'name');
    }

    // Get child modules (if this is a parent category)
    public function children()
    {
        return $this->hasMany(Module::class, 'parent', 'name');
    }

    // Get parent module
    public function parentModule()
    {
        return $this->belongsTo(Module::class, 'parent', 'name');
    }
}
