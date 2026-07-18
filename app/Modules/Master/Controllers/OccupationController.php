<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\OccupationRequest;
use App\Modules\Master\Services\OccupationService;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function __construct(private OccupationService $service) {}

    public function index(Request $request)
    {
        return view('occupation.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(OccupationRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(OccupationRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
