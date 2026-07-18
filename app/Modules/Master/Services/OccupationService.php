<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\OccupationRepository;
use Illuminate\Http\JsonResponse;

class OccupationService
{
    public function __construct(private OccupationRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return [
            'occupations' => $this->repo->paginate($perPage),
            'perPage' => $perPage,
            'totalCount' => \App\Models\OccupationMaster::count(),
        ];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Occupation created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Occupation updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Occupation deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
