<?php

class acciones {

    /**
     * Identificador unico del boton dentro del formulario
     * @var string
     */
    public $_id;

    /**
     * Descripcion del boton creado
     * @var string
     */
    public $_etiqueta;

    /**
     * Clase css del boton creado
     * @var string
     */
    public $_presentacion;

    /**
     * Construido del boton
     * @var string
     */
    private $_accion;

    /**
     * Crear botones de accion a crear
     * @param array $caracteristicas Caracteristicas del boton a crear
     * @param string $tipoAccion Tipo de boton a crear
     */
    function __construct($caracteristicas, $tipo) {
        $this->_id = $caracteristicas[ZC_ID];
        $this->_etiqueta = (!isset($caracteristicas[ZC_ETIQUETA])) ? $this->_id : $caracteristicas[ZC_ETIQUETA];
        $this->tipo($tipo);
    }

    /**
     * Define todos los parametros del boton a crear, funcion principal de la clase
     */
    function crearAccion() {
        $this->_accion = "
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

    /**
     * Imprime el boton del formulario
     * @return string
     */
    function imprimirAccion() {
        echo $this->_accion;
    }

    /**
     * Devuelve el tipo de boton creado
     * @return string
     */
    function devolverAccion() {
        return $this->_accion;
    }

}
