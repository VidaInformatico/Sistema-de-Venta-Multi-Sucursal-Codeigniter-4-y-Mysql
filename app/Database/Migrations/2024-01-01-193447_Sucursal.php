<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Sucursal extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'telefono' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'correo' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'direccion' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'mensaje' => [
                'type'       => 'TEXT'
            ],
            'folio_venta' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'activo' => [
                'type' => 'TINYINT',
            ],
            'fecha_alta' => [
                'type'           => 'DATETIME',
            ],
            'fecha_modifica' => [
                'type'           => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('sucursal');
    }

    public function down()
    {
        $this->forge->dropTable('sucursal');
    }
}
