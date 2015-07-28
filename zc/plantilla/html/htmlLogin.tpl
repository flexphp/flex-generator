<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>{_nombreFormulario_}</title>
        <!-- CSS Bootstrap -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>publico/css/bootstrap.css">
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
        <nav class="navbar navbar-default navbar-fixed-top">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">ZC</a>
                </div>
                <!-- Menus -->
                <div id="navbar" class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="https://github.com/freddiegar/zerocodigo" title="Ir a ZeroCodigo">ZeroCodigo</a></li>
                    </ul>
                </div>
                <!-- Fin Menus -->
            </div>
        </nav>
        <br/>
        <br/>
        <br/>
        <br/>
        <div class="container-fluid">
            <div class="col-sm-6 col-md-4 col-md-offset-4">
                <div class="account-wall">
                    <div class="tab-content">
                        <div class="tab-pane active">
                            <div class="text-center">
                                <img src="<?php echo base_url(); ?>publico/img/login.jpg" class="hidden-xs hidden-sm" style="width: 70%; height: 70%" alt="Login"/>
                                <h1>{_nombreFormulario_}</h1>
                            </div>
                            <!-- Errores devueltos por el servidor -->
                            <div class="text-center">
                                <div class="alert alert-danger" style="display: none;" role="alert">
                                    <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                                    <span class="sr-only">Error:</span>
                                    <span id="error-{_idFormulario_}"></span>
                                </div>
                            </div>
                            <form id="{_idFormulario_}" name="{_idFormulario_}" method="{_metodoFormulario_}" class="form-signin form-horizontal" role="form">
{_contenidoFormulario_}
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pagina para ejecutar acciones desde JS -->
        <input type="hidden" id="zc-controlador" name="zc-controlador" value="{_nombreControlador_}"/>
        <!-- URL del proyecto, se usa en los llamados ajax -->
        <input type="hidden" id="URLProyecto" name="URLProyecto" value="<?php echo base_url(); ?>"/>
        <!-- JS jQuery -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/jquery.js"></script>
        <!-- JS BootStrap -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/bootstrap.js"></script>
        <!-- JS Parsley -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/parsley.js"></script>
        <!-- Utilidades ZC -->
        <script type="text/javascript" src="<?php echo base_url(); ?>publico/js/zc.js"></script>
{_archivoJavascript_}
    </body>
</html>