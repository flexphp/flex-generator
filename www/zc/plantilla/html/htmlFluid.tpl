<!DOCTYPE html>
<html lang="en">
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
        <div class="container-fluid">
            <!-- Seperacion del top -->
            <div class="col-md-12"> </div>
            
            <!-- Nombre del formulario actual -->
            <div class="col-md-1"></div>
            <div class="col-md-5 text-center">
                <h2>{_nombreFormulario_}</h2>
                <!-- Barra de progreso -->
                <div class="progress ">
                    <div id="progreso-{_nombreFormulario_}" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                        <span id="msj-progreso-{_nombreFormulario_}" style="color: black">0%</span>
                    </div>
                </div>
                <!-- Fin Barra de progreso -->
                    
                <!-- Errores devueltos por el servidor -->
                <div class="alert alert-danger" role="alert">
                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                    <span class="sr-only">Error:</span>
                    <span id="error-{_nombreFormulario_}"></span>
                </div>
                <!-- Fin Errores devueltos por el servidor -->
            </div>
            <div class="col-md-5"></div>
            <div class="col-md-1"></div>
            <!-- Fin Nombre del formulario actual -->

            <!-- Formulario para el ingreso de datos -->
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <form id="{_nombreFormulario_}" name="{_nombreFormulario_}" method="{_metodoFormulario_}">
                    {_contenidoFormulario_}
                </form>
            </div>
            <div class="col-md-1"></div>
            <!-- Fin Formulario para el ingreso de datos -->
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
        <!-- Utilidades AC -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/zc.v1.0.0.js"></script>
        {_archivoJavascript_}
    </body>
</html>
