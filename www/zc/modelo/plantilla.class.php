<?php

class plantilla {

    /**
     * Contenido cargado del archivo plantilla
     * @var string
     */
    private $_plantilla = null;

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
    public $_salidaPlantilla = '';

    /**
     * Constructor
     */
    function __construct() {

    }

    /**
     * Define una nueva plantilla para poder extraer los datos
     * @param type $nombre Nombre de la plantilla a manejar
     * @param type $contenido Contenido con las etiquetas a manejar
     * @return \plantilla
     * @throws Exception
     */
    public function nuevaPlantilla($nombre = '', $contenido = '') {
        if ($nombre == '') {
            throw new Exception(__FUNCTION__ . ": El nombre de la nueva plantilla!");
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
            throw new Exception(__FUNCTION__ . ": La plantilla " . $plantilla . " no existe");
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
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            $patron = '#\{_([A-z0-9\s\.])*_\}#';
            preg_match_all($patron, $this->_plantilla, $this->_etiquetas);
            if (count($this->_etiquetas[0]) > 0) {
                foreach ($this->_etiquetas[0] as $key => $value) {
                    $value = str_replace(array('{', '}', '_'), '', $value);
                    $this->_etiquetasValidas[$value] = 1;
                }
            }
        }
    }

    /**
     * Asigna el valor de etiqueta con el valor entregado
     * @param type $etiqueta Nombre de la etiqueta presente en la plantilla
     * @param type $valor Valor de la etiqueta
     * @return \plantilla
     * @throws Exception
     */
    public function asignarEtiqueta($etiqueta, $valor) {
        if (!$this->_plantilla) {
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            if (isset($this->_etiquetasValidas[$etiqueta])) {
//                No es necesario que la etiqueta este en la plantilla
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
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
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
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            $patron = '#\{_([A-z0-9\s\.])*_\}#';
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
    public function minimizarPlantilla() {
        if (!$this->_plantilla) {
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            //return $this;
            $patron = array(
//            Quitar comentarios html
                '#<!--(.)*-->#',
                // Quitar comentarios multiples php, js, css (ua sola linea /* comentario */) :(
                //'#/\*(.)*\*/#',
//            Reemplazar saltos de linea, tabs
                '#[\n][\r][\r\n][\t]#',
//            Reemplazar varios espacios por un solo espacio (HTML no los tiene encuenta)
                '#\s+#',
//            Quitar espacios entre etiquetas html
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
            //            Quitar comentarios html
//            $patron = '#<!--([A-z0-9\s\.\>\<])*-->#';
//            $reemplazar = '';
//            $this->_plantilla = preg_replace($patron, $reemplazar, $this->_plantilla);
////            Quitar comentarios html
//            $patron = '#(/\*([^*]|[\r\n]|(\*+([^*/]|[\r\n])))*\*+/)|((?<!:)//.*)|[\t\r\n]#i';
//            $reemplazar = '';
//            $this->_plantilla = preg_replace($patron, $reemplazar, $this->_plantilla);
//            Quitar comentarios /* */
//            $patron = "(\/\*(\s*|.*?)*\*\/)|(\/\/.*)";
//            $reemplazar = '';
//            $this->_plantilla = preg_replace($patron, $reemplazar, $this->_plantilla);
        }

        return $this;
    }

    /**
     * Imprime en pantalla la plantilla creada
     * @throws Exception
     */
    public function imprimirPlantilla() {
        if (!$this->_plantilla) {
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
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
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            return $this->_plantilla;
        }
    }

    /**
     * Crear archivo en disco, segun los parametros
     * @param type $dirSalida Ruta donde se creara el archivo
     * @param type $tipoSalida Extension que tendra el archivo
     * @param type $nombreSalida Nombre del archivo como tal
     * @throws Exception
     */
    public function crearPlantilla($dirSalida = '', $tipoSalida = 'php', $nombreSalida = '') {
        if (!$this->_plantilla) {
            throw new Exception(__FUNCTION__ . ": Y la plantilla!?");
        } else {
            if ($nombreSalida == '') {
                $info = explode('/', $this->_nombrePlantilla);
                $infoPlantilla = explode('.', end($info));
                $nombrePlantilla = implode('.', $infoPlantilla);
            } elseif (is_string($nombreSalida) && $nombreSalida != '') {
                $nombrePlantilla = $nombreSalida;
            } else {
                throw new Exception(__FUNCTION__ . ": Nombre de para la plantilla incorrecto!");
            }
            $this->_nombrePlantilla = crearArchivo($dirSalida, $nombrePlantilla, $tipoSalida, $this->_plantilla);
            $this->_rutaPlantilla = $dirSalida . '/';
            $this->_salidaPlantilla = $this->_rutaPlantilla . $this->_nombrePlantilla;
        }
    }

    public function abrirPlantilla($paramsGET = '') {
        header('Location: ' . $this->_salidaPlantilla . $paramsGET);
        die();
    }

}
