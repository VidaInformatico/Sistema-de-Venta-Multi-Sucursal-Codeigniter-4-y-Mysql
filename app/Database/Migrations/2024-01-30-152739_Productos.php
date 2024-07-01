<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Productos extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'SMALLINT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'codigo' => [
                'type'       => 'VARCHAR',
                'constraint' => '20'
            ],
            'nombre' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'precio' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'inventariable' => [
                'type' => 'TINYINT',
            ],
            'existencia' => [
                'type' => 'SMALLINT',
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
            'id_sucursal' => [
                'type'     => 'SMALLINT',
                'unsigned' => true,  // Asumiendo que id_cliente es una clave externa a una tabla clientes
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('id_sucursal', 'sucursal', 'id');
        $this->forge->createTable('productos');
    }

    public function down()
    {
        $this->forge->dropTable('productos');
    }
}
