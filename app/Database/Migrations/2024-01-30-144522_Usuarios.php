<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Usuarios extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'usuario' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'unique'     => true
            ],
            'password' => [
                'type'       => 'VARCHAR',
                'constraint' => '130',
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'activo' => [
                'type'       => 'TINYINT',
            ],
            'fecha_alta' => [
                'type'           => 'DATETIME',
            ],
            'fecha_modifica' => [
                'type'           => 'DATETIME',
            ],
            'id_sucursal' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,
            ],
            'id_rol' => [
                'type'     => 'SMALLINT',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_rol', 'roles', 'id');
        $this->forge->addForeignKey('id_sucursal', 'sucursal', 'id');
        $this->forge->createTable('usuarios');
    }

    public function down()
    {
        $this->forge->dropTable('usuarios');
    }
}
