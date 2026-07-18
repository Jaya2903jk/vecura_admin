<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\KnownByRequest;
use App\Modules\Master\Services\KnownByService;
use Illuminate\Http\Request;

class KnownByController extends Controller
{
    public function __construct(private KnownByService $service) {}

    public function index(Request $request)
    {
        return view('knownby.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(KnownByRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(KnownByRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
