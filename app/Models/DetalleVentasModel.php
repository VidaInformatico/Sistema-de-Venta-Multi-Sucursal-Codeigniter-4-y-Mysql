<?php

namespace App\Models;

use CodeIgniter\Model;

class DetalleVentasModel extends Model
{
    protected $table      = 'detalle_ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['venta_id', 'producto_id', 'nombre', 'cantidad', 'precio'];

    // Dates
    protected $useTimestamps = false;

}
