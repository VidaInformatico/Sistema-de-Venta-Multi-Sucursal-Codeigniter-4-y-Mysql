<?php

namespace App\Controllers;

use App\Models\ClientesModel;
use App\Models\ProductosModel;
use App\Models\VentasModel;

class Inicio extends BaseController
{
    public function index()
    {
        $productosModel = new ProductosModel();
        $clientesModel = new ClientesModel();
        $ventasModel    = new VentasModel();

        $data['totalProductos'] = $productosModel->where(['activo' => 1, 'id_sucursal' => $this->session->get('id_sucursal')])->countAllResults();
        $data['totalClientes'] = $clientesModel->where(['activo' => 1, 'id_sucursal' => $this->session->get('id_sucursal')])->countAllResults();
        $hoy = date('Y-m-d');
        $mes = date('m');
        $anio = date('Y');
        $desde = $anio . '-01-01 00:00:00';
        $hasta = $anio . '-12-31 23:59:59';
        $data['totalVentasDia'] = $ventasModel->totalVentasDia($hoy, $this->session->get('id_sucursal'));
        $data['totalVentasMes'] = $ventasModel->totalVentasMes($mes, $anio, $this->session->get('id_sucursal'));
        $data['topClientes'] = $ventasModel->obtenerTopClientes($this->session->get('id_sucursal'));
        $data['ventasPorMes'] = $ventasModel->calcularVentas($desde, $hasta, $this->session->get('id_sucursal'));
        return view('inicio', $data);
    }

    
}
