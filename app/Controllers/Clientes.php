<?php

namespace App\Controllers;

use App\Models\ClientesModel;

class Clientes extends BaseController
{
    protected $clientesModel;

    public function __construct()
    {
        $this->clientesModel = new ClientesModel();
        helper(['form']);
        
    }

    // Cargar catálogo de clientes
    public function index()
    {
        if (!verificar('clientes', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        $clientes = $this->clientesModel->where(['activo' => 1, 'id_sucursal' => $this->session->get('id_sucursal')])->findAll();
        return view('clientes/index', ['clientes' => $clientes]);
    }

    // Mostrar formulario nuevo
    public function new()
    {
        if (!verificar('clientes', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        return view('clientes/new');
    }

    // Valida e inserta nuevo registro
    public function create()
    {
        $reglas = [
            'identidad' => 'required|unique_sucursal[clientes.identidad]',
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => 'required|numeric|unique_sucursal[clientes.telefono]',
            'direccion' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['identidad', 'nombre', 'apellido', 'telefono', 'direccion']);
        $this->clientesModel->insert([
            'identidad'        => $post['identidad'],
            'nombre'        => $post['nombre'],
            'apellido'        => $post['apellido'],
            'telefono' => $post['telefono'],
            'direccion'    => $post['direccion'],
            'activo'        => 1,
            'id_sucursal'        => $this->session->get('id_sucursal')
        ]);

        return redirect()->to('clientes');
    }

    // Cargar vista editar
    public function edit($id = null)
    {
        if (!verificar('clientes', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        if ($id == null) {
            return redirect()->to('clientes');
        }

        $cliente = $this->clientesModel->find($id);
        return view('clientes/edit', ['cliente' => $cliente]);
    }

    // Valida y actualiza registro
    public function update($id = null)
    {
        if ($id == null) {
            return redirect()->to('clientes');
        }

        $reglas = [
            'identidad' => "required|unique_sucursal[clientes.identidad.{$id}]",
            'nombre' => 'required',
            'apellido' => 'required',
            'telefono' => "required|numeric|unique_sucursal[clientes.telefono.{$id}]",
            'direccion' => 'required'
        ];
                      

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['identidad', 'nombre', 'apellido', 'telefono', 'direccion']);

        $this->clientesModel->update($id, [
            'identidad'        => $post['identidad'],
            'nombre'        => $post['nombre'],
            'apellido'        => $post['apellido'],
            'telefono' => $post['telefono'],
            'direccion'    => $post['direccion'],
        ]);

        return redirect()->to('clientes');
    }

    // Elimina cliente
    public function delete($id = null)
    {
        if (!verificar('clientes', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        if (!$id == null) {
            $this->clientesModel->update($id, [
                'activo' => 0
            ]);
        }

        return redirect()->to('clientes');
    }

    // Función para autocompletado de clientes
    public function autocompleteData()
    {
        $resultado = array();

        $valor = $this->request->getGet('term');

        $clientes = $this->clientesModel->buscarCliente($valor, $this->session->get('id_sucursal'));

        if (!empty($clientes)) {
            foreach ($clientes as $cliente) {
                $data['id']    = $cliente['id'];
                $data['value'] = $cliente['identidad'];
                $data['label'] = $cliente['nombre'] . ' ' . $cliente['apellido'];
                $data['telefono'] = $cliente['telefono'];
                array_push($resultado, $data);
            }
        }

        echo json_encode($resultado);
    }
}
