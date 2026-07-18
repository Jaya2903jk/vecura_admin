<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\KnownByRepository;
use Illuminate\Http\JsonResponse;

class KnownByService
{
    public function __construct(private KnownByRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return [
            'knownbys' => $this->repo->paginate($perPage),
            'perPage' => $perPage,
            'totalCount' => \App\Models\KnownByMaster::count(),
        ];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Known By created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Known By updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Known By deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
