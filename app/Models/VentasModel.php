<?php

namespace App\Models;

use CodeIgniter\Model;

class VentasModel extends Model
{
    protected $table      = 'ventas';
    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['folio', 'total', 'fecha', 'usuario_id', 'id_cliente', 'id_sucursal', 'activo'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'fecha_alta';
    protected $updatedField  = 'fecha_modifica';

    // Consulta vista "ventas"
    public function mostrarVentas($id_sucursal, $activo = 1)
    {
        $query = $this->select('ventas.*, u.nombre as usuario') // Selecciona los campos necesarios
            ->join('usuarios AS u', 'u.id = ventas.usuario_id') // Realiza el INNER JOIN
            ->where(['ventas.activo' => $activo, 'ventas.id_sucursal' => $id_sucursal]); // Agrega condiciones

        return $query->get()->getResultArray();
    }


    // Consulta ventas por fechas
    public function ventasRango($fechaInicio, $fechaFin, $activo = 1, $id_sucursal)
    {
        $query = $this->select('ventas.*, c.nombre')
            ->join('clientes AS c', 'ventas.id_cliente = c.id')
            ->where(['ventas.activo' => $activo, 'ventas.id_sucursal' => $id_sucursal])
            ->where("DATE(ventas.fecha_alta) BETWEEN '$fechaInicio' AND '$fechaFin'")
            ->orderBy('ventas.fecha_alta DESC')
            ->get();
        return $query->getResultArray();
    }

    public function totalVentasDia($fecha, $id_sucursal)
    {
        $where = "activo = 1 AND id_sucursal = $id_sucursal AND DATE(fecha) = '$fecha'";
        $this->select("IFNULL(sum(total), 0) AS total");
        return $this->where($where)->first();
    }

    public function totalVentasMes($mes, $ano, $id_sucursal)
    {
        // Asegúrate de validar y sanitizar los valores de $mes, $ano y $id_sucursal para evitar posibles problemas de seguridad

        // Formatea el mes y el año en el formato de fecha deseado
        $fechaInicio = "$ano-$mes-01";
        $fechaFin = date('Y-m-t', strtotime($fechaInicio)); // 't' obtiene el último día del mes

        $where = "activo = 1 AND id_sucursal = $id_sucursal AND fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
        $this->select("IFNULL(sum(total), 0) AS total");
        return $this->where($where)->first();
    }

    public function obtenerTopClientes($id_sucursal, $limit = 12)
    {
        return $this->select('clientes.nombre as cliente, SUM(ventas.total) as total_ventas')
            ->join('clientes', 'clientes.id = ventas.id_cliente')
            ->where(['ventas.activo' => 1, 'ventas.id_sucursal' => $id_sucursal])
            ->groupBy('clientes.id')
            ->orderBy('total_ventas', 'DESC')
            ->limit($limit)
            ->findAll();
    }

    public function calcularVentas($desde, $hasta, $id_sucursal)
    {
        $result = $this->select('SUM(IF(MONTH(fecha) = 1, total, 0)) AS ene,
        SUM(IF(MONTH(fecha) = 2, total, 0)) AS feb,
        SUM(IF(MONTH(fecha) = 3, total, 0)) AS mar,
        SUM(IF(MONTH(fecha) = 4, total, 0)) AS abr,
        SUM(IF(MONTH(fecha) = 5, total, 0)) AS may,
        SUM(IF(MONTH(fecha) = 6, total, 0)) AS jun,
        SUM(IF(MONTH(fecha) = 7, total, 0)) AS jul,
        SUM(IF(MONTH(fecha) = 8, total, 0)) AS ago,
        SUM(IF(MONTH(fecha) = 9, total, 0)) AS sep,
        SUM(IF(MONTH(fecha) = 10, total, 0)) AS oct,
        SUM(IF(MONTH(fecha) = 11, total, 0)) AS nov,
        SUM(IF(MONTH(fecha) = 12, total, 0)) AS dic')
            ->where('fecha BETWEEN ' . $this->escape($desde) . ' AND ' . $this->escape($hasta))
            ->where('id_sucursal', $id_sucursal)
            ->get();

        return $result->getRow();
    }
}
