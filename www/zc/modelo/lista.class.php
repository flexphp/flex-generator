<?php

/**
 * Clase base para la creacion de elemento HTML
 */
require_once 'elemento.class.php';

/**
 * Crea elementos de lista, (select)
 */
class lista extends elemento {

    /**
     * HTML con el conjunto de opciones posibles para la lista
     * @var type
     */
    private $_opciones = '';

    /**
     * Propiedades de los join, de aqui se crean las acciones para cargar los campos
     * tipo lista (select) del formulario
     * @var array
     */
    private $_joinTablas = array();

    /**
     * Nombre del controlador donse esta alamacenado el servicio ajax
     * @var string
     */
    private $_controlador = '';

    /**
     * Ruta del archivo ajax creado, se usa para agregarlo a los archivos html
     * @var string
     */
    private $_ajax = null;

    /**
     * Contrucutor de listas (input select), define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @param array $controlador Valores seleccionados por el cliente
     */
    function __construct($caracteristicas, $controlador = '') {
        parent::__construct($caracteristicas);
        $this->_controlador = $controlador;
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
        $this->_html = "<select" .
                " class='form-control'" .
                // Identificador
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                // Validacion obligatorio
                " {$this->_obligatorio}" .
                " {$this->_msjObligatorio}" .
                // Ayuda visual
                $this->ayuda() .
                "/>
                    {$this->_opciones}
                    </select>
                    <span class='help-block'></span>";
        return $this;
    }

    /**
     * Devuelve el html con el conjunto de valores posible para el listado
     * @param type $opciones Valores que puede adoptar la lista de seleccion,
     * debe ser del tipo: id1 = valor1, id2 = valor2
     */
    private function opciones($opciones) {
        $opcion = array();
        if (is_string($opciones)) {
            if (strpos($opciones, ZC_MOTOR_SEPARADOR) === false && strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de lista no valido, se espera id1=valor1 o tabla::campo::tipoJoin');
            }
            // Agrega la opcion vacia a la lista de seleccion, aplica para el ajax como para el proceso no ajax
            $opcion[''] = '';
            $separador = (strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) ? ZC_MOTOR_SEPARADOR : ZC_MOTOR_JOIN_SEPARADOR;
            if ($separador != ZC_MOTOR_JOIN_SEPARADOR) {
                $cada_opcion = explode(',', $opciones);
                foreach ($cada_opcion as $value) {
                    if (strpos($value, $separador) === false) {
                        mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de lista no valido, se espera id1' . ZC_MOTOR_SEPARADOR . 'valor1');
                    }
                    list($id, $valor) = explode(ZC_MOTOR_SEPARADOR, $value);
                    $opcion[trim($id)] = trim($valor);
                }
            } else {
                // Campos llenados por base de datos, se valida que sea una opcion valida
                $this->opcionesAjax($opciones);
            }
        }

        foreach ($opcion as $id => $valor) {
            $this->_opciones .= insertarEspacios(14) . "<option value='$id'>$valor</option>" . FIN_DE_LINEA;
        }
        return $this;
    }

    private function opcionesAjax($join) {
        $this->_joinTablas = joinTablas($join);
        if ('' == $this->_controlador) {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'No se ha definido el controlador para el llamado ajax');
        }
        if (isset($this->_joinTablas)) {
            /**
             * Plantilla para el manejo de javascript
             */
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jsLlamadosListasAjax.js');

            $plantilla->asignarEtiqueta('nombreControlador', $this->_controlador);
            $plantilla->asignarEtiqueta('nombreTabla', $this->_joinTablas['tabla']);
            $plantilla->asignarEtiqueta('nombreCampos', $this->_joinTablas['campo']);
            $plantilla->asignarEtiqueta('nombreSelect', $this->_id);

            $plantilla->crearPlantilla('../publico/js', 'js', 'ajax_' . $this->_joinTablas['tabla']);
            // Agregar archivo creado al javascript al formulario
            $this->_ajax = $plantilla->_salidaPlantilla;
        }
        return $this;
    }

    /**
     * Devuelve la ruta del archivo ajax si existe
     * @return type
     */
    function devolverAjax() {
        return (isset($this->_ajax)) ? $this->_ajax : null;
    }

}
