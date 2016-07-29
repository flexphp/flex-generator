<?php

/**
 * Crea las funciones para realizar el logue con los campos seleccionados, se base 
 */
class loguear extends accion {

    /**
     * Crea la accion de logueo, es decir todo el proceso de creacion del SELECT en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        parent::__construct($caracteristicas, $tabla, $accion);
    }

    /**
     * Crea la accion loguear. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_LOGUEAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_LOGUEAR);
        }
        $php = '';
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Establece los valores de cada uno de los campos', 12);
        $php .= $this->inicializar();
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->' . ZC_ACCION_LOGUEAR . '($data, \'' . ZC_ACCION_LOGUEAR . '\');', 12);

        $this->_html = $php;
        return $this;
    }

    /**
     * Selecciona crea la accion buscar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_LOGUEAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_LOGUEAR);
        }
        $php = $this->comando('function ' . ZC_ACCION_LOGUEAR . '($campos, $accion){', 0);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$validacion = $this->validarFiltros($campos, $accion);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && count($validacion[\'error\']) > 0):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = $validacion[\'error\'];', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = \'Error, intentelo nuevamente.\';', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('// Agregar alias a los campos', 16);
        $php .= $this->comando('$this->db->select(implode(\', \', $this->_aliasCampo));', 16);
        $php .= $this->comando('// Tablas involucradas', 16);
        $php .= $this->comando('$this->db->from(\'' . ZC_LOGIN_TABLA . '\');', 16);
        $php .= $this->comando('// Join de las tablas relacionadas', 16);
        $php .= $this->comando('foreach ($this->_tablasRelacionadas as $tabla => $datos) {', 16);
        $php .= $this->comando('$this->db->join($tabla, "' . ZC_LOGIN_TABLA . '.{$datos[\'campo\']} = {$tabla}.id", $datos[\'join\']);', 20);
        $php .= $this->comando('}', 16);
        $php .= $this->comando('// Omite los registros que se encuentran en estado eliminado', 16);
        $php .= $this->comando('$this->db->where(array(\'' . ZC_LOGIN_TABLA . '.zc_eliminado is null\' => null));', 16);
        $php .= $this->comando('// Resultado consulta', 16);
        $php .= $this->comando('$ressql = $this->db->get();', 16);
        $php .= $this->comando('// Existen resultados', 16);
        $php .= $this->comando('if($ressql && $ressql->num_rows() > 0){', 16);
        $php .= $this->comando('// Numero total de elementos, esto segun los filtros de busqueda', 20);
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
        $this->_inicializarCliente = parent::inicializarAccion()->devolverInicializarCliente();
        $this->_inicializarServidor = parent::inicializarAccion()->devolverInicializarServidor();
        $this->_parametrosServidor = parent::inicializarAccion()->devolverParametrosServidor();
        $this->_asignacionControlador = parent::inicializarAccion()->devolverAsignacionControlador();
        $this->_tipoPlantilla = 'jsLlamadosLoginAjax.js';

        //Desactiva nuevas peticiones de inicializacion
        $this->_yaInicio = true;
        return $this;
    }

}
