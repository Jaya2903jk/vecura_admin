<?php

namespace App\Http\Controllers;
use App\Models\Designation;
use Illuminate\Http\Request;

class DesginationController extends Controller {

    public function index( Request $request ) {
        $perPage = $request->get( 'per_page', 10 );

        $designations = Designation::orderBy( 'id', 'asc' )
        ->paginate( $perPage );

        return view( 'designation.index', compact( 'designations', 'perPage' ) );
    }

    public function exportExcel() {
        $designations = Designation::orderBy('id', 'asc')->get();

        $headers = [
            'Content-Type'        => 'application/vnd.ms-excel',
            'Content-Disposition' => 'attachment; filename="designations_' . date('Y-m-d') . '.xls"',
        ];

        $html  = '<table border="1">';
        $html .= '<thead><tr><th>Designation Code</th><th>Designation</th><th>Status</th></tr></thead>';
        $html .= '<tbody>';
        foreach ($designations as $des) {
            $status = $des->status == 0 ? 'Active' : 'Inactive';
            $html  .= '<tr>';
            $html  .= '<td>' . htmlspecialchars($des->DesignationCode) . '</td>';
            $html  .= '<td>' . htmlspecialchars($des->Designation) . '</td>';
            $html  .= '<td>' . $status . '</td>';
            $html  .= '</tr>';
        }
        $html .= '</tbody></table>';

        return response($html, 200, $headers);
    }
}
