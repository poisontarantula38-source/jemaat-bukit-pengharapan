<?php

namespace App\Models;

use CodeIgniter\Model;

class ContactMessageModel extends Model
{
    protected $table = 'contact_messages';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $allowedFields = [
        'sender_name',
        'sender_email',
        'subject',
        'message',
        'status',
        'assigned_admin_id',
        'assigned_admin_username',
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function preserveAssignmentsForDeletedAdmin(int $adminId, string $username): bool
    {
        return $this->builder()
            ->where('assigned_admin_id', $adminId)
            ->update([
                'assigned_admin_id' => null,
                'assigned_admin_username' => $username,
            ]);
    }
}
