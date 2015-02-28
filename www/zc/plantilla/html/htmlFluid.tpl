<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{_nombreFormulario_}</title>
        <!-- Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.min.css">
        <!-- Parsley -->
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
            <div class="col-md-1"></div>
            <div class="col-md-6">
                <form id="frm_{_nombreFormulario_}" name="frm_{_nombreFormulario_}" method="{_metodoFormulario_}">
                    {_contenidoFormulario_}
                </form>
            </div>
        </div>

        <!-- URL del proyecto, se usa en los llamados ajax -->
        <input type="hidden" id="URLProyecto" name="URLProyecto" value="<?php echo base_url(); ?>"/>
        <!-- jQuery -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/jquery.v2.1.1.min.js"></script>
        <!-- ParsleyJS -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/parsley.v2.0.5.js"></script>
        {_archivoJavascript_}
    </body>
</html>
