<?php

namespace App\Http\Controllers;

use App\Models\ServiceMaster;
use App\Models\UserMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiceMasterController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search', '');
        $category = $request->get('category', '');
        $status = $request->get('status', '');

        $query = ServiceMaster::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ServiceName', 'like', "%{$search}%")
                    ->orWhere('ServiceCode', 'like', "%{$search}%")
                    ->orWhere('Category', 'like', "%{$search}%")
                    ->orWhere('SACNumber', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('Category', $category);
        }

        if ($status) {
            $query->where('Status', 'like', "%{$status}%");
        }

        $services = $query->orderBy('Serviceid', 'desc')->paginate($perPage);

        // Fetch distinct categories for filter dropdown
        $categoriesList = DB::table('Servicemaster')
            ->whereNotNull('Category')
            ->where('Category', '!=', '')
            ->distinct()
            ->pluck('Category')
            ->toArray();

        if ($request->ajax()) {
            return response()->json([
                'services' => $services->items(),
                'total' => $services->total(),
                'per_page' => $services->perPage(),
                'current_page' => $services->currentPage()
            ]);
        }

        return view('services.index', [
            'services' => $services,
            'categories' => $categoriesList,
            'perPage' => $perPage,
            'search' => $search,
            'category' => $category,
            'status' => $status,
        ]);
    }

    public function generateServiceCode()
    {
        try {
            $lastService = DB::table('Servicemaster')
                ->whereNotNull('ServiceCode')
                ->where('ServiceCode', 'like', 'SER-%')
                ->orderBy('Serviceid', 'desc')
                ->first(['ServiceCode']);

            $lastNum = 0;
            if ($lastService && $lastService->ServiceCode) {
                preg_match('/(\d+)/', $lastService->ServiceCode, $matches);
                $lastNum = isset($matches[1]) ? intval($matches[1]) : 0;
            }

            $nextCode = 'SER-' . str_pad($lastNum + 1, 4, '0', STR_PAD_LEFT);

            return response()->json([
                'status' => true,
                'service_code' => $nextCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'service_code' => 'nullable|string|max:50',
            'rate' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'gst_per_value_s' => 'nullable|numeric|min:0',
            'sac_number' => 'nullable|string|max:50',
            'incentive_amt' => 'nullable|numeric|min:0',
            'surgery' => 'nullable|in:Yes,No',
            'kid_type' => 'nullable|in:Kit,non-kit',
            'service_qty' => 'nullable|integer|min:1',
            'status' => 'required|in:Active,In Active',
        ]);

        try {
            $userCode = optional(UserMaster::find(session('user_id')))->UserCode ?: 'Bol1';
            $serviceCode = $validated['service_code'] ?? 'SER-' . time();

            $service = ServiceMaster::create([
                'ServiceCode' => $serviceCode,
                'ServiceName' => $validated['service_name'],
                'Rate' => $validated['rate'],
                'Category' => $validated['category'] ?? 'Others',
                'GSTPerValueS' => $validated['gst_per_value_s'] ?? 5.0,
                'SACNumber' => $validated['sac_number'] ?? '999722',
                'Incentiveamt' => $validated['incentive_amt'] ?? 0.0,
                'surgery' => $validated['surgery'] ?? 'No',
                'kidType' => $validated['kid_type'] ?? 'non-kit',
                'ServiceQty' => $validated['service_qty'] ?? 1,
                'Status' => $validated['status'],
                'Type' => 'Others',
                'ConversationRatio' => 'No',
                'ServiceChargeEditable' => 'No',
                'CreatedBy' => $userCode,
                'CreatedDate' => now(),
                'ModifiedBy' => $userCode,
                'ModifiedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Service Master created successfully',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $service = ServiceMaster::findOrFail($id);
            return response()->json([
                'status' => true,
                'service' => $service
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Service not found: ' . $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $service = ServiceMaster::findOrFail($id);

        $validated = $request->validate([
            'service_name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'category' => 'nullable|string|max:100',
            'gst_per_value_s' => 'nullable|numeric|min:0',
            'sac_number' => 'nullable|string|max:50',
            'incentive_amt' => 'nullable|numeric|min:0',
            'surgery' => 'nullable|in:Yes,No',
            'status' => 'required|in:Active,In Active',
        ]);

        try {
            $userCode = optional(UserMaster::find(session('user_id')))->UserCode ?: 'Bol1';

            $service->update([
                'ServiceName' => $validated['service_name'],
                'Rate' => $validated['rate'],
                'Category' => $validated['category'] ?? $service->Category,
                'GSTPerValueS' => $validated['gst_per_value_s'] ?? $service->GSTPerValueS,
                'SACNumber' => $validated['sac_number'] ?? $service->SACNumber,
                'Incentiveamt' => $validated['incentive_amt'] ?? $service->Incentiveamt,
                'surgery' => $validated['surgery'] ?? $service->surgery,
                'Status' => $validated['status'],
                'ModifiedBy' => $userCode,
                'ModifiedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Service Master updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus(Request $request, $id)
    {
        try {
            $service = ServiceMaster::findOrFail($id);
            $newStatus = $request->input('status');

            if (!in_array($newStatus, ['Active', 'In Active'])) {
                $newStatus = (trim($service->Status) === 'Active') ? 'In Active' : 'Active';
            }

            $userCode = optional(UserMaster::find(session('user_id')))->UserCode ?: 'Bol1';

            $service->update([
                'Status' => $newStatus,
                'ModifiedBy' => $userCode,
                'ModifiedDate' => now(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Service status updated to ' . $newStatus,
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
