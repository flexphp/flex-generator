<?php

class {_nombreModelo_} extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('nusoap');
        $this->load->database();
    }

    {_llamadosModelo_}
    {_funcionesModelo_}
    {_validacionModelo_}

    /**
     * Procesa la respuesta devuelta por el servidor WS, verifica si existen errores
     * @return array
     */ 
    function procesarRespuestaWS($ws) {
        if (isset($ws['errorWS'])) {
            /**
            * Error durante consulta webservice
            */
            // $this->manejoError->crearError($ws['errorWS']);
            $rpta['error'] = $ws['errorWS'];
        } else {
            $rptaWS = $ws['rptaWS'];
            if ($rptaWS) {
                if ($rptaWS[0]['error'] != '') {
                    // $this->manejoError->crearError(json_decode($rptaWS[0]['error'], true));
                    $rpta['error'] = json_decode($rptaWS[0]['error'], true);
                } elseif ($rptaWS[0]['cta'] > 0) {
                    // Informacion devuelta
                    $rpta['infoEncabezado'] = json_decode($rptaWS[0]['infoEncabezado'], true);
                    // Cantidad de registros devueltos
                    $rpta['cta'] = $rptaWS[0]['cta'];
                    /**
                    * Quita campos del array
                    */
                    return $rpta;
                } else {
                    // $this->manejoError->crearError('No existen datos.');
                    $rpta['error'] = 'No se encontraron datos relacionados.';
                }
            } else {
                // $this->manejoError->crearError('Error en servidor WS');
                $rpta['error'] = 'Error en servidor WS';
            }
        }
        return $rpta;
    }
    
    /**
     * Valida los filtros aplicados en el formulario de busqieda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     */
    function validarFiltros($campos, $accion){
        // Errores durante la validacion
        $rpta['error'] = '';
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        // Determina si se deben validar los filtros
        $filtros = ($campos == '')? explode('|??|', $campos) : array();

        foreach ($filtros as $cadaFiltro) {
            if($cadaFiltro == ''){
                //Sin filtro de busqueda
                continue;
            }
            list($campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            $datos[$campo] = $valor;
            $rptaValidacion = $this->{_nombreModelo_}->{_nombreValidacion_}($datos);
            if (isset($rptaValidacion['error']) && '' != $rptaValidacion['error']) {
                $rpta['error'] .=  $rptaValidacion['error'];
            }
            // Agrega condiciones de busqueda segun los filtros
            if(strpos($operador, '%')){
                $this->db->like($campo, $valor, str_replace('%', '', $operador));
            }else{
                $this->db->where(array($campo.' '.$operador => $valor));
            }
        }
        return $rpta;
    }
}
