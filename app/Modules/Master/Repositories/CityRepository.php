<?php

namespace App\Modules\Master\Repositories;

use App\Models\City;
use App\Models\State;

class CityRepository
{
    public function paginate(int $perPage) { return City::with('state')->orderBy('city_id', 'asc')->paginate($perPage); }
    public function states() { return State::where('is_active', 1)->orderBy('state_name')->get(); }

    public function create(array $d): City
    {
        return City::create([
            'city_name' => trim($d['city_name']),
            'state_id'  => $d['state_id'],
            'pincode'   => $d['pincode'] ?? null,
            'is_active' => $d['is_active'],
        ]);
    }

    public function update(int $id, array $d): City
    {
        $m = City::findOrFail($id);
        $m->update([
            'city_name' => trim($d['city_name']),
            'state_id'  => $d['state_id'],
            'pincode'   => $d['pincode'] ?? null,
            'is_active' => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { City::findOrFail($id)->delete(); }
}
