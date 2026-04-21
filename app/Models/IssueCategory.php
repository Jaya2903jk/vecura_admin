<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssueCategory extends Model {
    //
    protected $table = 'issue_categories';

    protected $primaryKey = 'category_id';

    public $timestamps = false;

    protected $fillable = [
        'category_name',
        'description',
        'status',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(IssueDepartment::class, 'department_id', 'Departmentid');
    }
}
