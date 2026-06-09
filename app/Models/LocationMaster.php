<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationMaster extends Model {
    protected $connection = 'sqlsrv';
    protected $table = 'LocationMaster';
    protected $primaryKey = 'LocationID';
    public $timestamps = false;

    protected $fillable = [
        'LocationName',
        'LocationCode',
        'LocationAddress',
        'StateCode',
        'CityCode',
        'LocPinCode',
        'ZoneState',
        'CountryCode',
        'LStatus',
        'GSTNo',
        'phoneno'
    ];
    public function state()
    {
        return $this->belongsTo(StateMaster::class, 'StateCode', 'state_id');
    }
    public function city()
    {
        return $this->belongsTo(CityMaster::class, 'CityCode', 'city_id');
    }
    public function zone()
    {
        return $this->belongsTo(ZoneMaster::class, 'ZoneState', 'zone_code');
    }
}
