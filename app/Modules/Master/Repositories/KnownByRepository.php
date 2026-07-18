<?php

namespace App\Modules\Master\Repositories;

use App\Models\KnownByMaster;

class KnownByRepository
{
    public function paginate(int $perPage)
    {
        return KnownByMaster::orderBy('Knwid', 'asc')->paginate($perPage);
    }

    public function create(array $d): KnownByMaster
    {
        return KnownByMaster::create([
            'KnwCode' => $d['KnwCode'] ?? null,
            'KwnBy' => trim($d['KwnBy']),
            'kstatus' => $d['kstatus'] ?? 'Active',
            'digital' => $d['digital'] ?? null,
            'CreatedBy' => auth()->user()->id ?? null,
            'ModifiedBy' => auth()->user()->id ?? null,
        ]);
    }

    public function update(int $id, array $d): KnownByMaster
    {
        $m = KnownByMaster::findOrFail($id);
        $m->update([
            'KnwCode' => $d['KnwCode'] ?? null,
            'KwnBy' => trim($d['KwnBy']),
            'kstatus' => $d['kstatus'] ?? 'Active',
            'digital' => $d['digital'] ?? null,
            'ModifiedBy' => auth()->user()->id ?? null,
        ]);
        return $m;
    }

    public function delete(int $id): void
    {
        KnownByMaster::findOrFail($id)->delete();
    }
}
