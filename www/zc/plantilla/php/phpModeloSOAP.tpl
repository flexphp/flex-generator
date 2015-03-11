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
        $rpta['condicion'] = '';
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        $filtros = explode('|??|', $campos);
        // Carga el modelo para poder hacer las validaciones
        $CI = new CI_Controller;
        $CI->load->model('{_nombreModelo_}');
        
        foreach ($filtros as $cadaFiltro) {
            list($campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            $datos[$campo] = $valor;
            $rptaValidacion = $CI->{_nombreModelo_}->validacion{_nombreFormulario_}($datos);
            if (isset($rptaValidacion['error']) && '' != $rptaValidacion['error']) {
                $rpta['error'] .=  $rptaValidacion['error'];
            }
            $rpta['condicion'][$campo . ' ' . $operador] = $valor;
        }
        return $rpta;
    }
}
