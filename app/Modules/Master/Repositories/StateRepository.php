<?php

namespace App\Modules\Master\Repositories;

use App\Models\State;
use App\Models\Country;

class StateRepository
{
    public function paginate(int $perPage) { return State::with('country')->orderBy('state_id', 'asc')->paginate($perPage); }
    public function countries() { return Country::where('is_active', 1)->orderBy('country_name')->get(); }

    public function create(array $d): State
    {
        return State::create([
            'state_code' => strtoupper(trim($d['state_code'])),
            'state_name' => trim($d['state_name']),
            'country_id' => $d['country_id'],
            'is_active'  => $d['is_active'],
        ]);
    }

    public function update(int $id, array $d): State
    {
        $m = State::findOrFail($id);
        $m->update([
            'state_code' => strtoupper(trim($d['state_code'])),
            'state_name' => trim($d['state_name']),
            'country_id' => $d['country_id'],
            'is_active'  => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { State::findOrFail($id)->delete(); }
}
