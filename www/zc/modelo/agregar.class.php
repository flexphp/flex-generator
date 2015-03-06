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
    function agregar($caracteristicas, $accion) {
        $this->crear($caracteristicas, $accion);
    }

    /**
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la 
     * variable $reultado (IMPORTANTE)
     */
    public function crear($caracteristicas, $accion) {
        if($accion != ZC_ACCION_AGREGAR){
            // No es la accion esperada, no crea nada
            throw new Exception(__FUNCTION__ . ': Error en la accion, se esperaba: ' . ZC_ACCION_AGREGAR);
        }
        $php = '';
//        foreach ($caracteristicas as $nro => $prop) {
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
            $php .= $this->comando('');
            $php .= $this->comando('$resultado = $CI->db->insert(\'mytable\', $data);');
            $php .= $this->comando('if(!$resultado){');
            $php .= $this->comando(insertarEspacios(4) . '// Mensaje/causa de error devuelto');
            $php .= $this->comando(insertarEspacios(4) . '$Resultado[0][\'error\'] = json_encode($CI->db->_error_message());');
            $php .= $this->comando('}');
//        }
        $this->_html = $php;
    }

}