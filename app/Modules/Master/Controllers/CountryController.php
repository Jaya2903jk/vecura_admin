<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\CountryRequest;
use App\Modules\Master\Services\CountryService;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function __construct(private CountryService $service) {}

    public function index(Request $request)
    {
        return view('country.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(CountryRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(CountryRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}
