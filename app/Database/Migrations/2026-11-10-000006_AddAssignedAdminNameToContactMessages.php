<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignedAdminNameToContactMessages extends Migration
{
    public function up()
    {
        $this->forge->addColumn('contact_messages', [
            'assigned_admin_username' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('contact_messages', 'assigned_admin_username');
    }
}
