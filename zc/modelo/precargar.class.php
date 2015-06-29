<?php

/**
 * Crea acciones: buscar, depende de class "accion" por la funcion  accion::devolver
 */
class precargar extends accion {

    /**
     * Crea la accion de buscar, es decir todo el proceso de creacion del SELECT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }

    /**
     * Redefine la duncion de inicializacion para manejar las vearialbes de vusqueda
     * @return type
     */
    function inicializar() {
        $cmd = $this->comando("\$data = \$id;", 12);
        return $cmd;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_PRECARGAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_PRECARGAR);
        }
        $php = '';
        $php .= $this->comando('//Establece los valores de cada uno de los campos', 12);
//        $php .= $this->inicializar();
        $php .= $this->comando('', 12);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('// Nombre de la tabla afectada', 12);
        $php .= $this->comando('$tabla = \'' . $this->_tabla . '\';', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', $tabla);', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->$tabla->precargar($id, \'precargar\');', 12);
        $php .= $this->comando('switch (true){', 12);
        $php .= $this->comando('case (isset($rpta[\'error\']) && \'\' != $rpta[\'error\']):', 16);
        $php .= $this->comando('// Errores durante la ejecucion', 20);
        $php .= $this->comando('$Resultado[0][\'error\'] = json_encode($rpta[\'error\']);', 20);
        $php .= $this->comando('break;', 20);
        $php .= $this->comando('default:', 16);
        $php .= $this->comando('// Resultado', 20);
        $php .= $this->comando('$resultado = $rpta[\'resultado\'];', 20);
        $php .= $this->comando('$cta = $rpta[\'cta\'];', 20);
        $php .= $this->comando('break;', 20);
        $php .= $this->comando('}', 12);
        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_PRECARGAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_PRECARGAR);
        }
        $php = '';
        $php .= $this->comando('function precargar($id, $accion){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (!isset($id) || \'\' == $id):', 12);
        $php .= $this->comando('// El campo id es obligatorio para la busqueda', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error en la precarga (id)\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Devuelve los ddatos del id enviado', 16);
        $php .= $this->comando('$this->db->where(array(\'id\' => $id));', 16);
        $php .= $this->comando('// Omite los registros que se encuentran en estado eliminado', 16);
        $php .= $this->comando('$this->db->where(array(\'zc_eliminado is null\' => null));', 16);
        $php .= $this->comando('$ressql = $this->db->get(\'' . $this->_tabla . '\');', 16);
        $php .= $this->comando('if($ressql->num_rows() > 0){', 16);
        $php .= $this->comando('$rpta[\'resultado\'] = $ressql->row();', 20);
        $php .= $this->comando('$rpta[\'cta\'] = $ressql->num_rows();', 20);
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
        $this->_inicializarCliente[] = "'id' => \$datos['id']";
        $this->_inicializarServidor[] = "'id' => 'xsd:string'";
        $this->_parametrosServidor[] = '$id';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
