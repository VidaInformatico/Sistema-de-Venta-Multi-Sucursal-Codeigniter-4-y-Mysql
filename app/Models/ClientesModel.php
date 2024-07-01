<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientesModel extends Model
{
    protected $table      = 'clientes';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['identidad', 'nombre', 'apellido', 'telefono', 'direccion', 'activo', 'id_sucursal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_modifica';

    public function buscarCliente($cliente = '', $id_sucursal)
    {
        $cliente = $this->escapeLikeString($cliente);

        $query = $this->select('id, identidad, nombre, apellido, telefono')
            ->where(['activo' => 1, 'id_sucursal' => $id_sucursal])
            ->groupStart()
            ->like('identidad', $cliente)
            ->orLike('nombre', $cliente)
            ->groupEnd()
            ->limit(10)
            ->get();

        return $query->getResultArray();
    }
}
