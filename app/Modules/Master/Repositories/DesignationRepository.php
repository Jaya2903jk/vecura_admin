<?php

namespace App\Modules\Master\Repositories;

use App\Models\Designation;

class DesignationRepository
{
    public function paginate(int $perPage) { return Designation::orderBy('id', 'asc')->paginate($perPage); }
    public function all() { return Designation::orderBy('id', 'asc')->get(); }
    public function create(array $d): Designation { return Designation::create(['Designation' => $d['designation_name'], 'status' => $d['status']]); }
    public function update(Designation $m, array $d): Designation { $m->update(['Designation' => $d['designation_name'], 'status' => $d['status']]); return $m; }
    public function delete(Designation $m): void { $m->delete(); }
}
