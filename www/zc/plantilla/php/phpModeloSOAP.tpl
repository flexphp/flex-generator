<?php

class {_nombreModelo_} extends CI_Model {

    /**
     * Nombres de los campos a utilizar en los alias, (Accion buscar)
     * $var array
     */
    private $_aliasCampo = array(
        {_aliasCampos_}
    );

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
     * @param array Respuesta del servidor de WS
     * @return array
     */ 
    function procesarRespuestaWS($ws) {
        if (isset($ws['errorWS'])) {
            /**
            * Error durante consulta webservice
            */
            $rpta['error'] = $ws['errorWS'];
        } else {
            $rptaWS = $ws['rptaWS'];
            if ($rptaWS) {
                if ($rptaWS[0]['error'] != '') {
                    $rpta['error'] = json_decode($rptaWS[0]['error'], true);
                } elseif ($rptaWS[0]['cta'] > 0) {
                    // Informacion devuelta
                    $rpta['infoEncabezado'] = json_decode($rptaWS[0]['infoEncabezado'], true);
                    // Cantidad de registros devueltos
                    $rpta['cta'] = $rptaWS[0]['cta'];
                    // Devuelve respuesta procesada
                    return $rpta;
                } else {
                    $rpta['error'] = 'No se encontraron datos.';
                }
            } else {
                $rpta['error'] = 'Error en servidor WS';
            }
        }
        return $rpta;
    }
    
    /**
     * Valida los filtros aplicados en el formulario de busqieda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $accion Accion solicitado por parte del cliente
     * @return array Respuesta de la validacion de datos
     */
    function validarFiltros($campos, $accion){
        // Errores durante la validacion
        $rpta['error'] = '';
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        // Determina si se deben validar los filtros
        $filtros = ($campos != '')? explode('|??|', $campos) : array();

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
    
    /**
     * Devuelve el numero total de registros que cumplen con los filtros aplicados 
     * en el formulario de busqueda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $tabla Nombre de la tabla
     * @return int Numero de registros encontrados
     */
    function totalRegistros($campos, $tabla){
        $filtros = ($campos != '')? explode('|??|', $campos) : array();
        $this->db->select('COUNT(1) cta');
        foreach ($filtros as $cadaFiltro) {
            if($cadaFiltro == ''){
                //Sin filtro de busqueda
                continue;
            }
            list($campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            // Agrega condiciones de busqueda segun los filtros
            if(strpos($operador, '%')){
                $this->db->like($campo, $valor, str_replace('%', '', $operador));
            }else{
                $this->db->where(array($campo.' '.$operador => $valor));
            }
        }
        $ressql = $this->db->get($tabla);
        return $ressql->row()->cta;
    }
}
