<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\BranchRequest;
use App\Modules\Master\Services\BranchService;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function __construct(private BranchService $service) {}

    public function index(Request $request)
    {
        return view('new-branch.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(BranchRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(BranchRequest $request, $id)
    {
        if ($request->boolean('_status_only')) {
            return $this->service->update($id, $request->only(['is_active']));
        }
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
