<?php

namespace App\Database\Seeds;

use App\Models\PermisosModel;
use CodeIgniter\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $ModelPermiso = new PermisosModel();
        $permisos = $ModelPermiso->findAll();
        $array = [];
        foreach ($permisos as $permiso) {
            $array[] = $permiso['permiso'];
        }
        $data = [
            'nombre' => 'Administrador',
            'permisos' => json_encode($array)
        ];

        $this->db->table('roles')->insert($data);
    }
}
