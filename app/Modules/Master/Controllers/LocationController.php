<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\LocationRequest;
use App\Modules\Master\Services\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function __construct(private LocationService $service) {}

    public function index(Request $request)
    {
        return view('location.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(LocationRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(LocationRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
