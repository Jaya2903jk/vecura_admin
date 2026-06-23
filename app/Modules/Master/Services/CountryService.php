<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\CountryRepository;
use Illuminate\Http\JsonResponse;

class CountryService
{
    public function __construct(private CountryRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['countries' => $this->repo->paginate($perPage), 'perPage' => $perPage, 'totalCount' => \App\Models\Country::count()];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Country created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'Country updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Country deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
