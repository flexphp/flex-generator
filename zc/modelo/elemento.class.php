<?php


/**
 * Clase para crear caja|radio|lista|checkbox
 */
require RUTA_GENERADOR_CODIGO . '/modelo/Aelemento.class.php';

class elemento {

    /**
     * Instancia del elemento creado
     */
    public $_elemento;

    /**
     * Caracteristicas del elmento a crear
     * @var array
     */
    private $_caracteristicas = array();

    /**
     * Propiedades adicionales que pueden ser pasadas a los elmentos para su correcta creacion
     * @var array
     */
    private $_propiedad = array();

    /**
     * Crea el tipo de elemento segun las caracteristicas entregadas
     */
    function __construct($caracteristicas) {
        $this->_caracteristicas = $caracteristicas;
        unset($caracteristicas);
    }

    public function crear() {
        cargarClase(__FILE__, __FUNCTION__, $this->_caracteristicas[ZC_ELEMENTO]);
        switch ($this->_caracteristicas[ZC_ELEMENTO]) {
            case ZC_ELEMENTO_CAJA:
                $this->agregarElementoCaja($this->_caracteristicas);
                break;
            case ZC_ELEMENTO_RADIO:
                $this->agregarElementoRadio($this->_caracteristicas);
                break;
            case ZC_ELEMENTO_CHECKBOX:
                $this->agregarElementoCheckbox($this->_caracteristicas);
                break;
            case ZC_ELEMENTO_LISTA:
                $this->agregarElementoLista($this->_caracteristicas);
                break;
            default:
                mostrarErrorZC(__FILE__, __FUNCTION__, ": Tipo de elemento no definido: {$caracteristicas[ZC_ELEMENTO]}!");
        }

        return $this->_elemento;
    }

    /**
     * Agrega las cajas, radios, checkboxs, select dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarElementoCaja($caracteristicas) {
        $this->_elemento = new caja($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las radios dentro del formulario, segun caracteristicas del XML
     * @param string $tipo Caracteristicas extraidas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarElementoRadio($caracteristicas) {
        $this->_elemento = new radio($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las checkbox dentro del formulario, segun caracteristicas del XML
     * @param string $tipo Caracteristicas extraidas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarElementoCheckbox($caracteristicas) {
        $this->_elemento = new checkbox($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las listas dentro del formulario, segun caracteristicas
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \formulario
     */
    private function agregarElementoLista($caracteristicas) {
        $this->_elemento = new lista($caracteristicas, $this->_propiedad['controlador']);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Permite agregar caracterisiticas adicionales a los elementos
     */
    public function propiedad($nombre, $valor) {
        $this->_propiedad[$nombre] = $valor;
    }

}
