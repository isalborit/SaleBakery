<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<title>SmartSoft v1.0</title>
	<!--<link rel="icon" type="image/ico" href="<?=base_url('public')?>/resources/logos.ico" />-->
    <link rel="stylesheet" href="<?=base_url('public')?>/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/bootstrap/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/css/dom.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/css/fonts.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/icons/awesome/awesome.css">
    <link rel="stylesheet" href="<?=base_url('public')?>/css/jquery-ui.css">
    <style type="text/css">
        .loading{
            position:fixed;
            z-index:9999;
            background: rgba(17, 17, 17, 0.5);
            width:100%;
            height:100%;
            top:0; 
            left:0;
        }

        .loading div{
            position: absolute;
            background-image: url(<?=base_url('public/resources/loading.gif');?>);
            background-size: 60px 60px;
            top:50%;
            left:50%;
            width:60px;
            height:60px;
            margin-top:-30px;
            margin-left:-30px;
        }
    </style>
</head>
<body>
	<nav class="navbar navbar-alvasoft" style="border-radius: 0px; border: none;">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="<?=base_url('home');?>" class="navbar-brand">Mister Pan</a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="ddb">
                    <?php if($accessVentas > 0){ ?>
                    <li><a href="<?=base_url('sale');?>"><span class="glyphicon glyphicon-arrow-up"></span> Ventas</a></li>
                    <?php } ?>
                    <?php if($accessCompras > 0){ ?>
                    <li><a href="<?=base_url('shopping');?>"><span class="glyphicon glyphicon-shopping-cart"></span> Compras</a></li>
                    <?php } ?>
                    <?php if($accessNota > 0){ ?>
                    <li><a href="<?=base_url('note');?>"><span class="glyphicon glyphicon-file"></span> Nota Credito</a></li>
                    <?php } ?>
                    <?php if($accessProducto > 0){ ?>
                    <li><a href="<?=base_url('product');?>"><span class="glyphicon glyphicon-list-alt"></span> Productos</a></li>
                    <?php } ?>
                    <?php if($accessCliente > 0 OR $accessProveedor > 0){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span> Contribuyentes <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" style="width: 100px;">
                            <?php if($accessCliente > 0){ ?>
                            <li><a href="<?=base_url('customer');?>"> Clientes</a></li>
                            <?php } ?>
                            <?php if($accessProveedor > 0){ ?>
                            <li><a href="<?=base_url('provider');?>"> Proveedores</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($accessEmpleado > 0 OR $accessUsuario > 0){ ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="glyphicon glyphicon-user"></span> Personal <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" style="width: 100px;">
                            <?php if($accessEmpleado > 0){ ?>
                            <li><a href="<?=base_url('employee');?>"> Empleados</a></li>
                            <?php } ?>
                            <?php if($accessUsuario > 0){ ?>
                            <li><a href="<?=base_url('user');?>"> Usuarios</a></li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if($accessReporte > 0){ ?>
                    <li><a href="<?=base_url('report');?>"><span class="glyphicon glyphicon-user"></span> Reportes</a></li>
                    <?php } ?>  
                </ul>
                <ul class="nav navbar-nav navbar-right" id="ddb">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <?=$userName;?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="javascript:void(0);" onClick="openModalSettings()">
                                    <span class="glyphicon glyphicon-cog"></span> <?=$menu_settings;?>
                                </a>
                            </li>
                            <li>
                                <a href="<?=base_url('logout');?>">
                                    <span class="glyphicon glyphicon-log-out"></span> <?=$menu_close_system;?>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">

        