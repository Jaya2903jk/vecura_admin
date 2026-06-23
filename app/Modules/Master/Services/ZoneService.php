<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\ZoneRepository;
use Illuminate\Http\JsonResponse;

class ZoneService
{
    public function __construct(private ZoneRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['zones' => $this->repo->paginate($perPage), 'perPage' => $perPage, 'totalCount' => \App\Models\Zone::count(), 'countries' => $this->repo->countries()];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Zone created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Zone updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Zone deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
