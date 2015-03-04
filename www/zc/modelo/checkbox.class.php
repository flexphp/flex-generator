<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elementos.class.php';

/**
 * Crea elementos de lista, (select)
 */
class checkbox extends elementos {

    /**
     * HTML con el conjunto de opciones posibles para la lista
     * @var type
     */
    private $_opciones = '';

    /**
     * Contrucutor de listas (input select), define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
        $this->obligatorio($this->_prop[ZC_OBLIGATORIO], $this->_prop[ZC_OBLIGATORIO_ERROR]);
        $this->opciones($this->_prop[ZC_ELEMENTO_SELECT_OPCIONES]);
    }

    /**
     * Crear y define el elemento HTML a devolver
     * El estilo de creacion permite crear dos columnas para la recoleecion de datos
     * Cada una inicia con una columna en blanco (margen) izquierdo
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * Cada una inicia con una columna en blanco (margen) derecho
     */
    public function crear() {
        $this->_html = "
            <div class='row'>
                <div class='col-md-1'></div>
                <div class='col-md-2 text-right'>
                    <label>{$this->_etiqueta}{$this->_signoObligatorio}</label>
                </div>
                <div class='col-md-3'>
                    <div class='table table-bordered'>
                        <div class='text-center'>
                            {$this->_opciones}
                        </div>
                        <span id='error-{$this->_id}'></span>
                    </div>
                </div>
                <div class='col-md-5'></div>
                <div class='col-md-1'></div>
            </div>
        ";
    }

    /**
     * Devuelve el html con el conjunto de valores posible para el listado
     * @param type $opciones Valores que puede adoptar la lista de seleccion,
     * debe ser del tipo: id1 = valor1, id2 = valor2
     */
    private function opciones($opciones) {
        if (is_string($opciones)) {
            // Se agrega la opcion vacia al inicio del listado
            $cada_opcion = explode(',', $opciones);
            $opciones = array();
            foreach ($cada_opcion as $value) {
                if (strpos($value, '=') === false) {
                    throw new Exception(__FUNCTION__ . ': Tipo de checkbox no valido, se espera id1=valor1, id2=valor2');
                }
                list($id, $valor) = explode('=', $value);
                $opciones[trim($id)] = trim($valor);
            }
        }

        foreach ($opciones as $id => $valor) {
            $idOpcion = $this->_id . '_' . $id;
            $this->_opciones .= insertarEspacios(14) .
                    "<label for='{$idOpcion}'>" .
                    "<input" .
                    " type='checkbox'" .
                    " class='checkbox'" .
                    // Idenfitificador
                    // El nombre se maneja como arreglo para permitir enviar varios valores
                    " id='{$idOpcion}'" .
                    " name='{$this->_id}[]'" .
                    " value='{$id}'";
            if (!isset($config)) {
                // Solo agrega la configuracion a un elemento dentro del grupo
                $config = true;
                $this->_opciones .= "" .
                        " {$this->_obligatorio}" .
                        " {$this->_msjObligatorio}";
            }
            $this->_opciones .= "" .
                    // Ayuda visible
                    " data-placement='{$this->_posicionTitle}'" .
                    " data-toggle='tooltip'" .
                    " data-original-title='{$this->_etiqueta}: {$valor}'" .
                    // Elemento donde se mostraran los errores
                    " data-parsley-errors-container='#error-{$this->_id}'" .
                    "/>" .
                    "$valor" .
                    "</label>" . FIN_DE_LINEA;
        }
    }

}
