<?php

class {_nombreModelo_} extends CI_Model {

    /**
     * Nombres de los campos a utilizar en los alias, (Accion buscar)
     * @var array
     */
    private $_aliasCampo = array(
{_aliasCampos_}
    );

    /**
     * Cada una de las tablas relacionadas con el formulario
     * @var array
     */
    private $_tablasRelacionadas = array(
{_tablasRelacionadas_}
    );

    function __construct() {
        parent::__construct();
        $this->load->helper('url');
        $this->load->database();
        // Los parametros se DEBEN pasar en un array segun CodeIgniter
        $zc_params[0] = '{_nombreModelo_}';
        $this->load->library('zc', $zc_params);
    }

    {_funcionesModelo_}
    {_validacionModelo_}
    /**
     * Valida los filtros (condiciones SQL) aplicados en el formulario de busqueda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $accion Accion solicitado por parte del cliente
     * @return array Respuesta de la validacion de datos
     */
    function validarFiltros($campos, $accion){
        return $this->zc->validarFiltros($campos, $accion);
    }
}