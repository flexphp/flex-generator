<?php

/**
 * Crea acciones: BORRAR, depende de class "accion" por la funcion  accion::devolver
 */
class borrar extends accion {

    /**
     * Crea la accion de borrar, es decir todo el proceso la actualizadion registro eliminado del UPDATE en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }
    
    /**
     * Selecciona crea la accion modificar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_BORRAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_BORRAR);
        }
        $php = '';
        // Agrega la condicion de busqueda
        $php .= $this->comando('', 12);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('// Nombre de la tabla afectada', 12);
        $php .= $this->comando('$tabla = \'' . $this->_tabla . '\';', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', $tabla);', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->$tabla->borrar($id);', 12);
        $php .= $this->comando('switch (true){', 12);
        $php .= $this->comando('case (isset($rpta[\'error\']) && \'\' != $rpta[\'error\']):', 12);
        $php .= $this->comando('// Errores durante la ejecucion', 16);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($rpta[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Resultado', 16);
        $php .= $this->comando('$resultado = $rpta[\'resultado\'];', 16);
        $php .= $this->comando('$cta = $rpta[\'cta\'];', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 12);
        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_BORRAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_BORRAR);
        }
        $php = '';
        $php .= $this->comando('function borrar($id = null){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (!isset($id) || !is_int($id)):', 12);
        $php .= $this->comando('// No existe id de busqueda', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, no se puede eliminar.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case $this->db->update(\'' . $this->_tabla . '\', array(\'zc_eliminado\' => 1), array(\'id\' => $id)) == false:', 12);
        $php .= $this->comando('// Mensaje/causa de error devuelto', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($this->db->_error_message());', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Devuelve el id borrado campo y el numero de filas efectadas', 16);
        $php .= $this->comando('$rpta[\'resultado\'] = $id;', 16);
        $php .= $this->comando('// Siempre devuelve 1, aun el registro no se cambie', 16);
        $php .= $this->comando('$rpta[\'cta\'] = 1;', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 8);
        $php .= $this->comando('return $rpta;', 8);
        $php .= $this->comando('}', 4);
        $this->_funcion = $php;
        return $this;
    }

    /**
     * Varibles utilizadas durante la creacion de los servicios web
     * @return \borrar
     */
    public function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no vuelve a asignarlos
            return $this;
        }
        // Herada los de la clase padre
        $this->_inicializarCliente[] = "'id' => \$datos['id']";
        $this->_inicializarServidor[] = "'id' => 'xsd:int'";
        $this->_parametrosServidor[] = '$id';
        $this->_tipoPlantilla = 'jsLlamadosBorrarAjax.js';

        $this->_yaInicio = true;
        return $this;
    }

}
