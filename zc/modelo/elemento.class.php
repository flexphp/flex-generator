<?php


/**
 * Clase para crear caja|radio|lista|checkbox
 */
require RUTA_GENERADOR_CODIGO . '/modelo/Aelemento.class.php';

class elemento {

    /**
     * Instancia del elemento creado
     * @var \Aelemento
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
     * Filtros permitidos segun el tipo de dato del campo
     * @var array
     */
    private $_operadores = array(
        'lista' => array(
            '=' => '',
            '=' => 'igual a',
            '!=' => 'diferente de',
        ),
        'numero' => array(
            '=' => '',
            '=' => 'igual a',
            '>' => 'mayor a',
            '>=' => 'mayor o igual a',
            '<' => 'menor a',
            '<=' => 'menor o igual a',
            '!=' => 'diferente de',
        ),
        'texto' => array(
            'both%' => '',
            '=' => 'igual a',
            'both%' => 'contiene',
            'after%' => 'inicia con',
            'before%' => 'termina con',
            '!=' => 'diferente de',
        ),
    );

    /**
     * Crea el tipo de elemento segun las caracteristicas entregadas
     * @param array $caracteristicas Propiedades del elemento a crear
     */
    function __construct($caracteristicas) {
        $this->_caracteristicas = $caracteristicas;
        unset($caracteristicas);
    }

    /**
     * Crea el elmento segun su tipo devolviendo la instancia del elemento
     */
    public function crear() {
        cargarClase(__FILE__, __FUNCTION__, $this->_caracteristicas[ZC_ELEMENTO]);
        switch ($this->_caracteristicas[ZC_ELEMENTO]) {
            case ZC_ELEMENTO_CAJA:
                $this->agregarElementoCaja($this->_caracteristicas);
                break;
            case ZC_ELEMENTO_AREA:
                $this->agregarElementoArea($this->_caracteristicas);
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
                mostrarErrorZC(__FILE__, __FUNCTION__, " Tipo de elemento no definido: {$caracteristicas[ZC_ELEMENTO]}!");
        }

        return $this->_elemento;
    }

    /**
     * Agrega las cajas tipo texto, password dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \elemento
     */
    private function agregarElementoCaja($caracteristicas) {
        $this->_elemento = new caja($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega areas de texto, password dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \elemento
     */
    private function agregarElementoArea($caracteristicas) {
        $this->_elemento = new area($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las radios dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \elemento
     */
    private function agregarElementoRadio($caracteristicas) {
        $this->_elemento = new radio($caracteristicas, ((isset($this->_propiedad['controlador'])) ? $this->_propiedad['controlador'] : ''));
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las checkbox dentro del formulario, segun caracteristicas del XML
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \elemento
     */
    private function agregarElementoCheckbox($caracteristicas) {
        $this->_elemento = new checkbox($caracteristicas);
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Agrega las listas dentro del formulario, segun caracteristicas
     * @param string $caracteristicas Caracteristicas extraidas del XML
     * @return \elemento
     */
    private function agregarElementoLista($caracteristicas) {
        $this->_elemento = new lista($caracteristicas, ((isset($this->_propiedad['controlador'])) ? $this->_propiedad['controlador'] : ''));
        $this->_elemento->crear();
        return $this;
    }

    /**
     * Permite agregar caracteristicas adicionales a los elementos
     * @param string $nombre Nombre de la propiedad a agregar
     * @param string $valor Valor de la propiedad a agregar
     */
    public function propiedad($nombre, $valor) {
        $this->_propiedad[$nombre] = $valor;
    }

    /**
     * Devuelve la lista de operadores que pueden ser aplicados para la busqueda
     * @param string $tipo Tipo de dato que recibe el campo texto|numero|lista
     * @param string $oculto Establece si se debe mostrar por defecto el campo
     * @return string
     */
    public function operadores($tipo = '', $oculto = '') {
        if ('' == $tipo) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Tipo de dato para saber los operadores!?');
        }
        // Opciones de filtro, se envuelve en un div para manejar la ocultacion de forma mas efectiva
        $operadores = tabular("<div id='operador-{$tipo}' name='operador-{$tipo}' class='zc-operador{$oculto}'>", 24);
        $operadores .= tabular("<select id='zc-operador-{$tipo}' name='zc-operador-{$tipo}' class='form-control'>", 28);
        foreach ($this->_operadores[$tipo] as $key => $value) {
            $operadores .= tabular("<option value='$key'>$value</option>", 32);
        }
        $operadores .= tabular('</select>', 28);
        $operadores .= tabular('</div>', 24);
        return $operadores;
    }

}
