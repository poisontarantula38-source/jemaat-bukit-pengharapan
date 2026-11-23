<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdminRolesSchedulesAndContactInbox extends Migration
{
    public function up()
    {
        $this->forge->addColumn('admin_users', [
            'role' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'editor',
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
        ]);

        $firstAdmin = $this->db->table('admin_users')
            ->select('id')
            ->orderBy('id', 'ASC')
            ->get(1)
            ->getRowArray();
        if ($firstAdmin !== null) {
            $this->db->table('admin_users')
                ->where('id', $firstAdmin['id'])
                ->update(['role' => 'superadmin']);
        }

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'service_date' => [
                'type' => 'DATE',
            ],
            'service_type' => [
                'type' => 'VARCHAR',
                'constraint' => 160,
            ],
            'service_time' => [
                'type' => 'TIME',
            ],
            'location' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
            ],
            'minister' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['service_date', 'service_time']);
        $this->forge->createTable('worship_schedules', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sender_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'sender_email' => [
                'type' => 'VARCHAR',
                'constraint' => 254,
            ],
            'subject' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'default' => 'baru',
            ],
            'assigned_admin_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['status', 'created_at']);
        $this->forge->addKey('assigned_admin_id');
        $this->forge->createTable('contact_messages', true);
    }

    public function down()
    {
        $this->forge->dropTable('contact_messages', true);
        $this->forge->dropTable('worship_schedules', true);
        $this->forge->dropColumn('admin_users', ['role', 'is_active']);
    }
}
