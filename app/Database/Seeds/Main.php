<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Main extends Seeder
{
    public function run()
    {
        $this->call('PermisosSeeder');
        $this->call('RolesSeeder');
        $this->call('SucursalSeeder');
        $this->call('UsuariosSeeder');
    }
}
