<?php

/**
 * Crea acciones: agregar, depende de class "accion" por la funcion  accion::devolver
 */
class agregar extends accion {

    /**
     * Crea la accion de agregar, es decir todo el proceso de creacion del INSERT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion, $funcionValidacion = null) {
        parent::__construct($caracteristicas, $tabla, $accion);
        $this->_funcionValidacion = $funcionValidacion;
    }

    /**
     * Crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_AGREGAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_AGREGAR);
        }
        $php = '';
        $php .= $this->comando('// Establece los valores de cada uno de los campos', 12);
        $php .= $this->inicializar($this->_campos);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->agregar($data);', 12);

        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_AGREGAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_AGREGAR);
        }
        $php = '';
        $php .= $this->comando('function agregar($campos){');
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$validacion = $this->' . $this->_funcionValidacion . '($campos);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && count($validacion[\'error\']) > 0):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($validacion[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case $this->db->insert(\'' . $this->_tabla . '\', $campos) == false:', 12);
        $php .= $this->comando('// Mensaje/causa de error devuelto', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($this->db->_error_message());', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Devuelve el id insertado campo y el numero de filas efectadas', 16);
        $php .= $this->comando('$rpta[\'resultado\'] = $this->db->insert_id();', 16);
        $php .= $this->comando('$rpta[\'cta\'] = $this->db->affected_rows() > 0;', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 8);
        $php .= $this->comando('return $rpta;', 8);
        $php .= $this->comando('}', 4);
        $this->_funcion = $php;
        return $this;
    }

    /**
     * Varibles utilizadas durante la creacion de los servicios web
     * @return \modificar
     */
    public function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no vuelve a asignarlos
            return $this;
        }
        $this->_inicializarCliente = parent::inicializarAccion()->devolverInicializarCliente();
        $this->_inicializarServidor = parent::inicializarAccion()->devolverInicializarServidor();
        $this->_parametrosServidor = parent::inicializarAccion()->devolverParametrosServidor();
        $this->_asignacionControlador = parent::inicializarAccion()->devolverAsignacionControlador();
        $this->_tipoPlantilla = 'jsLlamadosCrearAjax.js';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
