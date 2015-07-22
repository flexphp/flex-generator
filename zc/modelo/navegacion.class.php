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
        // La comparacion se hace en minusculas, ya que el contenido puede variar
        if (strtolower($controlador) != strtolower(ZC_LOGIN_PAGINA)) {
            // El controlador de login no se incluye en la pagina de navegacion
            $this->_html .= tabular('<li id="zc-menu-' . $controlador . '"><a href="<?php echo base_url(). \'index.php/' . $controlador . '\'; ?>">' . $formulario . '</a></li>', 24);
        }
    }

    /**
     * Crea le menu de navegacion dentro de la carpeta vistas (view) de la aplicacion
     */
    public function fin($directorioSalida = '../application/views', $extension = 'html', $opciones = array()) {
        $plantilla = new plantilla($opciones);
        $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/html/htmlNavegacion.tpl');
        $plantilla->asignarEtiqueta('menuNavegacion', $this->_html);
        $plantilla->asignarEtiqueta('accionInicio', '<?php echo base_url(). \'index.php/inicio\'; ?>');
        $plantilla->asignarEtiqueta('accionSalir', '<?php echo base_url(). \'index.php/' . nombreControlador(ZC_LOGIN_PAGINA) . '/desloguear\'; ?>');
        $plantilla->crearPlantilla($directorioSalida, $extension, ZC_NAVEGACION_PAGINA);
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function __destruct() {

    }

}
