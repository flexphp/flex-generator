<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{_nombreFormulario_}</title>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.css">
        <!-- CSS Datapicker -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/datetimepicker.css">
        <!-- CSS Parsley -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/parsley.css">
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="<?php echo base_url(); ?>publico/js/html5shiv.js"></script>
          <script src="<?php echo base_url(); ?>publico/js/respond.js"></script>
        <![endif]-->
    </head>
    <body>
        <?php echo $navegacion; ?>
        <div class="container-fluid">
            <!-- Seperacion del top -->
            <div class="col-md-12"> </div>
            <!-- Nombre del formulario actual -->
            <div class="col-md-1"></div>
            <div class="col-md-10 text-center">
                <h2>{_nombreFormulario_}</h2>
                {_barraProgreso_}
                <!-- Errores devueltos por el servidor -->
                <div class="alert alert-danger" style="display: none;" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <span id="error-{_idFormulario_}"></span>
                </div>
                <!-- Fin Errores devueltos por el servidor -->
            </div>
            <div class="col-md-1"></div>
            <!-- Fin Nombre del formulario actual -->
            <!-- Formulario para el ingreso de datos -->
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form id="{_idFormulario_}" name="{_idFormulario_}" method="{_metodoFormulario_}" class="form-horizontal" role="form">
{_contenidoFormulario_}
                </form>
            </div>
            <div class="col-md-1"></div>
            <!-- Fin Formulario para el ingreso de datos -->
        </div>
        <!-- Id del registro actual, si esta vacio, es un registro nuevo, de lo contrario es una actualizacion -->
        <input type="hidden" id="zc-id-{_idFormulario_}" name="zc-id-{_idFormulario_}" value="<?php echo $id; ?>"/>
        <!-- Pagina para ejecutar acciones desde JS -->
        <input type="hidden" id="zc-controlador" name="zc-controlador" value="{_nombreControlador_}"/>
        <!-- URL del proyecto, se usa en los llamados ajax -->
        <input type="hidden" id="URLProyecto" name="URLProyecto" value="<?php echo base_url(); ?>"/>
        <!-- JS jQuery -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/jquery.js"></script>
        <!-- JS Datapicker -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/moment.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/es.js"></script>
        <!-- JS BootStrap -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/bootstrap.js"></script>
        <!-- JS Datapicker -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/datetimepicker.js"></script>
        <!-- JS Parsley -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/parsley.js"></script>
        <!-- Utilidades ZC -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/zc.js"></script>
{_archivoJavascript_}
    </body>
</html>