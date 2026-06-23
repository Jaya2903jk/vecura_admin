<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\DesignationRepository;
use Illuminate\Http\JsonResponse;

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
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Designation Created Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $model = \App\Models\Designation::findOrFail($id);
            $this->repo->update($model, $data);
            return response()->json(['status' => true, 'message' => 'Designation Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = \App\Models\Designation::findOrFail($id);
            $this->repo->delete($model);
            return response()->json(['status' => true, 'message' => 'Designation Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function exportData() { return $this->repo->all(); }
}
