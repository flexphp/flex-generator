<?php

// Funciones utilizadas durante la creacion del proyecto
require RUTA_GENERADOR_CODIGO . '/includes/libreria.inc.php';
// Clase para el manejo de plantillas
require RUTA_GENERADOR_CODIGO . '/modelo/plantilla.class.php';
// Clase utilizada para crear el menu de navegacion
require RUTA_GENERADOR_CODIGO . '/modelo/navegacion.class.php';
// Clase utilizada para el procesamiento del archivo XML
require RUTA_GENERADOR_CODIGO . '/modelo/hoja.class.php';
// Clase central del generador de codigo
require RUTA_GENERADOR_CODIGO . '/modelo/formulario.class.php';

// Mostrar todos los errores generados
error_reporting(E_ALL);

function plantillas() {
    /**
     * Imagenes e Iconos homologados
     */
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/fonts', '../www/publico/fonts');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/img', '../www/publico/img');

    /**
     * Eliminar archivos xml generados en ejecuciones pasadas del generador de codigo
     */
    eliminar(RUTA_GENERADOR_CODIGO . '/xml', false);

    /**
     * Libreria donde estan metodos traversales (comunes) a toda la aplicacion
     */
    $zcphp = new plantilla();
    $zcphp->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpLibreriaZC.tpl');
    $zcphp->crearPlantilla('../www/application/libraries', 'php', 'zc');

    /**
     * Tablas de configuracion, para usuarios, tipos de usuario, estados de usuario y login
     */
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
    $loginxml->asignarEtiqueta('paginaLogin', ZC_LOGIN_PAGINA);
    $loginxml->asignarEtiqueta('tipoServicio', ZC_WS_SOAP);
    $loginxml->asignarEtiqueta('tipoMotor', ZC_BD_MOTOR);
    $loginxml->crearPlantilla('xml', 'xml', 'login');
    
    /**
     * Pagina y bienvenida de la aplicacion
     */
    $iniciophp = new plantilla();
    $iniciophp->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/php/phpControladorInicio.tpl');
    $iniciophp->asignarEtiqueta('nombreFormulario', 'Bienvenida');
    $iniciophp->asignarEtiqueta('nombreVista', 'vista_inicio.html');
    $iniciophp->asignarEtiqueta('nombreControlador', 'inicio');
    $iniciophp->asignarEtiqueta('paginaNavegacion', ZC_NAVEGACION_PAGINA);
    $iniciophp->crearPlantilla('../www/application/controllers', 'php', 'inicio');

    $iniciohtml = new plantilla();
    $iniciohtml->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlInicio.tpl');
    $iniciohtml->asignarEtiqueta('nombreFormulario', 'Bienvenida');
    $iniciohtml->asignarEtiqueta('nombreVista', 'vista_inicio.html');
    $iniciohtml->asignarEtiqueta('nombreControlador', 'inicio');
    $iniciohtml->crearPlantilla('../www/application/views', 'html', 'vista_inicio');

    /**
     * JQuery homologado
     */
    $jqueryjs = new plantilla();
    $jqueryjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jquery.v2.1.1.min.js');
    $jqueryjs->crearPlantilla('../www/publico/js', 'js');

    /**
     * Parsley Homologado
     */
    $parsleyjs = new plantilla();
    $parsleyjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/parsley.v2.0.5.js');
    $parsleyjs->crearPlantilla('../www/publico/js', 'js');

    /**
     * Boostrap Homologado
     */
    $bootstrapjs = new plantilla();
    $bootstrapjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/bootstrap.v3.3.2.min.js');
    $bootstrapjs->crearPlantilla('../www/publico/js', 'js');

    /**
     * Datapicker
     */
    $datetimepickerjs = new plantilla();
    $datetimepickerjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/datetimepicker.v4.7.14.min.js');
    $datetimepickerjs->crearPlantilla('../www/publico/js', 'js');

    $momentjs = new plantilla();
    $momentjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/moment.v2.9.0.min.js');
    $momentjs->crearPlantilla('../www/publico/js', 'js');

    /**
     * Lenguaje datapicker
     */
    $lenguajejs = new plantilla();
    $lenguajejs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/es.js');
    $lenguajejs->crearPlantilla('../www/publico/js', 'js');

    /**
     * JS con funciones javascript utilizadas por el sistema
     */
    $zcjs = new plantilla();
    $zcjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/zc.v1.0.0.js');
    $zcjs->crearPlantilla('../www/publico/js', 'js');

    $indexjs = new plantilla();
    $indexjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/index.html');
    $indexjs->crearPlantilla('../www/publico/js', 'html');

    /**
     * CSS Homologado
     */
    $bootstrapcss = new plantilla();
    $bootstrapcss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/bootstrap.min.css');
    $bootstrapcss->crearPlantilla('../www/publico/css', 'css');

    $datetimepicker = new plantilla();
    $datetimepicker->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/datetimepicker.min.css');
    $datetimepicker->crearPlantilla('../www/publico/css', 'css');

    $parsleycss = new plantilla();
    $parsleycss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/parsley.css');
    $parsleycss->crearPlantilla('../www/publico/css', 'css');

    $indexcss = new plantilla();
    $indexcss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/index.html');
    $indexcss->crearPlantilla('../www/publico/css', 'html');

    /**
     * Index de la carpeta publico
     */
    $index = new plantilla();
    $index->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/index.html');
    $index->crearPlantilla('../www/publico', 'html');
}

function config() {    
    /**
     * Configuracion para los archivos de paginacion
     */
    $pagina = new plantilla();
    $pagina->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/ci/pagination.tpl');
    if (defined('ZC_REGISTROS_POR_PAGINA')) {
        $pagina->asignarEtiqueta('porPagina', ZC_REGISTROS_POR_PAGINA);
    }
    $pagina->crearPlantilla('../www/application/config', 'php');

    /**
     * Crear archivo de configuracion de base de datos, valida y setea la configuracion segun corresponda
     */
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