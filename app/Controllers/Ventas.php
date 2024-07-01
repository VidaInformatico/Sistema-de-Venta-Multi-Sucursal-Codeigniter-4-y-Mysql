<?php

namespace App\Controllers;

use App\Models\ClientesModel;
use App\Models\DetalleVentasModel;
use App\Models\ProductosModel;
use App\Models\SucursalModel;
use App\Models\TemporalCajaModel;
use App\ThirdParty\Fpdf\Fpdf;
use App\ThirdParty\NumerosALetras;
use Exception;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class Ventas extends BaseController
{
    protected $ventasModel;

    public function __construct()
    {
        $this->ventasModel = model('VentasModel');
    }

    // Cargar catálogo de ventas activas
    public function index()
    {
        if (!verificar('historial ventas', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $ventas = $this->ventasModel->mostrarVentas($this->session->get('id_sucursal'), 1);
        return view('ventas/index', ['ventas' => $ventas]);
    }

    // Cargar catálogo de ventas canceladas
    public function bajas()
    {
        if (!verificar('historial ventas', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $ventas = $this->ventasModel->mostrarVentas($this->session->get('id_sucursal'), 0);
        return view('ventas/eliminados', ['ventas' => $ventas]);
    }

    // Guarda venta
    public function guarda()
    {
        $temporalModel = new TemporalCajaModel();
        $sucursalModel   = new SucursalModel();

        $idVentaTmp = $this->request->getPost('id_venta');

        //COMPROBAR ID CLIENTE
        $cliente = $this->request->getPost('id_cliente');
        if (empty($cliente)) {
            //COMPROBAR SI YA EXISTE
            $clientesModel = new ClientesModel();
            $consulta = $clientesModel->where(['identidad' => '00000000', 'activo' => 1, 'id_sucursal' => $this->session->get('id_sucursal')])->first();
            if (empty($consulta)) {
                $cliente = $clientesModel->insert([
                    'identidad'        => '00000000',
                    'nombre'        => 'PUBLICO',
                    'apellido'        => 'EN GENERAL',
                    'telefono' => '00000000',
                    'direccion'    => 'S/N',
                    'activo'        => 1,
                    'id_sucursal' => $this->session->get('id_sucursal')
                ]);
            } else {
                $cliente = $consulta['id'];
            }
        }

        $datos = [
            'folio' => str_pad($sucursalModel->ultimoFolio($this->session->get('id_sucursal')), 10, 0, STR_PAD_LEFT),
            'total' => preg_replace('/[\$,]/', '', $this->request->getPost('total')),
            'fecha' => date('Y-m-d H:i:s'),
            'usuario_id' => $this->session->get('usuarioId'),
            'id_cliente' => $cliente,
            'activo'        => 1,
            'id_sucursal' => $this->session->get('id_sucursal')
        ];

        $idVenta = $this->ventasModel->insert($datos);

        if ($idVenta) {
            $sucursalModel->siguienteFolio($this->session->get('id_sucursal'));

            $ventaTmp = $temporalModel->where('id_venta', $idVentaTmp)->findAll();

            $detalleVentasModel = new DetalleVentasModel();
            $productosModel     = new ProductosModel();

            foreach ($ventaTmp as $productoTmp) {
                $producto = [
                    'venta_id'    => $idVenta,
                    'producto_id' => $productoTmp['id_producto'],
                    'nombre'      => $productoTmp['nombre'],
                    'cantidad'    => $productoTmp['cantidad'],
                    'precio'      => $productoTmp['precio'],
                ];

                $detalleVentasModel->insert($producto);

                $datosProducto = $productosModel->find($productoTmp['id_producto']);

                if ($datosProducto['inventariable'] == 1) {
                    $productosModel->actualizaStock($productoTmp['id_producto'], $productoTmp['cantidad'], '-');
                }
            }
        }

        $temporalModel->eliminaVenta($idVentaTmp);

        if(env('IMPRESIONDIRECTA')){
            $this->impresionDirecta($idVenta);
        }

        return redirect()->to(base_url('ventas/imprimirTicket/' . $idVenta));
    }

    // Muesta ticket de venta
    public function verTicket($idVenta)
    {
        $datosVenta   = $this->ventasModel->find($idVenta);

        if ($datosVenta) {
            return view('ventas/ticket', ['idVenta' => $idVenta]);
        } else {
            return view('ventas/mensaje', ['mensaje' => 'No se encontró información.']);
        }
    }

    // Genera ticket de venta
    public function generaTicket($idVenta)
    {
        $detalleVentasModel = new DetalleVentasModel();
        $sucursalModel = new SucursalModel();

        $empresa = $sucursalModel->find($this->session->get('id_sucursal'));

        $datosVenta   = $this->ventasModel->select('ventas.*, c.identidad, c.nombre, c.apellido, c.telefono, c.direccion')
            ->join('clientes AS c', 'ventas.id_cliente = c.id')
            ->where('ventas.id', $idVenta)->first();
        $detalleVenta = $detalleVentasModel->where('venta_id', $idVenta)->findAll();

        $pdf = new Fpdf('P', 'mm', array(80, 250));
        $pdf->AddPage();
        $pdf->SetMargins(5, 5, 5);
        $pdf->SetTitle("Ticket");
        $pdf->SetFont('Arial', 'B', 9);

        $fecha = substr($datosVenta['fecha'], 0, 10);
        $hora = substr($datosVenta['fecha'], 11, 8);

        $total = $datosVenta['total'];

        $pdf->Multicell(60, 4, mb_convert_encoding($empresa['nombre'], 'ISO-8859-1', 'UTF-8'), 0, 'C', 0);

        $pdf->SetFont('Arial', '', 7);
        $pdf->Multicell(70, 4, mb_convert_encoding($empresa['direccion'], 'ISO-8859-1', 'UTF-8'), 0, 'C', 0);
        $pdf->Multicell(70, 4, mb_convert_encoding($empresa['telefono'], 'ISO-8859-1', 'UTF-8'), 0, 'C', 0);

        $pdf->SetFont('Arial', '', 8);
        $pdf->Ln();
        $pdf->Cell(60, 4, mb_convert_encoding('Nº ticket:  ', 'ISO-8859-1', 'UTF-8') . $datosVenta['folio'], 0, 1, 'L');
        $pdf->Cell(60, 4, mb_convert_encoding('Cliente:  ', 'ISO-8859-1', 'UTF-8') . $datosVenta['nombre'] . ' ' . $datosVenta['apellido'], 0, 1, 'L');
        $pdf->Cell(60, 4, mb_convert_encoding('Teléfono:  ', 'ISO-8859-1', 'UTF-8') . $datosVenta['telefono'], 0, 1, 'L');
        $pdf->Cell(60, 4, mb_convert_encoding('Dirección:  ', 'ISO-8859-1', 'UTF-8') . $datosVenta['direccion'], 0, 1, 'L');

        $pdf->Cell(60, 4, '=========================================', 0, 1, 'L');

        $pdf->Cell(7, 3, 'Cant.', 0, 0, 'L');
        $pdf->Cell(36, 3, mb_convert_encoding('Descripción', 'ISO-8859-1', 'UTF-8'), 0, 0, 'L');
        $pdf->Cell(14, 3, 'Precio', 0, 0, 'L');
        $pdf->Cell(14, 3, 'Importe', 0, 1, 'L');
        $pdf->Cell(70, 3, '------------------------------------------------------------------------', 0, 1, 'L');

        $pdf->SetFont('Arial', '', 6.5);

        foreach ($detalleVenta as $producto) {
            $importe  = number_format(($producto['cantidad'] * $producto['precio']), 2, '.', ',');
            $pdf->Cell(7, 3, $producto['cantidad'], 0, 0, 'C');
            $y = $pdf->GetY();
            $pdf->MultiCell(36, 3, mb_convert_encoding($producto['nombre'], 'ISO-8859-1', 'UTF-8'), 0, 'L');
            $y2 = $pdf->GetY();
            $pdf->SetXY(48, $y);
            $pdf->Cell(14, 3, env('SIMMONEY') . ' ' . number_format($producto['precio'], 2, '.', ','), 0, 1, 'C');
            $pdf->SetXY(62, $y);
            $pdf->Cell(14, 3, env('SIMMONEY') . $importe, 0, 1, 'C');
            $pdf->SetY($y2);
        }

        $pdf->Ln();
        $pdf->SetFont('Arial', 'B', 9);
        $pdf->Cell(50, 4, 'Total', 0, 0, 'R');
        $pdf->Cell(20, 4, env('SIMMONEY') . ' ' . number_format($total, 2, '.', ','), 0, 1, 'R');

        $pdf->Ln();
        $pdf->SetFont('Arial', '', 8);
        $pdf->MultiCell(70, 4, env('SIMMONEY') . ' ' . ucfirst(strtolower(NumerosALetras::convertir($total, env('MONEDA'), 'centavos'))), 0, 'L', 0);

        $pdf->Ln();
        $pdf->Cell(10);
        $pdf->Cell(30, 4, 'Fecha: ' . date("d/m/Y", strtotime($fecha)), 0, 0, 'L');
        $pdf->Cell(30, 4, 'Hora: ' . $hora, 0, 1, 'L');

        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 8);
        $pdf->Multicell(70, 4, mb_convert_encoding($empresa['mensaje'], 'ISO-8859-1', 'UTF-8'), 0, 'C', 0);
        $pdf->Ln(5);
        if ($datosVenta['activo'] == 0) {
            $pdf->SetTextColor(255, 0, 0);
            $pdf->SetFontSize(24);
            // $pdf->SetY(30);
            $pdf->Cell(0, 5, 'Venta cancelada', 0, 0, 'C');
        }

        $this->response->setHeader('Content-Type', 'application/pdf');
        $pdf->Output("Ticket.pdf", 'I');
    }

    // Cancela venta
    public function cancelar($id)
    {
        if (!verificar('historial ventas', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        $this->ventasModel->update($id, ['activo' => 0]);
        return redirect()->to(base_url('ventas'));
    }

    //IMPRESIÓN DIRECTA
    public function impresionDirecta($idVenta)
    {
        $detalleVentasModel = new DetalleVentasModel();
        $sucursalModel = new SucursalModel();

        $empresa = $sucursalModel->find($this->session->get('id_sucursal'));

        $datosVenta   = $this->ventasModel->select('ventas.*, c.identidad, c.nombre, c.apellido, c.telefono, c.direccion')
            ->join('clientes AS c', 'ventas.id_cliente = c.id')
            ->where('ventas.id', $idVenta)->first();

        $productos = $detalleVentasModel->where('venta_id', $idVenta)->findAll();

        $connector = new WindowsPrintConnector(env('IMPRESORA'));
        $printer = new Printer($connector);

        // Configurar alineación central para los datos de la empresa
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        
        $printer->text($empresa['nombre'] . "\n");
        $printer->text($empresa['direccion'] . "\n");
        $printer->text(date("Y-m-d H:i:s") . "\n\n");

        // Configurar alineación izquierda para los datos del cliente
        $printer->setJustification(Printer::JUSTIFY_LEFT);
        $printer->text("Cliente: " . $datosVenta['nombre'] . " " . $datosVenta['apellido'] . "\n");
        $printer->text("Identidad: " . $datosVenta['identidad'] . "\n");
        $printer->text("Teléfono: " . $datosVenta['telefono'] . "\n");
        $printer->text("Dirección: " . $datosVenta['direccion'] . "\n");

        $printer->text("\nProductos:\n");
        $total = 0;
        foreach ($productos as $producto) {
            $total += $producto['cantidad'] * $producto['precio'];

            /*Alinear a la izquierda para la cantidad y el nombre*/
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text($producto['cantidad'] . " x " . $producto['nombre'] . "\n");

            /*Y a la derecha para el importe*/
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text(' ' . env('SIMMONEY') . $producto['precio'] . "\n");
        }

        $printer->text("--------\n");
        $printer->text("TOTAL: " . env('SIMMONEY') . $total . "\n");
        $printer->text($empresa['mensaje']);
        $printer->feed(3);
        $printer->cut();
        $printer->pulse();
        $printer->close();
    }
}
