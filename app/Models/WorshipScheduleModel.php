<?php

namespace App\Models;

use CodeIgniter\Model;

class WorshipScheduleModel extends Model
{
    protected $table = 'worship_schedules';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'service_date',
        'service_type',
        'service_time',
        'location',
        'minister',
        'notes',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function upcomingForPublic(): array
    {
        return $this->where('service_date >=', date('Y-m-d'))
            ->orderBy('service_date', 'ASC')
            ->orderBy('service_time', 'ASC')
            ->findAll();
    }
}
