<?php

class plantilla {

    /**
     * Contenido cargado del archivo plantilla
     * @var string
     */
    private $_plantilla = null;
    
    /**
     * Opciones qu se deben aplicar a la plantilla, minimizar, abrir
     * @var array
     */
    private $_opciones = array('minimizar' => false, 'abrir' => false);

    /**
     * Ruta de la plantilla en disco
     * @var string
     */
    private $_nombrePlantilla = null;

    /**
     * Etiquetas validas extraidas de la plantilla
     * @var array
     */
    private $_etiquetas = array();

    /**
     * Etiquetas validas extraidas de la plantilla
     * @var array
     */
    private $_etiquetasValidas = array();

    /**
     * Ruta de exportacion de la plantilla, solo el directorio de salida
     * @var string
     */
    private $_rutaPlantilla = '';

    /**
     * Ruta completa donde se exporta la plantilla, directorio y nombre
     * @var string
     */
    private $_salidaPlantilla = '';

    /**
     * Constructor
     */
    function __construct($opciones = array()) {
        $this->_opciones = (is_array($opciones)) ? $opciones : $this->_opciones;
    }

    /**
     * Define una nueva plantilla para poder extraer los datos
     * @param string $nombre Nombre de la plantilla a manejar
     * @param string $contenido Contenido con las etiquetas a manejar
     * @return \plantilla
     * @throws Exception
     */
    public function nuevaPlantilla($nombre = '', $contenido = '') {
        if ($nombre == '') {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": El nombre de la nueva plantilla!");
        } else {
            $this->_nombrePlantilla = $nombre;
            $this->_plantilla = $contenido;
            if ($this->_plantilla == '') {
                $this->_plantilla = '{_vacia_}';
            }
            $this->extraerEtiquetas();
        }
        return $this;
    }

    /**
     * Carga un archivo de plantilla desde el disco duro
     * @param string $plantilla Ruta del archivo donde esta ubicada la plantilla
     * @return \plantilla
     * @throws Exception
     */
    public function cargarPlantilla($plantilla = null) {
        if (!is_file($plantilla)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": La plantilla " . $plantilla . " no existe");
        } else {
            $this->_nombrePlantilla = $plantilla;
            $this->_plantilla = file_get_contents($plantilla);
            if ($this->_plantilla == '') {
                $this->_plantilla = '{_vacia_}';
            }
            $this->extraerEtiquetas();
        }
        return $this;
    }

    /**
     * Extrae todas etiquetas que estan definidas en la plantilla
     * @throws Exception
     */
    private function extraerEtiquetas() {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            $patron = '#\{_([A-z0-9\s\._])*_\}#';
            preg_match_all($patron, $this->_plantilla, $this->_etiquetas);
            if (count($this->_etiquetas[0]) > 0) {
                foreach ($this->_etiquetas[0] as $key => $value) {
                    $value = str_replace(array('{_', '_}'), '', $value);
                    $this->_etiquetasValidas[$value] = 1;
                }
            }
        }
    }

    /**
     * Asigna el valor de etiqueta con el valor entregado
     * @param string $etiqueta Nombre de la etiqueta presente en la plantilla
     * @param string $valor Valor de la etiqueta
     * @return \plantilla
     * @throws Exception
     */
    public function asignarEtiqueta($etiqueta, $valor) {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            if (isset($this->_etiquetasValidas[$etiqueta])) {
                // No es necesario que la etiqueta este en la plantilla
                $this->_plantilla = str_replace('{_' . $etiqueta . '_}', $valor, $this->_plantilla);
            }
        }

        return $this;
    }

    /**
     * Adigna etiquetas de manera multiple, se espera un arreglodel tipo
     * $array('etiqueta' => 'valor')
     * @param array $etiquetas Arreglo del tipo $array('etiqueta' => 'valor')
     * @return \plantilla
     * @throws Exception
     */
    public function asignarEtiquetaMultiple(array $etiquetas) {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            foreach ($etiquetas as $key => $value) {
                $this->asignarEtiqueta($key, $value);
            }
        }

        return $this;
    }

    /**
     * Elimina los tag {__} de la plantilla
     * @return \plantilla
     * @throws Exception
     */
    public function quitarEtiquetas() {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            $patron = '#\{_([A-z0-9\s\._])*_\}#';
            $reemplazar = '';
            $this->_plantilla = preg_replace($patron, $reemplazar, $this->_plantilla);
        }

        return $this;
    }

    /**
     * Hace reemplazos de caracteres para poder minimizar el tamanio del archivo
     * @return \plantilla
     * @throws Exception
     */
    private function minimizarPlantilla() {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else if (isset($this->_opciones['minimizar']) && $this->_opciones['minimizar'] === true) {
            $patron = array(
                // Quitar comentarios html
                '#<!--(.)*-->#',
                // Quitar comentarios multiples php, js, css (ua sola linea /* comentario */) :(
                //'#/\*(.)*\*/#',
                // Reemplazar saltos de linea, tabs
                '#[\n][\r][\r\n][\t]#',
                // Reemplazar varios espacios por un solo espacio (HTML no los tiene encuenta)
                '#\s+#',
                // Quitar espacios entre etiquetas html
                '#>(\s*)<#',
            );
            $reemplazar = array(
                '',
                //'',
                '',
                ' ',
                '><',
            );
            $this->_plantilla = preg_replace($patron, $reemplazar, $this->_plantilla);
        }

        return $this;
    }

    /**
     * Imprime en pantalla la plantilla creada
     * @throws Exception
     */
    public function imprimirPlantilla() {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            echo $this->_plantilla;
        }
    }

    /**
     * Retorna la plantilla creada
     * @return string
     * @throws Exception
     */
    public function devolverPlantilla() {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            // Elimina etiquetas no reemplazadas
            $this->quitarEtiquetas();
            // Verifica si debe minimizar la plantilla
            $this->minimizarPlantilla();
            return $this->_plantilla;
        }
    }

    /**
     * Crear archivo en disco, segun los parametros
     * @param string $dirSalida Ruta donde se creara el archivo
     * @param string $tipoSalida Extension que tendra el archivo
     * @param string $nombreSalida Nombre del archivo como tal
     * @throws Exception
     */
    public function crearPlantilla($dirSalida = '', $tipoSalida = 'php', $nombreSalida = '') {
        if (!$this->_plantilla) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la plantilla!?");
        } else {
            if ($nombreSalida == '') {
                $info = explode('/', $this->_nombrePlantilla);
                $infoPlantilla = explode('.', end($info));
                $nombrePlantilla = implode('.', $infoPlantilla);
            } elseif (is_string($nombreSalida) && $nombreSalida != '') {
                $nombrePlantilla = $nombreSalida;
            } else {
                mostrarErrorZC(__FILE__, __FUNCTION__, ": Nombre de para la plantilla incorrecto!");
            }
            $this->_nombrePlantilla = crearArchivo($dirSalida, $nombrePlantilla, $tipoSalida, $this->devolverPlantilla());
            $this->_rutaPlantilla = $dirSalida . '/';
            $this->_salidaPlantilla = $this->_rutaPlantilla . $this->_nombrePlantilla;
            // Verifica si debe abrir la plantilla
            $this->abrirPlantilla();
        }
    }

    /**
     * Abre la plantilla en el navegador, depende de las opciones pasadas a la plantilla
     * @param string $paramsGET Para parametros GET pasados a la plantilla
     */
    private function abrirPlantilla($paramsGET = '') {
        if (isset($this->_opciones['abrir']) && $this->_opciones['abrir'] === true) {
            header('Location: ' . $this->devolver() . $paramsGET);
            die();
        }
    }

    /**
     * Devuelve la ruta donde se creara la pantilla
     */
    public function devolver() {
        return $this->_salidaPlantilla;
    }

}
