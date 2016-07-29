<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{_nombreFormulario_}</title>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap-theme.css">
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
            <!-- Bienvenida a la aplicacion -->
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <br/>
                Por favor elige una opci&oacute;n <span class="glyphicon glyphicon-arrow-up" aria-hidden="true"></span>.
                <p class="text-center">
                    <img src="<?php echo base_url(); ?>publico/img/inicio.jpg" class="hidden-xs hidden-sm" style="width: 90%; height: 90%" alt="Bienvenido"/>
                    <br/>
                    Bienvenido, esta aplicaci&oacute;n fue generada autom&aacute;ticamente con <i>ZeroCodigo</i>, gracias por preferirnos.
                </p>
            </div>
            <div class="col-md-1"></div>
            <!-- Fin Bienvenida a la aplicacion -->
        </div><!-- Fin row -->
        </div><!-- Fin container -->
        <!-- JS jQuery -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/jquery.js"></script>
        <!-- JS BootStrap -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/bootstrap.js"></script>
    </body>
</html>