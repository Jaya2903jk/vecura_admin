<?php

namespace App\Modules\Master\Repositories;

use App\Models\Zone;
use App\Models\Country;

class ZoneRepository
{
    public function paginate(int $perPage) { return Zone::with('country')->orderBy('zone_id', 'asc')->paginate($perPage); }
    public function countries() { return Country::where('is_active', 1)->orderBy('country_name')->get(); }

    public function create(array $d): Zone
    {
        return Zone::create([
            'zone_code'   => strtoupper(trim($d['zone_code'])),
            'zone_name'   => trim($d['zone_name']),
            'country_id'  => $d['country_id'],
            'region_type' => $d['region_type'] ?? null,
            'is_active'   => $d['is_active'],
        ]);
    }

    public function update(int $id, array $d): Zone
    {
        $m = Zone::findOrFail($id);
        $m->update([
            'zone_code'   => strtoupper(trim($d['zone_code'])),
            'zone_name'   => trim($d['zone_name']),
            'country_id'  => $d['country_id'],
            'region_type' => $d['region_type'] ?? null,
            'is_active'   => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { Zone::findOrFail($id)->delete(); }
}
