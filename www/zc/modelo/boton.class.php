<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';

/**
 * Crea botones: button, reset
 */
class boton extends elementos{

    /**
     * Clase css del boton creado
     * @var string
     */
    private $_presentacion;

    /**
     * Crear botones de accion a crear
     * @param array $caracteristicas Caracteristicas del boton a crear
     * @param string $tipo Tipo de boton a crear
     */
    function __construct($caracteristicas, $tipo) {
        parent::__construct($caracteristicas);
        $this->tipo($tipo);
    }

    /**
     * Define todos los parametros del boton a crear, funcion principal de la clase
     */
    function crear() {
        $this->_html = "
            <button" .
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                " type='{$this->_tipo}'" .
                " class='btn {$this->_presentacion}'" .
                ">
                {$this->_etiqueta}
            </button>
		";
    }

    /**
     * Selecciona el tipo de boton a crear en el formulario
     * @param string $tipo Tipo de boton a crear, posibles valores:
     * restablecer:reset Boton para limpiar el formulario
     * boton:button Boton para ejecutar acciones
     */
    private function tipo($tipo) {
        switch ($tipo) {
            case 'restablecer':
                $this->_tipo = 'reset';
                $this->_presentacion = 'btn-danger';
                break;
            case 'boton':
            default :
                $this->_tipo = 'button';
                $this->_presentacion = 'btn-default';
                break;
        }
    }
}
