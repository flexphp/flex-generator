<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';
/**
 * Crea la funcion de agregar (insert)
 */
require_once 'agregar.class.php';

/**
 * Crea acciones: agregar, buscar, modificar, eliminar, cancelar, defecto
 */
class accion extends elementos {

    /**
     * Formulario o tabla relacionado
     * @var string
     */
    protected $_tabla = null;

    /**
     * Tipo de accion a ejecutar
     * @var string
     */
    protected $_accion = null;

    /**
     * Conjunto de elementos tipo input del formulario, corresponde a los campos
     * @var array
     */
    protected $_campos = array();

    /**
     * Crear las acciones, segun el tipo de elemento
     * @param array $caracteristicas Caracteristicas de la accion a crear
     * @param string $tabla Nombre de la tabla a manejar
     * @param string $accion Tipo de boton a crear
     */
    function __construct($caracteristicas, $tabla, $accion) {
        $this->_accion = $accion;
        $this->_tabla = strtolower($tabla);
        $this->_campos = $caracteristicas;
    }

    /**
     * Selecciona el tipo de accion crear segun el tipo de boton seleccionado
     */
    public function crear() {
        switch ($this->_accion) {
            case ZC_ACCION_AGREGAR:
                $accion = new agregar($this->_campos, $this->_tabla, $this->_accion);
                break;
            case ZC_ACCION_BUSCAR:
//                $accion = new buscar($this->_campos, $this->_accion);
                break;
            case ZC_ACCION_MODIFICAR:
//                $accion = new modificar($this->_campos, $this->_accion);
                break;
            case ZC_ACCION_BORRAR:
//                $accion = new borrar($this->_campos, $this->_accion);
                break;
            case ZC_ELEMENTO_RESTABLECER:
                break;
            case ZC_ELEMENTO_CANCELAR:
                break;
            case ZC_ELEMENTO_BOTON:
            default :
                break;
        }
        // Establece la accion creada
        $this->_html = (isset($accion)) ? $accion->devolver() : $this->comando('$resultado = implode(\'|\', func_get_args());');
        return $this;
    }

    protected function comando($cmd) {
        return insertarEspacios(12) . $cmd . FIN_DE_LINEA;
    }

}
