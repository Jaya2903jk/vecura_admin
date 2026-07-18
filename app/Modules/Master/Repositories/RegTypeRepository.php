<?php

namespace App\Modules\Master\Repositories;

use App\Models\RegTypeMaster;

class RegTypeRepository
{
    public function paginate(int $perPage)
    {
        return RegTypeMaster::orderBy('RegTypeId', 'asc')->paginate($perPage);
    }

    public function create(array $d): RegTypeMaster
    {
        return RegTypeMaster::create([
            'RegType' => trim($d['RegType']),
        ]);
    }

    public function update(int $id, array $d): RegTypeMaster
    {
        $m = RegTypeMaster::findOrFail($id);
        $m->update([
            'RegType' => trim($d['RegType']),
        ]);
        return $m;
    }

    public function delete(int $id): void
    {
        RegTypeMaster::findOrFail($id)->delete();
    }
}
