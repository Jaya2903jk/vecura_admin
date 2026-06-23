<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\DepartmentRequest;
use App\Modules\Master\Services\DepartmentService;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function __construct(private DepartmentService $service) {}

    public function index(Request $request)
    {
        return view('department.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(DepartmentRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(DepartmentRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
