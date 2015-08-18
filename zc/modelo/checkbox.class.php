<?php

/**
 * Crea elementos de lista, (select)
 */
class checkbox extends Aelemento {

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
        $this->opciones($this->_prop[ZC_ELEMENTO_OPCIONES]);
        $this->autofoco($this->_prop[ZC_AUTOFOCO]);
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
        $this->_html = tabular("<div class='table table-bordered'>", 0);
        $this->_html .= tabular("<div class='text-center checkbox'>", 32);
        $this->_html .= $this->_opciones;
        $this->_html .= tabular("</div>", 32);
        $this->_html .= tabular("</div>", 28);
        return $this;
    }

    /**
     * Devuelve el html con el conjunto de valores posible para el listado
     * @param type $opciones Valores que puede adoptar la lista de seleccion,
     * debe ser del tipo: id1 = valor1, id2 = valor2
     */
    private function opciones($opciones) {
        if (is_string($opciones)) {
            // Se agrega la opcion vacia al inicio del listado
            $cada_opcion = explode(ZC_ELEMENTO_OPCIONES_SEPARADOR, $opciones);
            $opciones = array();
            foreach ($cada_opcion as $value) {
                if (strpos($value, ZC_ELEMENTO_OPCIONES_ASIGNADOR) === false) {
                    mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de checkbox [' . $this->_id . '] no valido, se espera id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1' . ZC_ELEMENTO_OPCIONES_SEPARADOR . 'id2 ' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor2');
                }
                list($id, $valor) = explode(ZC_ELEMENTO_OPCIONES_ASIGNADOR, $value);
                $opciones[trim($id)] = trim($valor);
            }
        }

        foreach ($opciones as $id => $valor) {
            if (!isset($config)) {
                // Solo agrega la configuracion a un elemento dentro del grupo
                $config = // Autofoco
                        " {$this->_autofoco}" .
                        " {$this->_obligatorio}" .
                        " {$this->_msjObligatorio}";
            }
            $idOpcion = $this->_id . '_' . $id;
            $this->_opciones .= tabular('' .
                    "<label class='checkbox-inline' for='{$idOpcion}'>" .
                    "<input" .
                    " type='checkbox'" .
                    " class='checkbox'" .
                    // Permite extraer rapidamente la descripcion de la opcion, se usa en el buscador
                    " zc-texto='" . htmlentities($valor) . "'" .
                    // Idenfitificador
                    // El nombre se maneja como arreglo para permitir enviar varios valores
                    " id='{$idOpcion}'" .
                    " name='{$this->_id}[]'" .
                    " value='{$id}'" .
                    $config .
                    // Ayuda visual
                    $this->ayuda() .
                    "/>" .
                    html_entity_decode($valor) .
                    "</label>", 36);
            // Solo aplica para un elemento
            $config = '';
        }
    }

}
