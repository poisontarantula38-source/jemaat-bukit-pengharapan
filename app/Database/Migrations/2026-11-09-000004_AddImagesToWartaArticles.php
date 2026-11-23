<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddImagesToWartaArticles extends Migration
{
    public function up()
    {
        $this->forge->addColumn('warta_articles', [
            'image_filename' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'summary',
            ],
            'image_alt' => [
                'type' => 'VARCHAR',
                'constraint' => 250,
                'null' => true,
                'after' => 'image_filename',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('warta_articles', ['image_filename', 'image_alt']);
    }
}
