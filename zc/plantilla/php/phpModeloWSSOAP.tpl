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
        $this->load->library('zc', array(get_class()));
    }

    /**
     * Configuracion de los campos utilizados por el formulario,
     * se usa en las validaciones del lado servidor y del lado cliente
     * @param string $id Identificador del campo
     * @return array
     */
    public function configuracionCampo($id = null) {
{_configuracionCampo_}
        // Para la correcta asignacion de valores del lado cliente
        return (isset($id) && isset($campo[$id])) ? array($id => $campo[$id]) : $campo;
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