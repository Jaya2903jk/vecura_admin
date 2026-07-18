<?php

namespace App\Modules\Master\Repositories;

use App\Models\OccupationMaster;

class OccupationRepository
{
    public function paginate(int $perPage)
    {
        return OccupationMaster::orderBy('occupation_id', 'asc')->paginate($perPage);
    }

    public function create(array $d): OccupationMaster
    {
        return OccupationMaster::create([
            'occupation_name' => trim($d['occupation_name']),
            'occupationType' => $d['occupationType'] ?? null,
            'createdby' => auth()->user()->id ?? null,
            'modifiedby' => auth()->user()->id ?? null,
        ]);
    }

    public function update(int $id, array $d): OccupationMaster
    {
        $m = OccupationMaster::findOrFail($id);
        $m->update([
            'occupation_name' => trim($d['occupation_name']),
            'occupationType' => $d['occupationType'] ?? null,
            'modifiedby' => auth()->user()->id ?? null,
        ]);
        return $m;
    }

    public function delete(int $id): void
    {
        OccupationMaster::findOrFail($id)->delete();
    }
}
