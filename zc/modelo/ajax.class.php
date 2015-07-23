<?php

/**
 * Crea acciones: buscar, depende de class "accion" por la funcion  accion::devolver
 */
class ajax extends accion {

    /**
     * Crea la accion de buscar, es decir todo el proceso de creacion del SELECT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }

    /**
     * Crea la accion ajax. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_AJAX) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_AJAX);
        }
        $php = '';
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->ajax($tablas, $campos);', 12);

        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_AJAX) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_AJAX);
        }
        $php = '';
        $php .= $this->comando('function ajax($tablas, $campos){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (!isset($campos) || \'\' == $campos):', 12);
        $php .= $this->comando('case (!isset($tablas) || \'\' == $tablas):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos y tablas', 16);
        $php .= $this->comando('$rpta[\'error\'] = \'Error, intentelo mas tarde\';', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = \'Error, intentelo nuevamente.\';', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Agregar alias a los campos', 16);
        $php .= $this->comando('$this->db->select(\'id,\' . $campos);', 16);
        $php .= $this->comando('// Tablas involucradas', 16);
        $php .= $this->comando('$this->db->from($tablas);', 16);
        $php .= $this->comando('// Omite los registros que se encuentran en estado eliminado', 16);
        $php .= $this->comando('$this->db->where(array(\'zc_eliminado is null\' => null));', 16);
        $php .= $this->comando('// Ordena el resultado por el ultimo de los campos', 16);
        $php .= $this->comando('$this->db->order_by(end(explode(\',\', $campos)));', 16);
        $php .= $this->comando('// Resultado consulta', 16);
        $php .= $this->comando('$ressql = $this->db->get();', 16);
        $php .= $this->comando('// Existen resultados', 16);
        $php .= $this->comando('if($ressql && $ressql->num_rows() > 0){', 16);
        $php .= $this->comando('$rpta[\'cta\'] = $ressql->num_rows();', 20);
        $php .= $this->comando('$i = 0;', 20);
        $php .= $this->comando('foreach($ressql->result_array() as $row){', 20);
        $php .= $this->comando('$rpta[\'resultado\'][$i] = $row;', 24);
        $php .= $this->comando('++$i;', 24);
        $php .= $this->comando('}', 20);
        $php .= $this->comando('}', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('}', 8);
        $php .= $this->comando('return $rpta;', 8);
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
        $this->_inicializarCliente[] = "'tablas' => \$datos['tablas']";
        $this->_inicializarCliente[] = "'campos' => \$datos['campos']";

        $this->_inicializarServidor[] = "'tablas' => 'xsd:string'";
        $this->_inicializarServidor[] = "'campos' => 'xsd:string'";

        $this->_parametrosServidor[] = '$tablas, $campos';

        $this->_tipoPlantilla = '';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
