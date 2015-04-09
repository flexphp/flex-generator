<?php

/**
 * Crea la barra de navegacion para la aplicacion
 */
class navegacion {

    /**
     * Html con los enlaces agregados
     * @var string
     */
    private $_html = '';

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Agrega el enlace a la barra de navegacion de la aplicacion
     * @param array $info Informacion a utilizar en el enlace
     */
    public function crear($info) {
        $controlador = $info['controlador'];
        $formulario = $info['formulario'];
        $this->_html .= '<li id="zc-menu-' . $controlador . '"><a href="<?php echo base_url(). \'index.php/' . $controlador . '\'; ?>">' . $formulario . '</a></li>' . insertarEspacios(30) . FIN_DE_LINEA;
    }

    /**
     * Crea le menu de navegacion dentro de la carpeta vistas (view) de la aplicacion
     */
    public function fin($directorioSalida = '../application/views', $extension = 'html', $opciones = array()) {
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlNavegacion.tpl');
        $plantilla->asignarEtiqueta('menuNavegacion', $this->_html);
        $plantilla->crearPlantilla($directorioSalida, $extension, 'navegacion');
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function __destruct() {

    }

}
