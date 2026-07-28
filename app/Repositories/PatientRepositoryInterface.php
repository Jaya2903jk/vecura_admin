<?php

namespace App\Repositories;

interface PatientRepositoryInterface
{
    public function getAll($filters = [], $perPage = 10);

    public function getById($id);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getCountByStatus();

    public function getRecentRegistrations($limit = 10);

    public function getTreatmentJoinedCount();

    public function getFilterOptions();
}
