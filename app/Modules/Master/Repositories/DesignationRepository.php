<?php

namespace App\Modules\Master\Repositories;

use App\Models\Designation;

class DesignationRepository
{
    public function paginate(int $perPage)
    {
        return Designation::orderBy('id', 'asc')->paginate($perPage);
    }
    public function all()
    {
        return Designation::orderBy('id', 'asc')->get();
    }

    public function create(array $d): Designation
    {
        $code = $this->generateUniqueCode();
        return Designation::create([
            'DesignationCode' => $code,
            'Designation' => $d['designation_name'],
            'status' => $d['status']
        ]);
    }

    public function update(Designation $m, array $d): Designation
    {
        $m->update(['Designation' => $d['designation_name'], 'status' => $d['status']]);
        return $m;
    }

    public function delete(Designation $m): void
    {
        $m->delete();
    }

    private function generateUniqueCode(): string
    {
        $lastDesignation = Designation::orderBy('id', 'desc')->first();
        $lastCode = $lastDesignation ? $lastDesignation->DesignationCode : 'DES-0000';

        // Extract number from code (e.g., "DES-0001" -> 1)
        preg_match('/(\d+)/', $lastCode, $matches);
        $nextNumber = isset($matches[1]) ? intval($matches[1]) + 1 : 1;

        // Format as DES-0001, DES-0002, etc.
        return 'DES-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
