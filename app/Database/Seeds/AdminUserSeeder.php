<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $username = trim((string) env('ADMIN_USERNAME', 'admin'));
        $password = (string) env('ADMIN_PASSWORD', '');

        if ($username === '' || strlen($password) < 12) {
            throw new RuntimeException(
                'Set ADMIN_USERNAME and an ADMIN_PASSWORD with at least 12 characters in .env before running this seeder.',
            );
        }

        $users = $this->db->table('admin_users');
        if ($users->where('username', $username)->countAllResults() > 0) {
            return;
        }

        $users->insert([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'superadmin',
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
