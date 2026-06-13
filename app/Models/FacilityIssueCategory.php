<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FacilityIssueCategory extends Model
{
    protected $table    = 'facility_issue_category';
    protected $fillable = ['name', 'description', 'status'];
}
