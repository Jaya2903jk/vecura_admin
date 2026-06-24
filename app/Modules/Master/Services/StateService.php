<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\StateRepository;
use Illuminate\Http\JsonResponse;

class StateService
{
    public function __construct(private StateRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return ['states' => $this->repo->paginate($perPage), 'perPage' => $perPage, 'totalCount' => \App\Models\State::count(), 'countries' => $this->repo->countries()];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'State created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $this->repo->update($id, $data);
            return response()->json(['status' => true, 'message' => 'State updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'State deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
