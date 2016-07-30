<?php

/**
 * Crea acciones: init, depende de class "accion" por la funcion  accion::devolver
 */
class init extends accion {

    /**
     * Crea la accion de init, configura las restricciones de los campos
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }

    /**
     * Crea la accion init. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_INIT) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Error en la accion, se esperaba: ' . ZC_ACCION_INIT);
        }
        $php = '';
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->init($campo);', 12);

        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_INIT) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Error en la accion, se esperaba: ' . ZC_ACCION_INIT);
        }
        $php = '';
        $php .= $this->comando('function init($campo = null){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('// Restricciones para el campo', 8);
        $php .= $this->comando('$rpta[\'info\'] = $this->configuracionCampo($campo);', 8);
        $php .= $this->comando('$rpta[\'cta\'] = count($rpta[\'info\']);', 20);
        $php .= $this->comando('return ' . ((ZC_BD_ES_UTF) ? '$rpta;' : '$this->zc->utf8_converter($rpta);'), 8);
        $php .= $this->comando('}', 4);
        $this->_funcion = $php;
        return $this;
    }

    /**
     * Varibles utilizadas durante la creacion de los servicios web
     * @return \buscar
     */
    public function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no vuelve a asignarlos
            return $this;
        }
        $this->_inicializarCliente[] = "'campo' => \$datos['campo']";
        $this->_inicializarServidor[] = "'campo' => 'xsd:string'";
        $this->_parametrosServidor[] = '$campo';
        $this->_tipoPlantilla = '';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
