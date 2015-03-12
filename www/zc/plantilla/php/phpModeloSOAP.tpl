<?php

class {_nombreModelo_} extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('nusoap');
        $this->load->database();
    }

    {_clienteWS_}
    {_procesarWS_}
    {_funciones_}
    {_validacion_}
    
    /**
     * Valida los filtros aplicados en el formulario de busqieda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     */
    function validarFiltros($campos, $accion){
        // Errores durante la validacion
        $rpta['error'] = '';
        // Condicion formada con los filtros dados
        $rpta['condicion'] = array();
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        // Determina si se deben validar los filtros
        $filtros = explode('|??|', $campos);
        
        foreach ($filtros as $cadaFiltro) {
            list($campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            if($campo == ''){
                // Campo no valido
                continue;
            }
            $datos[$campo] = $valor;
            $rptaValidacion = $this->{_nombreModelo_}->{_nombreValidacion_}($datos);
            if (isset($rptaValidacion['error']) && '' != $rptaValidacion['error']) {
                $rpta['error'] .=  $rptaValidacion['error'];
            }
            $rpta['condicion'][$campo . ' ' . $operador] = $valor;
        }
        return $rpta;
    }
}
