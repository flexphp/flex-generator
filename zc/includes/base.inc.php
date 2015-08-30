<?php

// Funciones utilizadas durante la creacion del proyecto
require RUTA_GENERADOR_CODIGO . '/includes/libreria.inc.php';
// Clase para el manejo de plantillas
require RUTA_GENERADOR_CODIGO . '/modelo/plantilla.class.php';
// Clase utilizada para crear el menu de navegacion
require RUTA_GENERADOR_CODIGO . '/modelo/navegacion.class.php';
// Clase utilizada para el procesamiento del archivo XML
require RUTA_GENERADOR_CODIGO . '/modelo/hoja.class.php';
// Clase para la creacion del script de la base de datos
require RUTA_GENERADOR_CODIGO . '/modelo/bd.class.php';
// Clase central del generador de codigo
require RUTA_GENERADOR_CODIGO . '/modelo/formulario.class.php';

// Mostrar todos los errores generados
error_reporting(E_ALL);

function plantillas() {
    // Eliminar archivos xml generados en ejecuciones pasadas del generador de codigo
    eliminar(RUTA_GENERADOR_CODIGO . '/xml', false);
    // Imagenes e Iconos homologados
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/fonts', '../www/publico/fonts');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/img', '../www/publico/img');
    // Libreria para el manejo de web services
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/ci/nusoap.tpl', '../www/application/libraries/' . nombreControlador('nusoap.php'));
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/ci/nusoap', '../www/application/libraries/nusoap');
    // Libreria para el manejo de la paginacion
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpPaginacion.tpl', '../www/application/libraries/' . nombreControlador('pagination.php'));
    // Libreria donde estan metodos traversales (comunes) a toda la aplicacion
    $zcphp = new plantilla();
    $zcphp->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpLibreriaZC.tpl');
    $zcphp->asignarEtiqueta('datoNumerico', ZC_DATO_NUMERICO);
    $zcphp->asignarEtiqueta('datoEmail', ZC_DATO_EMAIL);
    $zcphp->asignarEtiqueta('datoFecha', ZC_DATO_FECHA);
    $zcphp->asignarEtiqueta('datoFechaHora', ZC_DATO_FECHA_HORA);
    $zcphp->asignarEtiqueta('datoHora', ZC_DATO_HORA);
    $zcphp->asignarEtiqueta('datoContrasena', ZC_DATO_CONTRASENA);
    $zcphp->asignarEtiqueta('datoUrl', ZC_DATO_URL);
    $zcphp->asignarEtiqueta('datoTexto', ZC_DATO_TEXTO);
    $zcphp->asignarEtiqueta('funcionValidacionDatos', nombreFuncionValidacionDatos());
    $zcphp->crearPlantilla('../www/application/libraries', 'php', nombreControlador('zc'));

    // Solo son necesarios cuando se crea login
    if (ZC_CREAR_LOGIN == ZC_OBLIGATORIO_SI) {
        // Tablas de configuracion, para usuarios, tipos de usuario, estados de usuario y login
        $usuariosxml = new plantilla();
        $usuariosxml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/xml/xmlUsuarios.tpl');
        $usuariosxml->asignarEtiqueta('tipoServicio', ZC_WS_SOAP);
        $usuariosxml->asignarEtiqueta('tipoMotor', ZC_BD_MOTOR);
        $usuariosxml->crearPlantilla('xml', 'xml', 'usuarios');

        $estados_usuarioxml = new plantilla();
        $estados_usuarioxml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/xml/xmlEstadosUsuario.tpl');
        $estados_usuarioxml->asignarEtiqueta('tipoServicio', ZC_WS_SOAP);
        $estados_usuarioxml->asignarEtiqueta('tipoMotor', ZC_BD_MOTOR);
        $estados_usuarioxml->crearPlantilla('xml', 'xml', 'estados_usuario');

        $tipos_usuarioxml = new plantilla();
        $tipos_usuarioxml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/xml/xmlTiposUsuario.tpl');
        $tipos_usuarioxml->asignarEtiqueta('tipoServicio', ZC_WS_SOAP);
        $tipos_usuarioxml->asignarEtiqueta('tipoMotor', ZC_BD_MOTOR);
        $tipos_usuarioxml->crearPlantilla('xml', 'xml', 'tipos_usuario');

        $loginxml = new plantilla();
        $loginxml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/xml/xmlLogin.tpl');
        $loginxml->asignarEtiqueta('paginaLogin', strtolower(ZC_LOGIN_PAGINA));
        $loginxml->asignarEtiqueta('tipoServicio', ZC_WS_SOAP);
        $loginxml->asignarEtiqueta('tipoMotor', ZC_BD_MOTOR);
        $loginxml->crearPlantilla('xml', 'xml', 'login');

        // Pagina y bienvenida de la aplicacion
        $iniciophp = new plantilla();
        $iniciophp->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorInicio.tpl');
        $iniciophp->asignarEtiqueta('nombreVista', nombreVista('inicio.html'));
        $iniciophp->asignarEtiqueta('nombreControlador', nombreControlador('inicio'));
        $iniciophp->asignarEtiqueta('navegacion', devolverNavegacion());
        $iniciophp->asignarEtiqueta('paginaLogin', strtolower(ZC_LOGIN_PAGINA));
        $iniciophp->crearPlantilla('../www/application/controllers', 'php', nombreControlador('inicio'));

        $iniciohtml = new plantilla();
        $iniciohtml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlInicio.tpl');
        $iniciohtml->asignarEtiqueta('nombreFormulario', 'Bienvenida');
        $iniciohtml->crearPlantilla('../www/application/views', 'html', nombreVista('inicio'));
    }

    // Compatiblilidad con HTML5
    // IE8 support of HTML5 elements and media queries
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/html5shiv.min.js', '../www/publico/js/html5shiv.js');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/respond.min.js', '../www/publico/js/respond.js');
    // JQuery homologado
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/jquery.min.js', '../www/publico/js/jquery.js');
    // Parsley Homologado
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/parsley.min.js', '../www/publico/js/parsley.js');
    // Boostrap Homologado
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/bootstrap.min.js', '../www/publico/js/bootstrap.js');
    // Datapicker
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/datetimepicker.min.js', '../www/publico/js/datetimepicker.js');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/moment.min.js', '../www/publico/js/moment.js');
    // Lenguaje datapicker
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/es.js', '../www/publico/js/es.js');
    // JS con funciones javascript utilizadas por el sistema
    $zcjs = new plantilla();
    $zcjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/zc.js');
    $zcjs->asignarEtiqueta('accionInit', ZC_ACCION_INIT);
    $zcjs->crearPlantilla('../www/publico/js', 'js', 'zc');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/js/index.html', '../www/publico/js/index.html');
    // CSS Homologado
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/css/bootstrap.min.css', '../www/publico/css/bootstrap.css');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/css/datetimepicker.min.css', '../www/publico/css/datetimepicker.css');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/css/parsley.css', '../www/publico/css/parsley.css');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/css/index.html', '../www/publico/css/index.html');
    // Index de la carpeta publico
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/html/index.html', '../www/publico/index.html');
}

function config() {
    // Configuracion para los archivos de paginacion
    $pagina = new plantilla();
    $pagina->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/ci/pagination.tpl');
    if (defined('ZC_REGISTROS_POR_PAGINA')) {
        $pagina->asignarEtiqueta('porPagina', ZC_REGISTROS_POR_PAGINA);
    }
    $pagina->crearPlantilla('../www/application/config', 'php');

    // Crear archivo de configuracion de base de datos, valida y setea la configuracion segun corresponda
    $db = new plantilla();
    $db->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/ci/database.tpl');
    if (defined('ZC_BD_MOTOR')) {
        $db->asignarEtiqueta('motor', ZC_BD_MOTOR);
    }
    if (defined('ZC_BD_SERVIDOR')) {
        $db->asignarEtiqueta('servidor', ZC_BD_SERVIDOR);
    }
    if (defined('ZC_BD_USUARIO')) {
        $db->asignarEtiqueta('usuario', ZC_BD_USUARIO);
    }
    if (defined('ZC_BD_CLAVE')) {
        $db->asignarEtiqueta('clave', ZC_BD_CLAVE);
    }
    if (defined('ZC_BD_ESQUEMA')) {
        $db->asignarEtiqueta('bd', ZC_BD_ESQUEMA);
    }
    if (defined('ZC_BD_CHARSET')) {
        $db->asignarEtiqueta('charset', ZC_BD_CHARSET);
    }
    if (defined('ZC_BD_COLLATION')) {
        $db->asignarEtiqueta('collation', ZC_BD_COLLATION);
    }
    $db->crearPlantilla('../www/application/config', 'php');
}
