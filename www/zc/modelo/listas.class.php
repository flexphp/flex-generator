<?php

class listas {

    /**
     * Identificador unico del elemento dentro del formulario
     * @var string
     */
    public $_id;

    /**
     * Etiqueta que acompana la caja de texto, descripcion
     * @var string
     */
    public $_etiqueta;

    /**
     * Bandera para definir si es un camp obligatorio
     * @var string
     */
    public $_obligatorio = 'false';

    /**
     * Signo que identifica los campos obligatorios
     * @var string
     */
    public $_signoObligatorio = '';

    /**
     * Mensaje mostrado al cliente si el campo es obligatorio y no se ha diligenciado
     * @var string
     */
    public $_msjObligatorio = '';

    /**
     * HTML con el conjunto de opciones posibles para la lista
     * @var type
     */
    public $_opciones = '';

    /**
     * Campo tipo html a mostrar en pantalla
     * @var string
     */
    private $_lista;

    /**
     * Contrucutor de la caja de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        /**
         * Id del objeto dentro del formulario
         */
        if (!isset($caracteristicas[ZC_ID])) {
            throw new Exception(__FUNCTION__ . ': Es necesario el id para el objeto');
        } else {
            $this->_id = $caracteristicas[ZC_ID];
        }
        $this->_etiqueta = (!isset($caracteristicas[ZC_ETIQUETA])) ? $this->_id : $caracteristicas[ZC_ETIQUETA];
        if (isset($caracteristicas[ZC_OBLIGATORIO]) && $caracteristicas[ZC_OBLIGATORIO] == ZC_OBLIGATORIO_SI) {
            $this->_signoObligatorio = '*';
            $this->_obligatorio = 'true';
            $this->_msjObligatorio = (!isset($caracteristicas[ZC_OBLIGATORIO_ERROR])) ? ZC_OBLIGATORIO_ERROR_PREDETERMINADO : $caracteristicas[ZC_OBLIGATORIO_ERROR];
        }

        $this->opciones($caracteristicas[ZC_ELEMENTO_SELECT_OPCIONES]);
    }

    /**
     * Crear y define el elemento HTML a devolver
     * El estilo de creacion permite crear dos columnas para la recoleecion de datos
     * Cada una inicia con una columna en blanco (margen) izquierdo
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * 5 columnas repartidas con 2 para la etiqueta del elemento y 3 para la forma de ignreso
     * Cada una inicia con una columna en blanco (margen) derecho
     */
    function crearLista() {
        $this->_lista = "
            <div class='row'>
                <div class='col-md-1'></div>
                <div class='col-md-2 text-right'>
                    <label for='{$this->_id}'>{$this->_etiqueta}{$this->_signoObligatorio}</label>
                </div>
                <div class='col-md-3'>
                    <select" .
                " class='form-control'" .
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                " data-parsley-required='{$this->_obligatorio}'" .
                " data-parsley-required-message='{$this->_msjObligatorio}'" .
                " data-placement='right'" .
                " data-toggle='tooltip'" .
                " data-original-title='{$this->_etiqueta}'" .
                "/>
                    {$this->_opciones}
                    </select>
                    <span class='help-block'></span>
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
            $cada_opcion = explode(',', '=,' . $opciones);
            $opciones = array();
            foreach ($cada_opcion as $value) {
                if (strpos($value, '=') === false) {
                    throw new Exception(__FUNCTION__ . ': Tipo de lista no valido, se espera id1=valor1, id2=valor2');
                }
                list($id, $valor) = explode('=', $value);
                $opciones[trim($id)] = trim($valor);
            }
        }

        foreach ($opciones as $id => $valor) {
            $this->_opciones .= insertarEspacios(14) . "<option value='$id'>$valor</option>" . FIN_DE_LINEA;
        }
    }

    /**
     * Muestra la caja de texto en pantalla
     */
    function imprimirLista() {
        echo $this->_lista;
    }

    /**
     * Retorna el codigo HTML creado de la caja de texto
     * @return string
     */
    function devolverLista() {
        return $this->_lista;
    }

}
