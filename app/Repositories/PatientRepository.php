<?php

namespace App\Repositories;

use App\Models\PatientPersonalDetail;
use Illuminate\Pagination\Paginator;

class PatientRepository implements PatientRepositoryInterface
{
    protected $model;

    public function __construct(PatientPersonalDetail $model)
    {
        $this->model = $model;
    }

    public function getAll($filters = [], $perPage = 10)
    {
        $query = $this->model->with('location');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('FirstName', 'LIKE', "%{$search}%")
                    ->orWhere('LastName', 'LIKE', "%{$search}%")
                    ->orWhere('Mobile', 'LIKE', "%{$search}%")
                    ->orWhere('EMail', 'LIKE', "%{$search}%")
                    ->orWhere('RegistrationNo', 'LIKE', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('CustomerStatus', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('Type', $filters['type']);
        }

        if (!empty($filters['treatment_joined'])) {
            $query->where('TreatmentJoined', $filters['treatment_joined']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('RegistrationDate', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('RegistrationDate', '<=', $filters['date_to']);
        }

        if (!empty($filters['city'])) {
            $query->where('City', $filters['city']);
        }

        if (!empty($filters['doctor'])) {
            $query->where('Doctorname', $filters['doctor']);
        }

        return $query->orderBy('RegistrationDate', 'DESC')->paginate($perPage);
    }

    public function getById($id)
    {
        return $this->model->with('location')->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $patient = $this->model->findOrFail($id);
        $patient->update($data);
        return $patient;
    }

    public function delete($id)
    {
        return $this->model->findOrFail($id)->delete();
    }

    public function getCountByStatus()
    {
        return $this->model
            ->selectRaw('CustomerStatus, COUNT(*) as count')
            ->groupBy('CustomerStatus')
            ->get()
            ->pluck('count', 'CustomerStatus')
            ->toArray();
    }

    public function getRecentRegistrations($limit = 10)
    {
        return $this->model
            ->orderBy('RegistrationDate', 'DESC')
            ->limit($limit)
            ->get();
    }

    public function getTreatmentJoinedCount()
    {
        return $this->model
            ->where('TreatmentJoined', 'Yes')
            ->count();
    }

    public function getFilterOptions()
    {
        return [
            'statuses' => $this->model->distinct('CustomerStatus')->pluck('CustomerStatus'),
            'types' => $this->model->distinct('Type')->pluck('Type'),
            'doctors' => $this->model->distinct('Doctorname')->pluck('Doctorname'),
            'cities' => $this->model->distinct('City')->pluck('City'),
        ];
    }
}
