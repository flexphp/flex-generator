<?php

/**
 * Crea elementos de lista, (select)
 */
class lista extends Aelemento {

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
        // Crear el nombre de controlador en minuscula
        $this->_controlador = strtolower($controlador);
        $this->opciones($this->_prop[ZC_ELEMENTO_OPCIONES]);
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
        $this->_html = tabular("<select" .
                " class='form-control'" .
                // Identificador
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                "/>", 0) .
                $this->_opciones .
                tabular("</select>", 28);
        return $this;
    }

    /**
     * La opcion por defecto tienen el placeholder y por css se  oculta
     * @return string
     */
    function placeholder() {
        return "<option value='' selected='selected'>" . ((isset($this->_prop[ZC_PLACEHOLDER])) ? $this->_prop[ZC_PLACEHOLDER] : '') . "</option>";
    }

    /**
     * Devuelve el html con el conjunto de valores posible para el listado
     * @param type $opciones Valores que puede adoptar la lista de seleccion,
     * debe ser del tipo: id1 = valor1, id2 = valor2
     */
    private function opciones($opciones) {
        $opcion = array();
        if (is_string($opciones)) {
            if (strpos($opciones, ZC_ELEMENTO_OPCIONES_SEPARADOR) === false && strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ' Tipo de lista [' . $this->_id . '] no valido, se espera id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1' . ZC_MOTOR_JOIN_SEPARADOR . 'id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1 o tabla' . ZC_MOTOR_JOIN_SEPARADOR . 'campo' . ZC_MOTOR_JOIN_SEPARADOR . 'tipoJoin');
            }
            // Agrega la opcion vacia a la lista de seleccion, aplica para el ajax como para el proceso no ajax
            $this->_opciones .= tabular($this->placeholder(), 32);
            $separador = (strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) ? ZC_ELEMENTO_OPCIONES_SEPARADOR : ZC_MOTOR_JOIN_SEPARADOR;
            if ($separador != ZC_MOTOR_JOIN_SEPARADOR) {
                $cada_opcion = explode(ZC_ELEMENTO_OPCIONES_SEPARADOR, $opciones);
                foreach ($cada_opcion as $value) {
                    if (strpos($value, ZC_ELEMENTO_OPCIONES_ASIGNADOR) === false) {
                        mostrarErrorZC(__FILE__, __FUNCTION__, ' Tipo de lista [' . $this->_id . '] no valido, se espera id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1');
                    }
                    list($id, $valor) = explode(ZC_ELEMENTO_OPCIONES_ASIGNADOR, $value);
                    $opcion[trim($id)] = trim($valor);
                }
            } else {
                // Campos llenados por base de datos, se valida que sea una opcion valida
                $this->opcionesAjax($opciones);
            }
        }

        foreach ($opcion as $id => $valor) {
            $this->_opciones .= tabular("<option value='$id'>$valor</option>", 32);
        }
        return $this;
    }

    /**
     * Crear un archivo javascript para jacer solicitudes Ajax
     * @param string $join Valida las tablas relacionadas
     */
    private function opcionesAjax($join) {
        $this->_joinTablas = joinTablas($join);
        if ('' == $this->_controlador) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ' No se ha definido el controlador para el llamado ajax');
        }
        if (isset($this->_joinTablas)) {
            $this->_joinTablas['tabla'] = strtolower($this->_joinTablas['tabla']);
            // Plantilla para el manejo de javascript
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jsLlamadosListasAjax.js');
            $plantilla->asignarEtiqueta('nombreControlador', $this->_controlador);
            $plantilla->asignarEtiqueta('nombreTabla', $this->_joinTablas['tabla']);
            $plantilla->asignarEtiqueta('nombreCampos', $this->_joinTablas['campo']);
            $plantilla->asignarEtiqueta('nombreSelect', $this->_id);
            $plantilla->crearPlantilla('../www/publico/js', 'js', 'ajax_' . $this->_joinTablas['tabla']);
            // Agregar archivo creado al javascript al formulario
            $this->_ajax = $plantilla->devolver();
        }
        return $this;
    }

    /**
     * Devuelve la ruta del archivo ajax creado, solo si existe
     * @return string
     */
    public function devolverAjax() {
        return (isset($this->_ajax)) ? $this->_ajax : null;
    }

}
