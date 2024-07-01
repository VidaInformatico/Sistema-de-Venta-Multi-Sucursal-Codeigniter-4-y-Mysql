<!DOCTYPE html>
<html lang="en">

<head>
    <title>Inventario </title>
    <!-- HTML5 Shim and Respond.js IE9 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
      <![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="Gradient Able Bootstrap admin template made using Bootstrap 4. The starter version of Gradient Able is completely free for personal project." />
    <meta name="keywords" content="free dashboard template, free admin, free bootstrap template, bootstrap admin template, admin theme, admin dashboard, dashboard template, admin template, responsive" />
    <meta name="author" content="codedthemes">
    <!-- Favicon icon -->
    <link rel="icon" href="<?= base_url('images/logo.png'); ?>" type="image/x-icon">
    <!-- Google font-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">
    <!-- Required Fremwork -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/bootstrap/css/bootstrap.min.css'); ?>">
    <!-- themify-icons line icon -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/icon/themify-icons/themify-icons.css'); ?>">
    <!-- ico font -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/icon/icofont/css/icofont.css'); ?>">
    <!-- Style.css -->
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/css/style.css'); ?>">

    <link href="<?= base_url('assets/datatables/datatables.min.css'); ?>" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/jquery-ui/jquery-ui.min.css'); ?>" rel="stylesheet">

    <?php $this->renderSection('style'); ?>

</head>

<body>

    <body>
        <!-- Pre-loader start -->
        <div class="theme-loader">
            <div class="loader-track">
                <div class="loader-bar"></div>
            </div>
        </div>
        <!-- Pre-loader end -->
        <div id="pcoded" class="pcoded">
            <div class="pcoded-overlay-box"></div>
            <div class="pcoded-container navbar-wrapper">

                <nav class="navbar header-navbar pcoded-header">
                    <div class="navbar-wrapper">
                        <div class="navbar-logo">
                            <a class="mobile-menu" id="mobile-collapse" href="#!">
                                <i class="ti-menu"></i>
                            </a>
                            <a href="<?= base_url('inicio'); ?>">
                                Tu Logo Aquí
                            </a>
                            <a class="mobile-options">
                                <i class="ti-more"></i>
                            </a>
                        </div>

                        <div class="navbar-container container-fluid">
                            <ul class="nav-left">
                                <li>
                                    <div class="sidebar_toggle"><a href="javascript:void(0)"><i class="ti-menu"></i></a>
                                    </div>
                                </li>
                                <li>
                                    <a href="#!" onclick="javascript:toggleFullScreen()">
                                        <i class="ti-fullscreen"></i>
                                    </a>
                                </li>
                            </ul>
                            <ul class="nav-right">

                                <li class="user-profile header-notification">
                                    <a href="#!">
                                        <img src="<?= base_url('assets/images/avatar-4.jpg'); ?>" class="img-radius" alt="User-Profile-Image">
                                        <span><?= $_SESSION['usuarioNombre']; ?></span>
                                        <i class="ti-angle-down"></i>
                                    </a>
                                    <ul class="show-notification profile-notification">
                                        <li>
                                            <a href="<?= base_url('perfil/cambiarClave'); ?>">
                                                <i class="ti-user"></i> Perfil
                                            </a>
                                        </li>

                                        <li>
                                            <a href="<?= base_url('logout'); ?>">
                                                <i class="ti-layout-sidebar-left"></i> Salir
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
                <div class="pcoded-main-container">
                    <div class="pcoded-wrapper">
                        <nav class="pcoded-navbar">
                            <div class="sidebar_toggle"><a href="#"><i class="icon-close icons"></i></a></div>
                            <div class="pcoded-inner-navbar main-menu">

                                <?= $this->include('plantilla/menu'); ?>

                            </div>
                        </nav>
                        <div class="pcoded-content">
                            <div class="pcoded-inner-content">
                                <div class="main-body">
                                    <div class="page-wrapper">

                                        <div class="page-body">
                                            <div class="card">
                                                <div class="card-body">
                                                    <?php $this->renderSection('contentido'); ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div id="styleSelector">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript" src="<?= base_url('assets/js/jquery/jquery.min.js'); ?>"></script>
            <script type="text/javascript" src="<?= base_url('assets/js/popper.js/popper.min.js'); ?>"></script>
            <script type="text/javascript" src="<?= base_url('assets/js/bootstrap/js/bootstrap.min.js'); ?>"></script>
            <!-- jquery slimscroll js -->
            <script type="text/javascript" src="<?= base_url('assets/js/jquery-slimscroll/jquery.slimscroll.js'); ?>"></script>
            <!-- modernizr js -->
            <script type="text/javascript" src="<?= base_url('assets/js/modernizr/modernizr.js'); ?>"></script>
            <!-- Custom js -->
            <script type="text/javascript" src="<?= base_url('assets/js/script.js'); ?>"></script>
            <script src="<?= base_url('assets/js/pcoded.min.js'); ?>"></script>
            <script src="<?= base_url('assets/js/vartical-demo.js'); ?>"></script>
            <script src="<?= base_url('assets/js/all.min.js'); ?>"></script>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script src="<?= base_url('assets/jquery-ui/jquery-ui.min.js'); ?>"></script>
            <script src="<?= base_url('assets/datatables/datatables.min.js'); ?>"></script>
            <script>
                const base_url = '<?= base_url(); ?>';
            </script>
            <script src="<?= base_url('js/custom.js'); ?>"></script>

            <?php $this->renderSection('script'); ?>
    </body>

</html>