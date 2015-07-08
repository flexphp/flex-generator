<?php

/**
 * Crea acciones: modificar, depende de class "accion" por la funcion  accion::devolver
 */
class modificar extends accion {

    /**
     * Crea la accion de modificar, es decir todo el proceso de creacion del UPDATE en SQL
     * @param array $caracteristicas Caracteristicas de la accion
     * @param string $accion  Accion a crear
     */
    function __construct($caracteristicas, $tabla, $accion, $funcionValidacion = null) {
        parent::__construct($caracteristicas, $tabla, $accion);
        $this->_funcionValidacion = $funcionValidacion;
    }

    public function inicializar() {
        $cmd = $this->comando('$data = array();', 12);
        foreach ($this->_campos as $nro => $campo) {
            switch (true) {
                case $campo[ZC_ELEMENTO] == ZC_ELEMENTO_CHECKBOX:
                    // Devuelve el alemento al array original
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = implode(',', json_decode(\${$campo[ZC_ID]}, true));", 12);
                    break;
                case $campo[ZC_DATO] == ZC_DATO_CONTRASENA:
                    // Encripta las contrasenas
                    $cmd .= $this->comando("if(\${$campo[ZC_ID]} != ''){", 12);
                    $cmd .= $this->comando("// La contrasena cambio", 16);
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = sha1(\${$campo[ZC_ID]});", 16);
                    $cmd .= $this->comando("}", 12);
                    break;
                default:
                    $cmd .= $this->comando("\$data['{$this->_campos[$nro][ZC_ID]}'] = \${$campo[ZC_ID]};", 12);
                    break;
            }
        }
        return $cmd;
    }

    /**
     * Selecciona crea la accion modificar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function crear() {
        if ($this->_accion !== ZC_ACCION_MODIFICAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_MODIFICAR);
        }
        $php = '';
        $php .= $this->comando('// Establece los valores de cada uno de los campos', 12);
        $php .= $this->inicializar($this->_campos);
        $php .= $this->comando('// Se instancia un nuevo controlador, desde la funcion no es posible acceder al $this original', 12);
        $php .= $this->comando('$CI = new CI_Controller;', 12);
        $php .= $this->comando('$CI->load->model(\'' . $this->_modelo . '\', \'modelo\');', 12);
        $php .= $this->comando('// Ejecucion de la accion', 12);
        $php .= $this->comando('$rpta = $CI->modelo->modificar($data, $id);', 12);
        $php .= $this->comando('switch (true){', 12);
        $php .= $this->comando('case (isset($rpta[\'error\']) && count($rpta[\'error\']) > 0):', 16);
        $php .= $this->comando('// Errores durante la ejecucion', 20);
        $php .= $this->comando('$error = $rpta[\'error\'];', 20);
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
     * Selecciona crea la accion agregar. el resultado de la accion se almacena en la
     * variable $resultado (IMPORTANTE)
     */
    public function funcion() {
        if ($this->_accion !== ZC_ACCION_MODIFICAR) {
            // No es la accion esperada, no crea nada
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Error en la accion, se esperaba: ' . ZC_ACCION_MODIFICAR);
        }
        $php = '';
        $php .= $this->comando('function modificar($campos, $id = null){', 4);
        $php .= $this->comando('$rpta = array();', 8);
        $php .= $this->comando('$validacion = $this->' . $this->_funcionValidacion . '($campos);', 8);
        $php .= $this->comando('switch (true){', 8);
        $php .= $this->comando('case (isset($validacion[\'error\']) && count($validacion[\'error\']) > 0):', 12);
        $php .= $this->comando('// Errores durante la validacion de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode($validacion[\'error\']);', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case (!isset($id)):', 12);
        $php .= $this->comando('// No existe id de actualizacion', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, no se puede actualizar.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case !$this->db->initialize():', 12);
        $php .= $this->comando('// Error en la conexion a la base de campos', 16);
        $php .= $this->comando('$rpta[\'error\'] = json_encode(\'Error, intentelo nuevamente.\');', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('case count($campos) == 0:', 12);
        $php .= $this->comando('// No hay campos para actualizar', 16);
        $php .= $this->comando('break;', 16);
        $php .= $this->comando('default:', 12);
        $php .= $this->comando('foreach ($campos as $campo => $valor) {', 16);
        $php .= $this->comando('$this->db->set($campo, (($valor == null && $valor !== 0) ? NULL : $valor));', 20);
        $php .= $this->comando('}', 16);
        $php .= $this->comando('// Condicion aplicada en la actualizacion', 16);
        $php .= $this->comando('$this->db->where(array(\'id\' => $id, \'zc_eliminado is null\' => null));', 16);
        $php .= $this->comando('// Mensaje/causa de error devuelto', 16);
        $php .= $this->comando('$rpta[\'error\'] = (!$this->db->update(\'' . $this->_tabla . '\')) ? json_encode($this->db->_error_message()) : array();', 16);
        $php .= $this->comando('// Devuelve el id actualizado', 16);
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
     * @return \modificar
     */
    public function inicializarAccion() {
        if (isset($this->_yaInicio)) {
            // Ya esta definido, no vuelve a asignarlos
            return $this;
        }
        // Herada los de la clase padre
        $this->_inicializarCliente = parent::inicializarAccion()->devolverInicializarCliente();
        $this->_inicializarCliente[] = "'id' => \$datos['id']";

        $this->_inicializarServidor = parent::inicializarAccion()->devolverInicializarServidor();
        $this->_inicializarServidor[] = "'id' => 'xsd:int'";

        $this->_parametrosServidor = parent::inicializarAccion()->devolverParametrosServidor();
        $this->_parametrosServidor[] = '$id';

        // La inicializacion se hace diferente para manejar los campos tipo passsword, si no se diligencian
        // es porque no cambian
        foreach ($this->_campos as $nro => $campo) {
            switch ($campo[ZC_DATO]){
                case ZC_DATO_CONTRASENA:
                    // Se valida que la contrasena este diligenciada
                    $this->_asignacionControlador[] = '// Clave modificada';
                    $this->_asignacionControlador[] = "\$datos['{$campo[ZC_ID]}'] = (\$this->input->post('{$campo[ZC_ID]}') != '') ? \$this->input->post('{$campo[ZC_ID]}') : null;";
                    break;
            }
        }

        $this->_tipoPlantilla = 'jsLlamadosModificarAjax.js';

        $this->_yaInicio = true;
        return $this;
    }

}
