<?php

namespace App\Models;

use CodeIgniter\Model;

class TemporalCajaModel extends Model
{
    protected $table      = 'temproductos';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['id_venta', 'id_producto', 'codigo', 'nombre', 'cantidad', 'precio', 'importe'];

    // Dates
    protected $useTimestamps = false;

    public function actualizaProductoVenta($idProducto, $idVenta, $cantidad, $importe)
    {
        return $this->set('cantidad', $cantidad)
            ->set('importe', $importe)
            ->where('id_producto', $idProducto)
            ->where('id_venta', $idVenta)
            ->update();
    }

    public function totalPorVenta($idVenta)
    {
        $resultado = $this->db->table('temproductos')
            ->selectSum('importe')
            ->where('id_venta', $idVenta)
            ->get();

        if ($resultado->getNumRows() > 0) {
            $importe = $resultado->getRowArray()['importe'];
            return ($importe !== null) ? $importe : 0;
        } else {
            return 0;
        }
    }

    public function eliminar($idProducto, $idVenta)
    {
        return $this->where('id_producto', $idProducto)
            ->where('id_venta', $idVenta)
            ->delete();
    }

    public function eliminaVenta($idVenta)
    {
        return $this->where('id_venta', $idVenta)
            ->delete();
    }
}
