<?php

// Tiempo de inicio del proceso de creacion
list($usec, $sec) = explode(" ", microtime());
$tiempoInicio = ((float) $usec + (float) $sec);
// Archivo de configuracion itilizado
require 'conf.php';
// Funciones utilizadas durante la creacion del proyecto
require RUTA_GENERADOR_CODIGO . '/includes/libreria.inc.php';
// Clase para el manejo de plantillas
require RUTA_GENERADOR_CODIGO . '/modelo/plantilla.class.php';
// Clase utilizada pra el procesamiento del archivo XML
require RUTA_GENERADOR_CODIGO . '/modelo/xml.class.php';
// Clase central del generador de codigo
require RUTA_GENERADOR_CODIGO . '/modelo/formulario.class.php';

// Mostrar todos los errores generados
error_reporting(E_ALL);

/**
 * Description of crearFormulario
 *
 * @author root
 */
try {
    /**
     * JQuery homologado
     */
    $jqueryjs = new plantilla();
    $jqueryjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jquery.v2.1.1.min.js');
    $jqueryjs->crearPlantilla('../publico/js', 'js');

    /**
     * Parsley Homologado
     */
    $parsleyjs = new plantilla();
    $parsleyjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/parsley.v2.0.5.js');
    $parsleyjs->crearPlantilla('../publico/js', 'js');

    /**
     * Boostrap Homologado
     */
    $bootstrapjs = new plantilla();
    $bootstrapjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/bootstrap.v3.3.2.min.js');
    $bootstrapjs->crearPlantilla('../publico/js', 'js');

    /**
     * Datapicker
     */
    $datetimepickerjs = new plantilla();
    $datetimepickerjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/datetimepicker.v4.7.14.min.js');
    $datetimepickerjs->crearPlantilla('../publico/js', 'js');

    $momentjs = new plantilla();
    $momentjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/moment.v2.9.0.min.js');
    $momentjs->crearPlantilla('../publico/js', 'js');

    /**
     * Lenguaje datapicker
     */
    $lenguajejs = new plantilla();
    $lenguajejs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/es.js');
    $lenguajejs->crearPlantilla('../publico/js', 'js');

    /**
     * JS con funciones javascript utilizadas por el sistema
     */
    $zcjs = new plantilla();
    $zcjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/zc.v1.0.0.js');
    $zcjs->crearPlantilla('../publico/js', 'js');

    $indexjs = new plantilla();
    $indexjs->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/index.html');
    $indexjs->crearPlantilla('../publico/js', 'html');

    /**
     * CSS Homologado
     */
    $bootstrapcss = new plantilla();
    $bootstrapcss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/bootstrap.min.css');
    $bootstrapcss->crearPlantilla('../publico/css', 'css');

    $datetimepicker = new plantilla();
    $datetimepicker->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/datetimepicker.min.css');
    $datetimepicker->crearPlantilla('../publico/css', 'css');

    $parsleycss = new plantilla();
    $parsleycss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/parsley.css');
    $parsleycss->crearPlantilla('../publico/css', 'css');

    $indexcss = new plantilla();
    $indexcss->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/css/index.html');
    $indexcss->crearPlantilla('../publico/css', 'html');

    /**
     * Iconos homologados
     */
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/fonts', '../publico/fonts');

    /**
     * Index de la carpeta publico
     */
    $index = new plantilla();
    $index->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/index.html');
    $index->crearPlantilla('../publico', 'html');

    /**
     * Configuracion para los archivos de paginacion
     */
    $pagina = new plantilla();
    $pagina->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/ci/pagination.tpl');
    if (defined('ZC_REGISTROS_POR_PAGINA')) {
        $pagina->asignarEtiqueta('porPagina', ZC_REGISTROS_POR_PAGINA);
    }
    $pagina->crearPlantilla('../application/config', 'php');

    /**
     * Crear archivo de configuracion de base de datos, valida y setea la configuracion segun corresponda
     */
    $db = new plantilla();
    $db->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/ci/database.tpl');
    if (defined('ZC_CONEXION_SERVIDOR')) {
        $db->asignarEtiqueta('servidor', ZC_CONEXION_SERVIDOR);
    }
    if (defined('ZC_CONEXION_USUARIO')) {
        $db->asignarEtiqueta('usuario', ZC_CONEXION_USUARIO);
    }
    if (defined('ZC_CONEXION_CLAVE')) {
        $db->asignarEtiqueta('clave', ZC_CONEXION_CLAVE);
    }
    if (defined('ZC_CONEXION_BD')) {
        $db->asignarEtiqueta('bd', ZC_CONEXION_BD);
    }
    if (defined('ZC_MOTOR_MYSQL')) {
        $db->asignarEtiqueta('motor', ZC_MOTOR_MYSQL);
    }
    if (defined('ZC_MOTOR_DEFAULT_CHARSET')) {
        $db->asignarEtiqueta('charset', ZC_MOTOR_DEFAULT_CHARSET);
    }
    if (defined('ZC_MOTOR_DEFAULT_COLLATION')) {
        $db->asignarEtiqueta('collation', ZC_MOTOR_DEFAULT_COLLATION);
    }
    $db->crearPlantilla('../application/config', 'php');

    /**
     * Opciones de formulario
     */
    $nf = new procesarXML();
    $nf->cargarArchivosXML('xml');
} catch (Exception $e) {
    die($e->getMessage());
}

$tiempoFin = tiempoMilisegundos();
echo '<br/> Tiempo de ejecucion en segundos: ' . ($tiempoFin - $tiempoInicio);