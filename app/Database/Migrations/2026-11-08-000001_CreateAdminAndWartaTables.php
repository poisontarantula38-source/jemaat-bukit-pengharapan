<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminAndWartaTables extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
            ],
            'password_hash' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addKey('username', false, true);
        $this->forge->createTable('admin_users', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 200,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 80,
                'null' => true,
            ],
            'summary' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'content' => [
                'type' => 'MEDIUMTEXT',
            ],
            'is_published' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'published_at' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('slug', false, true);
        $this->forge->addKey(['is_published', 'published_at']);
        $this->forge->createTable('warta_articles', true);
    }

    public function down()
    {
        $this->forge->dropTable('warta_articles', true);
        $this->forge->dropTable('admin_users', true);
    }
}
