<?php

namespace App\Controllers;

use App\Models\PermisosModel;
use App\Models\RolesModel;
use App\Models\UsuariosModel;

class Login extends BaseController
{
    public function index()
    {
        return view('login');
    }

    public function login()
    {
        $session = session();
        helper('form');

        $reglas = [
            'usuario'  => 'required',
            'password' => ['label' => 'contraseña', 'rules' => 'required'],
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $usuarioModel = new UsuariosModel();
        $post = $this->request->getPost(['usuario', 'password']);

        $usuarioData = $usuarioModel->validaUsuario($post['usuario'], $post['password']);

        if ($usuarioData !== null) {
            //VERIFICAR SUPERADMIN
            $usuarioData['permisos'] = [];
            if ($usuarioData['id_rol'] != null) {
                $rolModel = new RolesModel();
                $roles = $rolModel->where('id', $usuarioData['id_rol'])->first();
                $usuarioData['rol'] = $roles['nombre'];
                $usuarioData['permisos'] = ($roles['permisos'] != null) ? json_decode($roles['permisos'], true) : null;
            }else{
                $usuarioData['rol'] = 'SuperAdmin';
                //RECUPERAR TODO LOS PERMISOS
                $permisosModel = new PermisosModel();
                $datosPermisos = $permisosModel->findAll();
                foreach ($datosPermisos as $permiso) {
                    $usuarioData['permisos'][] = $permiso['permiso'];                    
                }
            }
            $this->configurarSesion($usuarioData);
            return redirect()->to(base_url('inicio'));
        }

        $session->setFlashdata('errors', ['error' => 'El usuario y/o contraseña son incorrectos.']);
        return redirect()->back()->withInput();
    }

    private function configurarSesion($usuarioData)
    {
        $sesionData = [
            'usuarioLogin'  => true,
            'usuarioId'     => $usuarioData['id'],
            'usuarioNombre' => $usuarioData['nombre'],
            'usuarioRol' => $usuarioData['rol'],
            'permisos' => $usuarioData['permisos'],
            'id_sucursal' => $usuarioData['id_sucursal'],
            'id_rol' => $usuarioData['id_rol'],
        ];

        $this->session->set($sesionData);
    }

    public function logout()
    {
        if ($this->session->get('usuarioLogin')) {
            $this->session->destroy();
        }

        return redirect()->to(base_url());
    }

    public function cambiaPassword()
    {
        helper('form');
        return view('cambia_password', ['usuario' => $this->session]);
    }

    public function actualizaPassword()
    {
        $reglas = [
            'password'     => ['label' => 'contraseña', 'rules' => 'required'],
            'con_password' => ['label' => 'confirmar contraseña', 'rules' => 'required|matches[password]'],
        ];

        if (!$this->validate($reglas)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->listErrors());
        }

        $usuarioModel = new UsuariosModel();
        $post = $this->request->getPost(['id_usuario', 'password']);

        $hash = password_hash($post['password'], PASSWORD_DEFAULT);
        $usuarioModel->update($post['id_usuario'], ['password' => $hash]);

        return redirect()->back()->withInput()->with('success', 'Contraseña actualizada correctamente.');
    }
}
