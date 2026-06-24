<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\StateRequest;
use App\Modules\Master\Services\StateService;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function __construct(private StateService $service) {}

    public function index(Request $request)
    {
        return view('state.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(StateRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(StateRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
