<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Roles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'permisos' => [
                'type'       => 'TEXT',
                'null' => true
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
            ]
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');
    }

    public function down()
    {
        $this->forge->dropTable('roles');
    }
}
