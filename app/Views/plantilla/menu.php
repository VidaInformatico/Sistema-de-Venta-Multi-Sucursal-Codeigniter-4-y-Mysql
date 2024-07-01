<div class="pcoded-inner-navbar main-menu">
    <ul class="pcoded-item pcoded-left-item">
        <li class="">
            <?php if ($_SESSION['id_rol'] == null) { ?>
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="form-group" style="width: 100%;">
                        <select id="select-sucursal" onchange="cambiarSucursal(event)" class="form-control" style="width: 100%;">
                        </select>
                    </div>
                </div>
            <?php } ?>
        </li>

        <?php
        // En tu controlador o vista
        $uri = service('uri');

        // Obtén la ruta actual
        $currentPath = $uri->getPath();

        // Ajustar $currentPath eliminando el prefijo '/pos-cdp/public/'
        $currentPath = str_replace(env('NOMBREPROYECTO'), '', $currentPath);
        // Define las rutas esperadas y sus clases correspondientes
        $expectedPaths = [
            'inicio' => ['class' => 'tablero', 'icon' => 'fa-home'],
            'productos' => ['class' => 'productos', 'icon' => 'fa-list'],
            'clientes' => ['class' => 'clientes', 'icon' => 'fa-users'],
            'nuevaventa' => ['class' => 'nueva venta', 'icon' => 'fa-box'],
            'ventas' => ['class' => 'historial ventas', 'icon' => 'fa-cash-register'],
            'roles' => ['class' => 'roles', 'icon' => 'fa-key'],
            'usuarios' => ['class' => 'usuarios', 'icon' => 'fa-user'],
            'sucursal' => ['class' => 'sucursal', 'icon' => 'fa-cog'],
            // Agrega más rutas según sea necesario
        ];

        foreach ($expectedPaths as $path => $info) {
            $isActive = (str_replace('/', '', $currentPath) == $path) ? 'active' : '';

            if (verificar($info['class'], $_SESSION['permisos'])) { ?>
                <li class="<?= $isActive ?>">
                    <a href="<?= base_url("$path"); ?>">
                        <span class="pcoded-micon"><i class="fas <?= $info['icon'] ?>"></i><b>D</b></span>
                        <span class="pcoded-mtext" data-i18n="nav.dash.main"><?= ucfirst($info['class']) ?></span>
                        <span class="pcoded-mcaret"></span>
                    </a>
                </li>
        <?php  }
        } ?>

    </ul>

</div>