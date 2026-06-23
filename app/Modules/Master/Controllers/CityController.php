<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\CityRequest;
use App\Modules\Master\Services\CityService;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(private CityService $service) {}

    public function index(Request $request)
    {
        return view('city.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(CityRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(CityRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
