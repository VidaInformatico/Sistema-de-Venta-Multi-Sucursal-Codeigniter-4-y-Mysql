<?php

namespace App\Models;

use CodeIgniter\Model;

class SucursalModel extends Model
{
    protected $table      = 'sucursal';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['nombre', 'telefono', 'correo', 'direccion', 'mensaje', 'folio_venta', 'activo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_modifica';

    // Consulta ultimo folio
    public function ultimoFolio($id_sucursal)
    {
        $resultado = $this->select('folio_venta')
            ->where('id', $id_sucursal)
            ->get();

        if ($resultado->getNumRows() > 0) {
            return $resultado->getRowArray()['folio_venta'];
        } else {
            return 1;
        }
    }

    // Actualiza siguiente folio
    public function siguienteFolio($id_sucursal)
    {
        $this->where('id', $id_sucursal)
            ->set('folio_venta', "LPAD(folio_venta+1,10,'0')", false)
            ->update();
    }
}
