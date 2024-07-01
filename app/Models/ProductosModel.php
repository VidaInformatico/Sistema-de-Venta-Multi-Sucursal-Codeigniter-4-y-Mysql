<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductosModel extends Model
{
    protected $table      = 'productos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['codigo', 'nombre', 'precio', 'inventariable', 'existencia', 'activo', 'id_sucursal'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_modifica';

    public function buscarPorCodigoNombre($codigoNombre = '', $id_sucursal)
    {
        $codigoNombre = $this->escapeLikeString($codigoNombre);

        $query = $this->select('id, codigo, nombre')
            ->where(['activo' => 1, 'id_sucursal' => $id_sucursal])
            ->groupStart()
            ->like('codigo', $codigoNombre)
            ->orLike('nombre', $codigoNombre)
            ->groupEnd()
            ->orderBy('codigo', 'ASC')
            ->limit(10)
            ->get();

        return $query->getResultArray();
    }


    // Actualiza existencia del producto
    public function actualizaStock($idProducto, $cantidad, $operador = '+')
    {
        $this->where('id', $idProducto)
            ->set('existencia', "existencia $operador $cantidad", false)
            ->update();
    }

    public function productosInventario($id_sucursal, $activo = 1)
    {
        $query = $this->select(
            'id, codigo, nombre, precio, inventariable,
            (CASE 
                WHEN inventariable = 1 THEN "SI"
                ELSE "NO" 
            END) AS inventariable,
            (CASE
                WHEN inventariable = 1 THEN existencia
                    ELSE "N/A" 
                END) AS existencia'
        )->where(['activo' => $activo, 'id_sucursal' => $id_sucursal])->get();

        return $query->getResultArray();
    }
}
