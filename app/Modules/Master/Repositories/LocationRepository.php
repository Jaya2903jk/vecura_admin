<?php

namespace App\Modules\Master\Repositories;

use App\Models\Location;
use App\Models\City;
use App\Models\NewBranch;

class LocationRepository
{
    public function paginate(int $perPage) { return Location::with(['city.state', 'branch'])->orderBy('location_id', 'asc')->paginate($perPage); }
    public function cities() { return City::where('is_active', 1)->orderBy('city_name')->get(); }
    public function branches() { return NewBranch::where('is_active', 1)->orderBy('branch_name')->get(); }

    public function create(array $d): Location
    {
        return Location::create([
            'location_name' => trim($d['location_name']),
            'city_id'       => $d['city_id'],
            'branch_id'     => $d['branch_id'] ?: null,
            'address'       => $d['address'] ?? null,
            'pincode'       => $d['pincode'] ?? null,
            'latitude'      => $d['latitude'] ?: null,
            'longitude'     => $d['longitude'] ?: null,
            'is_active'     => $d['is_active'],
        ]);
    }

    public function update(int $id, array $d): Location
    {
        $m = Location::findOrFail($id);
        $m->update([
            'location_name' => trim($d['location_name']),
            'city_id'       => $d['city_id'],
            'branch_id'     => $d['branch_id'] ?: null,
            'address'       => $d['address'] ?? null,
            'pincode'       => $d['pincode'] ?? null,
            'latitude'      => $d['latitude'] ?: null,
            'longitude'     => $d['longitude'] ?: null,
            'is_active'     => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { Location::findOrFail($id)->delete(); }
}
