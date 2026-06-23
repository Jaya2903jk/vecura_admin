<?php

namespace App\Modules\Master\Repositories;

use App\Models\Country;

class CountryRepository
{
    public function paginate(int $perPage) { return Country::orderBy('country_id', 'asc')->paginate($perPage); }

    public function create(array $d): Country
    {
        return Country::create([
            'country_code' => strtoupper(trim($d['country_code'])),
            'country_name' => trim($d['country_name']),
            'is_active'    => $d['is_active'],
        ]);
    }

    public function update(int $id, array $d): Country
    {
        $m = Country::findOrFail($id);
        $m->update([
            'country_code' => strtoupper(trim($d['country_code'])),
            'country_name' => trim($d['country_name']),
            'is_active'    => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { Country::findOrFail($id)->delete(); }
}
