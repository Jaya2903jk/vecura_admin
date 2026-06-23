<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\ZoneRequest;
use App\Modules\Master\Services\ZoneService;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function __construct(private ZoneService $service) {}

    public function index(Request $request)
    {
        return view('zone.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(ZoneRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(ZoneRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
