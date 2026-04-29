<?php

namespace App\Models;

use App\Models\ApprovalFlow;
use Illuminate\Database\Eloquent\Model;
use App\Models\IssueDepartment;
use App\Models\IssueCategory;

class IssueMaster extends Model {
    //
    protected $table = 'issueMasterTest';

    protected $primaryKey = 'IssueId';

    public $timestamps = false;
    protected $fillable = [
        'DepartmentId',
        'CategoryId',
        'IssueName',
        'Status',
        'Level1Role',
        'Level2Role',
        'Level3Role',
        'Level4Role',
        'Level5Role',
    ];

    public function approvalFlows() {
        return $this->hasMany( ApprovalFlow::class, 'issueId', 'IssueId' )->orderBy( 'levelOrder' );
    }

    public function department() {
        return $this->belongsTo( IssueDepartment::class, 'DepartmentId', 'Departmentid' );
    }

    public function category() {
        return $this->belongsTo( IssueCategory::class, 'CategoryId', 'category_id' );
    }
}
