<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{_nombreFormulario_}</title>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.min.css">
        <!-- CSS Datapicker -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/datetimepicker.min.css">
        <!-- CSS Parsley -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/parsley.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php echo $navegacion;?>
        <div class="container-fluid">
            <!-- Seperacion del top -->
            <div class="col-md-12"> </div>

            <!-- Bienvenida a la aplicacion -->
            <div class="col-md-1"></div>
            <div class="col-md-10">
                </br>
                Por favor, <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span> elige una opci&oacute;n.
                <p class="text-center">
                    <img src="<?php echo base_url(); ?>publico/img/inicio.jpg" style="width: 90%; height: 90%" alt="Bienvenido"/>
                    </br>
                    Bienvenido, esta aplicacion fue generada automaticamente con <i>ZeroCodigo</i>, gracias por usarlo.
                </p>
            </div>
            <div class="col-md-1"></div>
            <!-- Fin Bienvenida a la aplicacion -->
        </div>
                
        <!-- URL del proyecto, se usa en los llamados ajax -->
        <input type="hidden" id="URLProyecto" name="URLProyecto" value="<?php echo base_url(); ?>"/>
        <!-- JS jQuery -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/jquery.v2.1.1.min.js"></script>
        <!-- JS Datapicker -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/moment.v2.9.0.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/es.js"></script>
        <!-- JS BootStrap -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/bootstrap.v3.3.2.min.js"></script>
        <!-- JS Datapicker -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/datetimepicker.v4.7.14.min.js"></script>
        <!-- JS Parsley -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/parsley.v2.0.5.js"></script>
        <!-- Utilidades ZC -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/zc.v1.0.0.js"></script>
    </body>
</html>
