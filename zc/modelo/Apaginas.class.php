<?php

/**
 * Crear compartida para la creacion de paginas html
 */
abstract class Apaginas {

    /**
     * Id del formulario
     * @var string
     */
    public $_idFormulario;

    /**
     * Nombre del controlador, lo usa para crear archivos Ajax
     * @var string
     */
    public $_controlador;

    /**
     * Nombre del modelo
     * @var string
     */
    public $_modelo;

    public function __construct($idFormulario, $controlador, $modelo) {
        $this->_idFormulario = $idFormulario;
        $this->_controlador = $controlador;
        $this->_modelo = $modelo;
    }

    /**
     * Plantilla a utilizar para crear la vista de creacion y modificacion de la tabla
     * @return string
     */
    public function devolverPlantillaVista() {
        $tpl = 'htmlVistaFluid.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizada para crear el controlador
     * @return string
     */
    public function devolverPlantillaControlador() {
        $tpl = 'phpControladorSOAP.tpl';
        return $tpl;
    }

    /**
     * Plantilla utilizado para crear el archivo javascript
     * @return string
     */
    public function devolverPlantillaJavascript() {
        $tpl = 'jsInicializacionjQuery.js';
        return $tpl;
    }

    /**
     * Devuelve el comando de autenticacion de WS
     * @return string
     */
    public function devolverServidorAutenticacion() {
        $tpl = tabular('// Valida que el usuario este autenticado para poder usar los ws', 0);
        $tpl .= tabular('// solo si es un llamado remoto', 8);
        $tpl .= tabular('$this->load->library(\'zc\');', 8);
        $tpl .= tabular('if (!$this->zc->esWebService()) {', 8);
        $tpl .= tabular('$datos = $this->zc->validarAutenticacion();', 12);
        $tpl .= tabular('$this->load->model(\'' . nombreModelo(ZC_LOGIN_PAGINA) . '\', \'modelo\');', 12);
        $tpl .= tabular('$rpta = $this->modelo->' . ZC_ACCION_LOGUEAR . '($datos);', 12);
        $tpl .= tabular('if (isset($rpta[\'error\'])) {', 12);
        $tpl .= tabular('die($rpta[\'error\']);', 16);
        $tpl .= tabular('}', 12);
        $tpl .= tabular('}', 8);
        return $tpl;
    }

    /**
     * Crea los elementos dentro del formulario segun las caracteristicas entregadas por el xml
     * @param array $elementos Caracteristicas de los elementos a entregar
     * @return \formulario
     */
    public function agregarElementoFormulario($elementos) {
        foreach ($elementos as $caracteristicas) {
            if ($this->_esLogin) {
                // A los campos de formularios NO se les valida la longitud
                $caracteristicas[ZC_LONGITUD_MAXIMA] = -1;
                $caracteristicas[ZC_LONGITUD_MINIMA] = -1;
            }
        }
        return $this;
    }

    /**
     * Devuelve la plantilla para la distribucion de los botones
     * @param array $elementos Elementos del formlario
     * @return string
     */
    public function devolverPlantillaBotones() {
        $tpl = tabular("<div class='form-group col-sm-11 col-md-12 col-lg-12 text-right'>", 20);
        $tpl .= '{_elementoHTML_}';
        $tpl .= tabular("</div>", 20);
        return $tpl;
    }

    /**
     * Devuelve el nombre de la funcion para plantilla formulario
     * @return string
     */
    public function devolverFuncionAgregar() {
        $tpl = 'devolver';
        return $tpl;
    }

    /**
     * Devuelve el nombre del archivo controlador
     * @return string
     */
    public function devolverArchivoControlador() {
        $tpl = $this->_controlador;
        return $tpl;
    }

    /**
     * Devuelve la funcion para cargar la barra de progreso
     * @return string
     */
    public function devolverJsBarraProgreso() {
        $tpl = '';
        if (ZC_CREAR_PREGRESO == ZC_OBLIGATORIO_SI) {
            $tpl .= tabular("// Se agrega la validacion cuando los elementos pierden el foco", 0);
            $tpl .= tabular("$('#{$this->_idFormulario}').find($(formasValidar)).focusout(function (e) {", 4);
            $tpl .= tabular("// Manejo de la barra de progreso", 8);
            $tpl .= tabular("ZCBarraProgreso('{$this->_idFormulario}', formasValidar);", 8);
            $tpl .= tabular("});", 4);
        }
        return $tpl;
    }

    /**
     * Devuelve el HTML de la barra de progreso para las vistas
     * @return string
     */
    public function devolverHTMLBarraProgreso() {
        $tpl = '';
        if (ZC_CREAR_PREGRESO == ZC_OBLIGATORIO_SI) {
            $tpl = tabular('<!-- Barra de progreso -->', 0);
            $tpl .= tabular('<div class="progress">', 16);
            $tpl .= tabular('<div id="progreso-' . $this->_idFormulario . '" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">', 20);
            $tpl .= tabular('<span id="msj-progreso-' . $this->_idFormulario . '" style="color: black">0%</span>', 24);
            $tpl .= tabular('</div>', 20);
            $tpl .= tabular('</div>', 16);
            $tpl .= tabular('<!-- Fin Barra de progreso -->', 16);
        }
        return $tpl;
    }

    /**
     * Devuelve el llamado a la funcion para activar menu actual
     * @return string
     */
    public function devolverJsNavegacion() {
        $tpl = '';
        if (ZC_CREAR_NAVBAR == ZC_OBLIGATORIO_SI) {
            $tpl = tabular('// Menu actual', 0);
            $tpl .= tabular('ZCMenuActual();', 4);
        }
        return $tpl;
    }

    /**
     * Devuelve el llamado a la vista de navegacion
     * @return string
     */
    public function devolverNavegacion() {
        return devolverNavegacion();
    }

    /**
     * Define si de se debe crear la vista de busqueda
     */
    public function esLogin() {
        return false;
    }

}
