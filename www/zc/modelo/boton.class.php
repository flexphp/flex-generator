<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';

/**
 * Crea botones: button, reset
 */
class boton extends elementos {

    /**
     * Tipo de boton/accion a ejecutar
     * @var string
     */
    private $_tipo;

    /**
     * Clase css del boton creado
     * @var string
     */
    private $_presentacion;

    /**
     * Icono que acompana el boton
     * @var string
     */
    private $_icono;

    /**
     * Crear botones de accion a crear
     * @param array $caracteristicas Caracteristicas del boton a crear
     * @param string $tipo Tipo de boton a crear
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
        $this->tipo();
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
                ">" .
                "{$this->_etiqueta}" .
                " {$this->_icono}" .
                "</button>";
    }

    /**
     * Selecciona el tipo de boton a crear en el formulario
     * @param string $tipo Tipo de boton a crear, posibles valores:
     * restablecer:reset Boton para limpiar el formulario
     * boton:button Boton para ejecutar acciones
     */
    private function tipo() {
        switch ($this->_prop[ZC_ELEMENTO]) {
            case ZC_ACCION_AGREGAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-ok" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-success';
                break;
            case ZC_ACCION_BUSCAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-search" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-primary';
                break;
            case ZC_ACCION_MODIFICAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-success';
                break;
            case ZC_ACCION_BORRAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-danger';
                break;
            case ZC_ELEMENTO_CANCELAR:
                $this->_tipo = 'button';
                $this->_icono = '<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>';
                $this->_presentacion = 'btn-warning';
                break;
            case ZC_ELEMENTO_RESTABLECER:
                $this->_tipo = 'reset';
                $this->_presentacion = 'btn-default';
                break;
            case ZC_ELEMENTO_BOTON:
            default :
                $this->_tipo = 'button';
                $this->_presentacion = 'btn-default';
                break;
        }
    }

}
