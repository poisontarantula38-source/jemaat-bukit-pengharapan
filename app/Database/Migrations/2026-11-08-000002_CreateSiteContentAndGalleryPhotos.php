<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSiteContentAndGalleryPhotos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'content_key' => [
                'type' => 'VARCHAR',
                'constraint' => 120,
            ],
            'content_value' => [
                'type' => 'TEXT',
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
        $this->forge->addKey('content_key', true);
        $this->forge->createTable('site_content', true);

        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'filename' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'caption' => [
                'type' => 'VARCHAR',
                'constraint' => 180,
            ],
            'alt_text' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
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
        $this->forge->addKey('created_at');
        $this->forge->createTable('gallery_photos', true);
    }

    public function down()
    {
        $this->forge->dropTable('gallery_photos', true);
        $this->forge->dropTable('site_content', true);
    }
}
