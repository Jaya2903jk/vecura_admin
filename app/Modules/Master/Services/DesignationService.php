<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\DesignationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DesignationService
{
    public function __construct(private DesignationRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['designations' => $this->repo->paginate($perPage), 'perPage' => $perPage];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $designation = $this->repo->create($data);

            // Map departments if provided
            if (!empty($data['department_ids'])) {
                $this->mapDepartments($designation, $data['department_ids']);
            }

            Cache::forget('designations_list');
            Cache::forget('issue_departments');

            return response()->json(['status' => true, 'message' => 'Designation Created Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = \App\Models\Designation::findOrFail($id);
            $this->repo->update($model, $data);

            // Update department mappings if provided
            if (isset($data['department_ids'])) {
                $this->mapDepartments($model, $data['department_ids']);
            }

            Cache::forget('designations_list');
            Cache::forget('issue_departments');

            return response()->json(['status' => true, 'message' => 'Designation Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function mapDepartments(\App\Models\Designation $designation, array $departmentIds): void
{
    // Delete existing mappings
    $designation->departmentMappings()->delete();
    
    // Create new mappings
    foreach ($departmentIds as $deptId) {
        \App\Models\DesignationDepartment::create([
            'designation_id' => $designation->id,  // ✅ Add this!
            'designation_code' => $designation->DesignationCode,
            'department_id' => (int)$deptId,
            'is_active' => true,
        ]);
    }
}
    public function delete(int $id): JsonResponse
    {
        try {
            $model = \App\Models\Designation::findOrFail($id);
            $this->repo->delete($model);

            Cache::forget('designations_list');
            Cache::forget('issue_departments');

            return response()->json(['status' => true, 'message' => 'Designation Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function exportData() { return $this->repo->all(); }
}
