<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Permisos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'permiso' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'activo' => [
                'type' => 'TINYINT',
                'default' => 1
            ],
            'fecha_alta' => [
                'type'           => 'DATETIME',
            ],
            'fecha_modifica' => [
                'type'           => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('permisos');
    }

    public function down()
    {
        $this->forge->dropTable('permisos');
    }
}
