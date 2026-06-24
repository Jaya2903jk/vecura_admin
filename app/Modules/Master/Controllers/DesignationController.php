<?php

namespace App\Modules\Master\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Master\Requests\DesignationRequest;
use App\Modules\Master\Services\DesignationService;
use Illuminate\Http\Request;

class DesignationController extends Controller
{
    public function __construct(private DesignationService $service) {}

    public function index(Request $request)
    {
        return view('designation.index', $this->service->getAll($request->get('per_page', 10)));
    }

    public function store(DesignationRequest $request)
    {
        return $this->service->create($request->validated());
    }

    public function update(DesignationRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }

    public function exportExcel()
    {
        $designations = $this->service->exportData();
        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="designations_' . date('Y-m-d') . '.xls"',
        ];
        $html  = '<table border="1"><thead><tr><th>Designation Code</th><th>Designation</th><th>Status</th></tr></thead><tbody>';
        foreach ($designations as $des) {
            $html .= '<tr><td>' . htmlspecialchars($des->DesignationCode) . '</td><td>' . htmlspecialchars($des->Designation) . '</td><td>' . ($des->status == 0 ? 'Active' : 'Inactive') . '</td></tr>';
        }
        $html .= '</tbody></table>';
        return response($html, 200, $headers);
    }
}
