<?php

/**
 * Clase para la creacion del script de la base de datos
 */
require RUTA_GENERADOR_CODIGO . '/modelo/bd.class.php';

// Clase utilizada para el procesamiento del archivo XML
require RUTA_GENERADOR_CODIGO . '/modelo/xml.class.php';

/**
 * Procesa los archivos XML para convertirlos en aplicaciones
 */
class hoja extends xml {

    /**
     * Almacena los enlaces a cada uno de los recursos
     * @var \navegacion
     */
    private $navegacion;

    /**
     * Almacena en un array cada uno de los formularios, desde aqui se contruyen las tablas
     * @var array
     */
    private $bd = array();

    /**
     * Almacena las variables de cada atributo
     * usada para crear el formulario
     * @var array
     */
    private $propiedad = array();

    /**
     * Agrega los botones de accion al formulario
     * @param array $formulario Datos del formulario
     */
    private function agregarAcciones($formulario) {
        if ($formulario != strtolower(ZC_LOGIN_PAGINA)) {
            $this->elementos[] = array(ZC_ID => 'ajax', ZC_ELEMENTO => ZC_ACCION_AJAX, ZC_ETIQUETA => 'Ajax');
            // Boton para crear un nuevo registro
            $this->elementos[] = array(ZC_ID => 'enviar', ZC_ELEMENTO => ZC_ACCION_AGREGAR, ZC_ETIQUETA => 'Agregar');
            // Necesario para crear el formulario de busqueda
            $this->elementos[] = array(ZC_ID => 'encontrar', ZC_ELEMENTO => ZC_ACCION_BUSCAR, ZC_ETIQUETA => 'Encontrar');
            // Permite actualizar el registro en la base de datos
            $this->elementos[] = array(ZC_ID => 'actualizar', ZC_ELEMENTO => ZC_ACCION_MODIFICAR, ZC_ETIQUETA => 'Actualizar');
            // Permite precargar la informacion del regitros a modificar, se usa en conjunto con actualizar
            $this->elementos[] = array(ZC_ID => 'precargar', ZC_ELEMENTO => ZC_ACCION_PRECARGAR, ZC_ETIQUETA => 'Precargar');
            // Permite eleiminar el registro (desactivarlo)
            $this->elementos[] = array(ZC_ID => 'eliminar', ZC_ELEMENTO => ZC_ACCION_BORRAR, ZC_ETIQUETA => 'Eliminar');
            // Boton para cancelar la accion actual
            $this->elementos[] = array(ZC_ID => 'cancelar', ZC_ELEMENTO => ZC_ACCION_CANCELAR, ZC_ETIQUETA => 'Cancelar');
            // Boton para limpiar el contenido del formulario
            $this->elementos[] = array(ZC_ID => 'limpiar', ZC_ELEMENTO => ZC_ACCION_RESTABLECER, ZC_ETIQUETA => 'Limpiar');
        } else {
            // Crear pagina login
            // Boton para hacer login
            $this->elementos[] = array(ZC_ID => 'login', ZC_ELEMENTO => ZC_ACCION_LOGIN, ZC_ETIQUETA => 'Ingresar');
        }
        return $this;
    }

    public function cargarArchivosXML($rutaArchivosXML) {
        if (!is_dir($rutaArchivosXML)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ' No es una carpeta valida: ' . $rutaArchivosXML);
        }
        $leerDirectorio = opendir($rutaArchivosXML);
        // Crear e menu de navegacion
        $this->navegacion = new navegacion();
        while ($cadaArchivo = readdir($leerDirectorio)) {
            if (extensionArchivo($cadaArchivo) == 'xml') {
                $rutaXML = $rutaArchivosXML . "/" . $cadaArchivo;
                $xml = simplexml_load_file($rutaXML);
                $this->estructuraArchivoXML($xml);
                // Agrega los botones a los formularios
                // Crea los campos dinamicamente
                $this->agregarAcciones($this->nombreHoja);
                /**
                 * Por lo menos se debio crear un elemento para tener una estructura valida
                 */
                $this->xml2form($rutaXML, $this->elementos);
                // Guarda los datos del formulario
                $this->bd[] = $this->elementos;
                // Libera la variable para que pueda ser siendo utilizada
                $this->elementos = array();
            }
        }
//        $opciones = array('minimizar' => true);
        $opciones = array();
        $this->navegacion->fin('../www/application/views', 'html', $opciones);
        $this->crearModeloDB();
    }

    /**
     * Construye el formulario con base en el archivo XML
     * @param type $elementos
     * @throws Exception
     */
    private function xml2form($archivoXML, $elementos) {
        if (!isset($elementos[0])) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ': Estructura XML no valida!: ' . $archivoXML);
        }

        foreach ($elementos as $key => $value) {
            if (!isset($value[ZC_ELEMENTO]) && $key == 0) {
                /**
                 * Atributos Formulario, crear una variable con el id del formulario
                 */
                $formulario = new formulario($value);
            } elseif (isset($value[ZC_ELEMENTO]) && $key > 0) {
                /**
                 * Otros atributos no definidos, esta funcion se encarga de validar el tipo
                 */
                $formulario->agregarElemento($value);
            } else {
                mostrarErrorZC(__FILE__, __FUNCTION__, ': No existe el elemento ' . preprint($value, 1));
            }

            if (!isset($elementos[($key + 1)]) && $key > 0) {
                $this->navegacion->crear($formulario->infoNavegacion());
                /**
                 * En el ultimo elemento, finaliza el formulario
                 */
                $formulario->finFormulario();
                unset($formulario);
            }
        }
    }

    /**
     * Crea el modelo de la base de datos en el motor
     * @param array $elementos
     */
    private function crearModeloDB() {
        if (count($this->bd) > 0) {
            if (!defined('ZC_MOTOR_MYSQL')) {
                mostrarErrorZC(__FILE__, __FUNCTION__, 'Falta el motor a utilizar!');
            }
            $motor = ZC_MOTOR_MYSQL;
            $bd = new bd($motor);
            $bd->db();
            foreach ($this->bd as $nro => $tabla) {
                $bd->tabla($tabla);
            }
            $bd->fin();
            $bd->crear();
            $bd->ejecutar();
        }
        return $this;
    }

}