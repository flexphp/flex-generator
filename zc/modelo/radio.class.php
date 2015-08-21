<?php

/**
 * Crea elementos de lista, (select)
 */
class radio extends Aelemento {

    /**
     * Id del contanenedor de los radio, se utiliza para ir anadiendo cada una de las opciones
     */
    private $_contenedorRadio = '';

    /**
     * HTML con el conjunto de opciones "<option></option>" posibles para la lista
     * @var string
     */
    private $_opciones = '';

    /**
     * Contrucutor de listas (input select), define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @param array $controlador Valores seleccionados por el cliente
     */
    function __construct($caracteristicas, $controlador = '') {
        parent::__construct($caracteristicas);
        // Crear el nombre de controlador en minuscula
        $this->_controlador = strtolower($controlador);
        // Nombre del contenedor donde se crearan los campos
        $this->_contenedorRadio = 'radio_' . $this->_id;
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
        $this->_html .= tabular("<div id='{$this->_contenedorRadio}' class='text-center'>", 32);
        $this->_html .= $this->_opciones;
        $this->_html .= tabular("</div>", 0);
        $this->_html .= tabular("</div>", 28);
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
            if (strpos($opciones, ZC_ELEMENTO_OPCIONES_ASIGNADOR) === false && strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) {
                mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de radio [' . $this->_id . '] no valido, se espera id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1' . ZC_ELEMENTO_OPCIONES_SEPARADOR . 'id2' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor2 o tabla' . ZC_MOTOR_JOIN_SEPARADOR . 'campo' . ZC_MOTOR_JOIN_SEPARADOR . 'tipoJoin');
            }
            // Agrega la opcion vacia a la lista de seleccion, aplica para el ajax como para el proceso no ajax
            $separador = (strpos($opciones, ZC_MOTOR_JOIN_SEPARADOR) === false) ? ZC_ELEMENTO_OPCIONES_SEPARADOR : ZC_MOTOR_JOIN_SEPARADOR;
            if ($separador != ZC_MOTOR_JOIN_SEPARADOR) {
                $cada_opcion = explode($separador, $opciones);
                foreach ($cada_opcion as $value) {
                    if (strpos($value, ZC_ELEMENTO_OPCIONES_ASIGNADOR) === false) {
                        mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo de radio [' . $this->_id . '] no valido, se espera id1' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor1' . ZC_ELEMENTO_OPCIONES_SEPARADOR . 'id2 ' . ZC_ELEMENTO_OPCIONES_ASIGNADOR . 'valor2');
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
            $valor = html_entity_decode($valor);
            if (!isset($config)) {
                // Solo agrega la configuracion a un elemento dentro del grupo
                $config = // Autofoco
                        " {$this->_autofoco}" .
                        " {$this->_obligatorio}" .
                        " {$this->_msjObligatorio}";
            }
            $idOpcion = $this->_id . '_' . $id;
            $this->_opciones .= tabular('' .
                    "<label class='radio-inline' for='{$idOpcion}'>" .
                    "<input" .
                    " type='radio'" .
                    " class='radio'" .
                    // Permite extraer rapidamente la descripcion de la opcion, se usa en el buscador
                    " zc-texto='" . htmlentities($valor) . "'" .
                    // Identificador campo
                    " id='{$idOpcion}'" .
                    " name='{$this->_id}'" .
                    " value='{$id}'" .
                    $config .
                    // Ayuda visual
                    $this->ayuda($this->_etiqueta . ': ' . $valor) .
                    "/>" .
                    html_entity_decode($valor) .
                    "</label>", 36);
            // Solo aplica para un elemento
            $config = '';
        }
    }

    /**
     * Crear un archivo javascript para jacer solicitudes Ajax
     * @param string $join Valida las tablas relacionadas
     */
    private function opcionesAjax($join) {
        $this->_joinTablas = joinTablas($join);
        if ('' == $this->_controlador) {
            mostrarErrorZC(__FILE__, __FUNCTION__, 'No se ha definido el controlador para el llamado ajax');
        }
        if (isset($this->_joinTablas)) {
            $this->_joinTablas['tabla'] = strtolower($this->_joinTablas['tabla']);
            // Plantilla para el manejo de javascript
            $plantilla = new plantilla();
            $plantilla->cargarPlantilla(RUTA_GENERADOR_CODIGO . '/plantilla/js/jsLlamadosRadioAjax.js');
            $plantilla->asignarEtiqueta('nombreControlador', $this->_controlador);
            $plantilla->asignarEtiqueta('nombreTabla', $this->_joinTablas['tabla']);
            $plantilla->asignarEtiqueta('nombreCampos', $this->_joinTablas['campo']);
            $plantilla->asignarEtiqueta('nombreContenedor', $this->_contenedorRadio);
            $plantilla->asignarEtiqueta('nombreRadio', $this->_id);
            $plantilla->asignarEtiqueta('obligatorio', $this->_obligatorio);
            $plantilla->asignarEtiqueta('mensajeObligatorio', $this->_msjObligatorio);
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
