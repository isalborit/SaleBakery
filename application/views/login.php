<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SaleBakery v1.0 - Login</title>
        <!--<link rel="icon" type="image/ico" href="<?=base_url('public')?>/resources/logos.ico" />-->
        <link rel="stylesheet" href="<?=base_url('public')?>/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="<?=base_url('public')?>/bootstrap/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="<?=base_url('public')?>/css/dom.css">
        <link rel="stylesheet" href="<?=base_url('public')?>/css/fonts.css">
        <link rel="stylesheet" href="<?=base_url('public')?>/css/jquery-ui.css">
        <style type="text/css">
            body{
                background-image: url(<?=base_url('public/resources/fondo_panaderia.jpg');?>);
                background-position: center center;
                background-repeat: no-repeat;
                background-attachment: fixed;
                background-size: 100% 100%;
            }
            
        </style>
    </head>
    <body>
        <div class="box-login-mister">
            <div class="box-bg-mister">
                <div style="margin: auto; padding: 10px; margin-bottom: 20px;">
                    <div class="letter-mister">
                        <h1>C.M. MISTER PAN</h1>
                        <h4>Sistema de Ventas</h4>
                    </div>
                </div>
                <hr>
                <form name="form" id="formLogin" class="form-login">
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                        <input type="text" class="form-control" name="user" id="user" autofocus="true" placeholder="Escribe tu usuario" required>                                        
                    </div>
                    <br>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                        <input type="password" class="form-control" name="pass" id="pass" placeholder="Escribe tu contraseña" required>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-xs-8"></div>
                        <div class="col-xs-4">
                            <button type="submit" href="#" class="btn-login">Ingresar</button>
                        </div>
                    </div>
                </form>  
            </div>
        </div>
        <script src="<?=base_url('public')?>/js/jquery.js"></script>
        <script src="<?=base_url('public')?>/js/jquery-ui.js"></script>
        <script src="<?=base_url('public')?>/bootstrap/js/bootstrap.min.js"></script>
        <script type="text/javascript"> var baseUrl = '<?php echo base_url();?>'; </script>
        <script src="<?=base_url('public')?>/js/myjava_login.js"></script>
    </body>
</html>