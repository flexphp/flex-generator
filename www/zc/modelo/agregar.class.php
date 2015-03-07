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
        $php .= $this->comando('$cantidad = func_num_args();');
        $php .= $this->comando('$args = get_defined_vars();');
        $php .= $this->comando('$contador = 0;');
        $php .= $this->comando('foreach($args as $campo => $valor){');
        $php .= $this->comando(insertarEspacios(4) . 'if(isset($$campo) && $contador < $cantidad){');
        $php .= $this->comando(insertarEspacios(8) . '$data[$campo] = $valor;');
        $php .= $this->comando(insertarEspacios(4) . '}');
        $php .= $this->comando(insertarEspacios(4) . '++$contador;');
        $php .= $this->comando('}');
        $php .= $this->comando('');
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original');
        $php .= $this->comando('$CI = new CI_Controller;');
        $php .= $this->comando('$CI->load->database();');
        $php .= $this->comando('$CI->load->model(\'modelo_\' . $tabla, $tabla);');
        $php .= $this->comando('// Validacion de los datos');
        $php .= $this->comando('$error = $CI->$tabla->validacionTest($data);');
        $php .= $this->comando('if (isset($error[\'error\']) && \'\' != $error[\'error\']) {');
        $php .= $this->comando(insertarEspacios(4) . '$Resultado[0][\'error\'] = json_encode($error[\'error\']);');
        $php .= $this->comando('} elseif (!$CI->db->insert($tabla, $data)) {');
        $php .= $this->comando(insertarEspacios(4) . '// Mensaje/causa de error devuelto');
        $php .= $this->comando(insertarEspacios(4) . '$Resultado[0][\'error\'] = json_encode($CI->db->_error_message());');
        $php .= $this->comando('}');
        $php .= $this->comando('// Devuelve el id insertado campo');
        $php .= $this->comando('$resultado = $CI->db->insert_id();');
        $this->_html = $php;
    }

}
