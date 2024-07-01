<?php

namespace App\Controllers;

use App\Models\RolesModel;
use App\Models\SucursalModel;
use App\Models\UsuariosModel;

class Usuarios extends BaseController
{
    protected $usuariosModel, $sucursalModel, $rolesModel;

    public function __construct()
    {
        $this->usuariosModel = new UsuariosModel();
        $this->sucursalModel = new SucursalModel();
        $this->rolesModel = new RolesModel();
        helper(['form']);
    }

    // Cargar catálogo de usuarios
    public function index()
    {
        if (!verificar('usuarios', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $usuarios = $this->usuariosModel->select('usuarios.*, s.nombre AS sucursal')
        ->join('sucursal AS s', 'usuarios.id_sucursal = s.id')
        ->where('usuarios.activo', 1)->findAll();
        return view('usuarios/index', ['usuarios' => $usuarios]);
    }

    // Mostrar formulario nuevo
    public function new()
    {
        if (!verificar('usuarios', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        $data['sucursales'] = $this->sucursalModel->where('activo', 1)->findAll();
        $data['roles'] = $this->rolesModel->where('activo', 1)->findAll();
        return view('usuarios/new', $data);
    }

    // Valida e inserta nuevo registro
    public function create()
    {
        $reglas = [
            'usuario' => ['label' => 'usuario', 'rules' => 'required|is_unique[usuarios.usuario]'],
            'nombre' => 'required',
            'password' => 'required',
            'id_sucursal' => 'required|numeric',
            'id_rol' => 'required|numeric'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['superadmin', 'usuario', 'nombre', 'password', 'id_sucursal', 'id_rol']);

        $superadmin = (!empty($post['superadmin'])) ? null : $post['id_rol'];

        $this->usuariosModel->insert([
            'usuario'        => $post['usuario'],
            'nombre'        => $post['nombre'],
            'password'        => password_hash($post['password'], PASSWORD_DEFAULT),
            'id_sucursal' => $post['id_sucursal'],
            'id_rol' => $superadmin,
            'activo'        => 1
        ]);

        return redirect()->to('usuarios');
    }

    // Cargar vista editar
    public function edit($id = null)
    {
        if (!verificar('usuarios', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        if ($id == null) {
            return redirect()->to('usuarios');
        }

        $data['usuario'] = $this->usuariosModel->find($id);
        $data['sucursales'] = $this->sucursalModel->where('activo', 1)->findAll();
        $data['roles'] = $this->rolesModel->where('activo', 1)->findAll();
        return view('usuarios/edit', $data);
    }

    // Valida y actualiza registro
    public function update($id = null)
    {
        if ($id == null) {
            return redirect()->to('usuarios');
        }

        $reglas = [
            'usuario' => ['label' => 'usuario', 'rules' => "required|is_unique[usuarios.usuario,id,{$id}]"],
            'nombre' => 'required',
            'id_sucursal' => 'required|numeric',
            'id_rol' => 'required|numeric'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['superadmin', 'usuario', 'nombre', 'id_sucursal', 'id_rol']);

        $superadmin = (!empty($post['superadmin'])) ? null : $post['id_rol'];

        $this->usuariosModel->update($id, [
            'usuario'        => $post['usuario'],
            'nombre'        => $post['nombre'],
            'id_sucursal' => $post['id_sucursal'],
            'id_rol' => $superadmin
        ]);

        return redirect()->to('usuarios');
    }

    // Elimina usuario
    public function delete($id = null)
    {
        if (!verificar('usuarios', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        if (!$id == null) {
            $this->usuariosModel->update($id, [
                'activo' => 0
            ]);
        }

        return redirect()->to('usuarios');
    }
}
