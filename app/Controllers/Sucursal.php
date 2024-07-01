<?php

namespace App\Controllers;

use App\Models\SucursalModel;
use App\Models\UsuariosModel;

class Sucursal extends BaseController
{
    protected $sucursalModel;

    public function __construct()
    {
        $this->sucursalModel = new SucursalModel();
        helper(['form']);
    }

    // Cargar catálogo de sucursales
    public function index()
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $sucursales = $this->sucursalModel->where('activo', 1)->findAll();
        return view('sucursales/index', ['sucursales' => $sucursales]);
    }

    // Mostrar formulario nuevo
    public function new()
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        return view('sucursales/new');
    }

    // Valida e inserta nuevo registro
    public function create()
    {
        $reglas = [
            'nombre' => 'required',
            'telefono' => 'required|numeric',
            'correo' => 'required|valid_email',
            'direccion' => 'required',
            'mensaje' => 'required',
            'folio_venta' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['nombre', 'correo', 'telefono', 'direccion', 'mensaje', 'folio_venta']);

        $this->sucursalModel->insert([
            'nombre'        => $post['nombre'],
            'correo'        => $post['correo'],
            'telefono' => $post['telefono'],
            'direccion'    => $post['direccion'],
            'mensaje'    => $post['mensaje'],
            'folio_venta'    => $post['folio_venta'],
            'activo'        => 1
        ]);

        return redirect()->to('sucursal');
    }

    // Cargar vista editar
    public function edit($id = null)
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        if ($id == null) {
            return redirect()->to('sucursal');
        }

        $sucursal = $this->sucursalModel->find($id);
        return view('sucursales/edit', ['sucursal' => $sucursal]);
    }

    public function show()
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $data['sucursales'] = $this->sucursalModel->where('activo', 1)->findAll();
        $data['sucursal'] = $this->session->id_sucursal;
        return $this->response->setJSON($data);
    }

    // Valida y actualiza registro
    public function update($id = null)
    {
        if ($id == null) {
            return redirect()->to('sucursal');
        }

        $reglas = [
            'nombre' => 'required',
            'telefono' => ['label' => 'telefono', 'rules' => "required|is_unique[sucursal.telefono,id,{$id}]"],
            'correo' => ['label' => 'correo', 'rules' => "required|valid_email|is_unique[sucursal.correo,id,{$id}]"],
            'direccion' => 'required',
            'mensaje' => 'required',
            'folio_venta' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['nombre', 'correo', 'telefono', 'direccion', 'mensaje', 'folio_venta']);

        $this->sucursalModel->update($id, [
            'nombre'        => $post['nombre'],
            'correo'        => $post['correo'],
            'telefono' => $post['telefono'],
            'direccion'    => $post['direccion'],
            'folio_venta'    => $post['folio_venta'],
            'mensaje'    => $post['mensaje'],
        ]);

        return redirect()->to('sucursal');
    }

    // Elimina sucursal
    public function delete($id = null)
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        if (!$id == null) {
            $this->sucursalModel->update($id, [
                'activo' => 0
            ]);
        }

        return redirect()->to('sucursal');
    }

    public function cambiarSucursal($id)
    {
        if (!verificar('sucursal', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        $usuariosModel = new UsuariosModel();
        $data = $usuariosModel->update(
            $this->session->usuarioId,
            ['id_sucursal' => $id]
        );

        if ($data) {
            $session = session();
            $session->set([
                'id_sucursal' => $id,
            ]);
            return $this->response->setJSON([
                'msg' => 'SUCURSAL CAMBIADO',
                'icono' => 'success'
            ]);
        } else {
            return $this->response->setJSON([
                'msg' => 'ERROR AL CAMBIAR',
                'icono' => 'error'
            ]);
        }
    }
}
