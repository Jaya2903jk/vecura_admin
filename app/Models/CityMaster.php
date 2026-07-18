<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CityMaster extends Model
{
    // CityMaster.php
    protected $table = 'City_Master';
    protected $primaryKey = 'city_id';
    public $incrementing = false;
    public $timestamps = false;
}
