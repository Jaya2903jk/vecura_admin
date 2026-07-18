<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\RegTypeRepository;
use Illuminate\Http\JsonResponse;

class RegTypeService
{
    public function __construct(private RegTypeRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return [
            'regtypes' => $this->repo->paginate($perPage),
            'perPage' => $perPage,
            'totalCount' => \App\Models\RegTypeMaster::count(),
        ];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Registration Type created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Registration Type updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Registration Type deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
