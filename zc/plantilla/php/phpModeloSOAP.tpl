<?php

class {_nombreModelo_} extends CI_Model {

    /**
     * Nombres de los campos a utilizar en los alias, (Accion buscar)
     * $var array
     */
    private $_aliasCampo = array(
        {_aliasCampos_}
    );
    
    /**
     * Cada una de las tablas relacionadas con el formulario
     * $var array
     */
    private $_tablasRelacionadas = array(
        {_tablasRelacionadas_}
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library(array('nusoap', 'zc'));
        $this->load->database();
    }

    {_llamadosModelo_}
    {_funcionesModelo_}
    {_validacionModelo_}
    
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
        $filtros = (is_string($campos))? explode('|??|', $campos) : $campos;

        foreach ($filtros as $llave => $cadaFiltro) {
            if($cadaFiltro == ''){
                //Sin filtro de busqueda
                continue;
            }
            if (strpos($cadaFiltro, '|?|') !== false) {
                list($tabla, $campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            } else {
                $tabla = '';
                $operador = '=';
                $campo = $llave;
                $valor = $cadaFiltro;
            }
            $datos[$campo] = $valor;
            $rptaValidacion = $this->{_nombreModelo_}->{_nombreValidacion_}($datos);
            if (isset($rptaValidacion['error']) && '' != $rptaValidacion['error']) {
                $rpta['error'] .=  $rptaValidacion['error'];
            }
            // Concatena la tabla, si existe
            $campo = ($tabla != '') ? $tabla . '.' . $campo : $campo;
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
        $this->db->select('COUNT(1) cta');
        // Agrega los filtros de busqueda
        $this->validarFiltros($campos, 'buscar');
        $ressql = $this->db->get($tabla);
        return $ressql->row()->cta;
    }
}
