<?php

namespace App\Modules\Master\Services;

use App\Modules\Master\Repositories\BranchRepository;
use Illuminate\Http\JsonResponse;

class BranchService
{
    public function __construct(private BranchRepository $repo) {}

    public function getAll(int $perPage): array
    {
        return [
            'branches'   => $this->repo->paginate($perPage),
            'perPage'    => $perPage,
            'totalCount' => \App\Models\NewBranch::count(),
            'zones'      => $this->repo->zones(),
            'cities'     => $this->repo->cities(),
        ];
    }

    public function create(array $data): JsonResponse
    {
        try {
            $this->repo->create($data);
            return response()->json(['status' => true, 'message' => 'Branch created successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function update(int $id, array $data): JsonResponse
    {
        try {
            $branch = $this->repo->find($id);
            if (isset($data['_status_only']) && $data['_status_only']) {
                $branch->update(['is_active' => $data['is_active']]);
                return response()->json(['status' => true, 'message' => 'Status updated successfully']);
            }
            $this->repo->update($branch, $data);
            return response()->json(['status' => true, 'message' => 'Branch updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return response()->json(['status' => true, 'message' => 'Branch deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!'], 500);
        }
    }
}
