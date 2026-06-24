<?php

namespace App\Modules\Master\Repositories;

use App\Models\IssueDepartment;

class DepartmentRepository
{
    public function paginate(int $perPage)
    {
        return IssueDepartment::orderBy('Departmentid', 'asc')->paginate($perPage);
    }
    public function create(array $d): IssueDepartment
    {
        return IssueDepartment::create(
            ['DepartmentName' => $d['department_name'],
            'Status' => $d['status']
            ]);
    }
    public function update(IssueDepartment $m, array $d): IssueDepartment
    {
        $m->update(['DepartmentName' => $d['department_name'], 'Status' => $d['status']]);
        return $m;
    }
    public function delete(IssueDepartment $m): void
    {
        $m->delete();
    }
}
