<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SucursalSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'nombre' => 'Vida Informático',
            'correo' => 'correo@gmail.com',
            'telefono' => '999999999',
            'direccion' => 'Tu Dirección',
            'mensaje' => 'Gracias por la compra',
            'folio_venta' => '0000000001',
            'activo' => 1
        ];

        $this->db->table('sucursal')->insert($data);
    }
}

