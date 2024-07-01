<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PermisosSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'permiso' => 'tablero'
            ],
            [
                'permiso' => 'productos'
            ],
            [
                'permiso' => 'clientes'
            ],
            [
                'permiso' => 'nueva venta'
            ],
            [
                'permiso' => 'historial ventas'
            ],
            [
                'permiso' => 'usuarios'
            ],
            [
                'permiso' => 'sucursal'
            ],
            [
                'permiso' => 'roles'
            ],
            [
                'permiso' => 'reportes'
            ],
            // Agrega más conjuntos de datos según sea necesario
        ];

        $this->db->table('permisos')->insertBatch($data);
    }
}
