<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\LocationRepository;
use Illuminate\Http\JsonResponse;

class LocationService
{
    public function __construct(private LocationRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return [
            'locations'  => $this->repo->paginate($perPage),
            'perPage'    => $perPage,
            'totalCount' => \App\Models\Location::count(),
            'cities'     => $this->repo->cities(),
            'branches'   => $this->repo->branches(),
        ];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Location created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Location updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Location deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
