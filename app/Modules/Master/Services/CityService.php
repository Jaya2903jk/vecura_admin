<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\CityRepository;
use Illuminate\Http\JsonResponse;

class CityService
{
    public function __construct(private CityRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['cities' => $this->repo->paginate($perPage), 'perPage' => $perPage, 'totalCount' => \App\Models\City::count(), 'states' => $this->repo->states()];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'City created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'City updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'City deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
