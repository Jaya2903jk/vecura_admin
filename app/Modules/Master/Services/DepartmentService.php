<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\DepartmentRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class DepartmentService
{
    public function __construct(private DepartmentRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['IssueDepartment' => $this->repo->paginate($perPage), 'perPage' => $perPage];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $data['status'] = strtolower($data['status']) === 'Active' ? 1 : 0;
            $this->repo->create($data);
            Cache::forget('issue_departments');
            return response()->json(['status' => true, 'message' => 'Department Created Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            // $data['status'] = strtolower($data['status']) === 'Active' ? 1 : 0;
            $model = \App\Models\IssueDepartment::findOrFail($id);
            $this->repo->update($model, $data);
            Cache::forget('issue_departments');
            return response()->json(['status' => true, 'message' => 'Department Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $model = \App\Models\IssueDepartment::findOrFail($id);
            $this->repo->delete($model);
            Cache::forget('issue_departments');
            return response()->json(['status' => true, 'message' => 'Department Deleted Successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
