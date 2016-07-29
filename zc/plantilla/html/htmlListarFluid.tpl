<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{_nombreFormulario_}</title>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap-theme.css">
        <!-- CSS Datapicker -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/datetimepicker.css">
        <!-- CSS Parsley -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/parsley.css">
{_archivoCSS_}
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
        <div class="row">
            <!-- Seperacion del top -->
            <div class="col-md-12"> </div>
            <!-- Nombre del formulario actual -->
            <div class="col-md-12 text-center">
                <h2>{_nombreFormulario_}</h2>
            </div>
            <!-- Fin Nombre del formulario actual -->
            <!-- Formulario con filtros/opciones de busqueda datos -->
            <div class="col-md-12">
                <form id="{_idFormulario_}" name="{_idFormulario_}" method="{_metodoFormulario_}" class="form-horizontal" role="form">
                    <!-- Contador de los filtros utilizados, se us para no repetir ID de filtros -->
                    <input id="zc-filtros-cantidad-filtros" name="zc-filtros-cantidad-filtros" type="hidden" value="0"/>
{_contenidoFormulario_}
                </form>
            </div>
            <!-- Fin Formulario con filtros/opciones de busqueda datos -->
            <!-- Errores devueltos por el servidor -->
            <div class="col-md-12 text-center">
                <br/>
                <div class="alert alert-danger" style="display: none;" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <span id="error"></span>
                </div>
            </div>
        </div><!-- Fin row -->

        <div class="row">
            <!-- Formulario con resultados de la busqueda -->
            <div class="col-md-12">
                <div id="cargando-{_idFormulario_}" class="hidden text-center form-control">
                    <!-- Cargando -->
                    <span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> Cargando...
                </div>
                <div id="listado-{_idFormulario_}">
                    <!-- Resultados de la busqueda -->
                </div>
                <div id="paginacion-{_idFormulario_}">
                    <!-- Paginacion de la busqueda -->
                </div>
            </div>
            <!-- Fin Formulario con resultados de la busqueda -->
        </div><!-- Fin row -->
        </div><!-- Fin container -->
        <!-- Busquedas hechas desde la url, se usa en la accion de crear -->
        <input type="hidden" id="zc-filtros-predefinidos" name="zc-filtros-predefinidos" value="<?php echo $busquedaPredefinida; ?>"/>
        <!-- Pagina para ejecutar acciones desde JS -->
        <input type="hidden" id="zc-controlador" name="zc-controlador" value="{_nombreControlador_}"/>
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