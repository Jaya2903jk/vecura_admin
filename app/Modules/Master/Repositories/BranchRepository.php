<?php

namespace App\Modules\Master\Repositories;

use App\Models\NewBranch;
use App\Models\Zone;
use App\Models\City;

class BranchRepository
{
    public function paginate(int $perPage) { return NewBranch::with(['zone', 'city', 'wallet'])->orderBy('branch_id', 'asc')->paginate($perPage); }
    public function zones() { return Zone::where('is_active', 1)->orderBy('zone_name')->get(); }
    public function cities() { return City::where('is_active', 1)->orderBy('city_name')->get(); }
    public function find(int $id): NewBranch { return NewBranch::findOrFail($id); }

    public function create(array $d): NewBranch
    {
        return NewBranch::create([
            'branch_name'  => trim($d['branch_name']),
            'branch_code'  => strtoupper(trim($d['branch_code'])),
            'zone_id'      => $d['zone_id'],
            'city_id'      => $d['city_id'],
            'manager_name' => $d['manager_name'] ?? null,
            'contact_no'   => $d['contact_no'] ?? null,
            'is_active'    => $d['is_active'],
        ]);
    }

    public function update(NewBranch $m, array $d): NewBranch
    {
        $m->update([
            'branch_name'  => trim($d['branch_name']),
            'branch_code'  => strtoupper(trim($d['branch_code'])),
            'zone_id'      => $d['zone_id'],
            'city_id'      => $d['city_id'],
            'manager_name' => $d['manager_name'] ?? null,
            'contact_no'   => $d['contact_no'] ?? null,
            'is_active'    => $d['is_active'],
        ]);
        return $m;
    }

    public function delete(int $id): void { NewBranch::findOrFail($id)->delete(); }
}
