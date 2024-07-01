<?php

namespace App\Controllers;

use App\Models\PermisosModel;
use App\Models\SucursalModel;
use App\Models\RolesModel;

class Roles extends BaseController
{
    protected $permisosModel, $rolesModel;

    public function __construct()
    {
        $this->rolesModel = new RolesModel();
        $this->permisosModel = new PermisosModel();
        helper(['form']);
    }

    // Cargar catálogo de roles
    public function index()
    {
        if (!verificar('roles', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        $roles = $this->rolesModel->where('activo', 1)->findAll();
        return view('roles/index', ['roles' => $roles]);
    }

    // Mostrar formulario nuevo
    public function new()
    {
        if (!verificar('roles', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        $data['permisos'] = $this->permisosModel->where('activo', 1)->findAll();
        return view('roles/new', $data);
    }

    // Valida e inserta nuevo registro
    public function create()
    {
        $reglas = [
            'nombre' => ['label' => 'rol', 'rules' => 'required|is_unique[roles.nombre]'],
            'permisos' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['nombre', 'permisos']);

        $this->rolesModel->insert([
            'nombre'        => $post['nombre'],
            'permisos'        => json_encode($post['permisos']),
            'activo'        => 1
        ]);

        return redirect()->to('roles');
    }

    // Cargar vista editar
    public function edit($id = null)
    {
        if (!verificar('roles', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }

        if ($id == null) {
            return redirect()->to('roles');
        }

        $data['rol'] = $this->rolesModel->find($id);
        $data['permisos'] = $this->getPermisosFromDatabase(); // Reemplaza esto con la forma en que obtienes tus permisos
        $permisosAsignados = json_decode($data['rol']['permisos'], true);
        $data['activos'] = $permisosAsignados;

        return view('roles/edit', $data);
    }

    // Ejemplo de cómo podrías obtener los permisos desde la base de datos, ajusta según tu lógica real
    private function getPermisosFromDatabase()
    {
        $permisos = $this->permisosModel->where('activo', 1)->findAll();
        $permisosArray = array_column($permisos, 'permiso');
        return $permisosArray;
    }


    // Valida y actualiza registro
    public function update($id = null)
    {
        if ($id == null) {
            return redirect()->to('roles');
        }

        $reglas = [
            'nombre' => ['label' => 'rol', 'rules' => "required|is_unique[roles.nombre,id,{$id}]"],
            'permisos' => 'required'
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $post = $this->request->getPost(['nombre', 'permisos']);

        $this->rolesModel->update($id, [
            'nombre'        => $post['nombre'],
            'permisos'        => json_encode($post['permisos'])
        ]);

        return redirect()->to('roles');
    }

    // Elimina usuario
    public function delete($id = null)
    {
        if (!verificar('roles', $_SESSION['permisos'])) {
            return redirect()->to(base_url('inicio'));
        }
        
        if (!$id == null) {
            $this->rolesModel->update($id, [
                'activo' => 0
            ]);
        }

        return redirect()->to('roles');
    }
}
