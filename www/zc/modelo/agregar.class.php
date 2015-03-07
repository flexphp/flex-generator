<?php

/**
 * Crea acciones: agregar, deepnde de accion por la funcion  accion:devolver
 */
class agregar extends accion {

    /**
     * Crea la accion de agregar, es decir todo el proceso de creacion del INSERT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
        $this->crear();
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_AGREGAR) {
            // No es la accion esperada, no crea nada
            throw new Exception(__FUNCTION__ . ': Error en la accion, se esperaba: ' . ZC_ACCION_AGREGAR);
        }
        $php = '';
        $php .= $this->comando('//Nombre de la tabla afectada');
        $php .= $this->comando('$tabla = \'' . $this->_tabla . '\';');
        $php .= $this->comando('//Establece los valores de cada uno de los campos');
        $php .= $this->inicializar($this->_campos);
        $php .= $this->comando('');
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original');
        $php .= $this->comando('$CI = new CI_Controller;');
        $php .= $this->comando('$CI->load->model(\'modelo_\' . $tabla, $tabla);');
        $php .= $this->comando('$CI->load->database();');
        $php .= $this->comando('// Validacion de los datos');
        $php .= $this->comando('$error = $CI->$tabla->validacionTest($data);');
        $php .= $this->comando('switch (true){');
        $php .= $this->comando('case (isset($error[\'error\']) && \'\' != $error[\'error\']):', 4);
        $php .= $this->comando('// Errores durante la validacion de datos', 8);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($error[\'error\']);', 8);
        $php .= $this->comando('break;', 8);
        $php .= $this->comando('case !$CI->db->initialize():', 4);
        $php .= $this->comando('// Error en la conexion a la base de datos', 8);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 8);
        $php .= $this->comando('break;', 8);
        $php .= $this->comando('case $CI->db->insert($tabla, $data) == false:', 4);
        $php .= $this->comando('// Mensaje/causa de error devuelto', 8);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($CI->db->_error_message());', 8);
        $php .= $this->comando('default:', 4);
        $php .= $this->comando('// Devuelve el id insertado campo y el numero de filas efectadas', 8);
        $php .= $this->comando('$resultado = $CI->db->insert_id();', 8);
        $php .= $this->comando('$cta = $CI->db->affected_rows() > 0;', 8);
        $php .= $this->comando('break;', 8);
        $php .= $this->comando('}');
        $this->_html = $php;
    }

}
