<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\RegTypeRequest;
use App\Modules\Master\Services\RegTypeService;
use Illuminate\Http\Request;

class RegTypeController extends Controller
{
    public function __construct(private RegTypeService $service) {}

    public function index(Request $request)
    {
        return view('regtype.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(RegTypeRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(RegTypeRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
