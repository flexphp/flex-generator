<?php

/**
 * Clase para la creacion del script de la base de datos
 */
require_once 'bd.class.php';

/**
 * Procesador de archivoXML
 *
 * Se encarga de crear los formularios web con base en los XML de configuracion
 *
 * @author root
 */

/**
 * Procesas archivos XML de configuracion la estructura es como la siguiente:
 * Ejemplo:
 * <?xml version="1.0" encoding="ISO-8859-1"?>
 * <crear>
 *     <pruebas>
 *         <tipoWS>Usuarios</tipoWS>
 *         <crearWS>1</crearWS>
 *         <metodo>POST</metodo>
 *         <numero1>
 *             <tipo>cajaTexto</tipo>
 *             <etiqueta>Numero 1</etiqueta>
 *             <dato>numero</dato>
 *             <obligatorio>true</obligatorio>
 *         </numero1>
 *         <numero2>
 *             <tipo>cajaTexto</tipo>
 *             <etiqueta>Numero 1</etiqueta>
 *             <dato>numero</dato>
 *             <obligatorio>true</obligatorio>
 *         </numero2>
 *         <resultado>
 *             <tipo>cajaTexto</tipo>
 *             <etiqueta>Numero 1</etiqueta>
 *             <dato>numero</dato>
 *         </resultado>
 *         <sumar>
 *             <tipo>Boton</tipo>
 *             <etiqueta>Sumar</etiqueta>
 *         </sumar>
 *     </pruebas>
 * </crear>
 */
class procesarXML {

    /**
     * Carpeta donde estan los archivos por defecto
     * @var string
     */
    private $rutaArchivos = 'xml';

    /**
     * Almacena en un array cada uno de los elementos del XML, esta es la variable
     * usada para crear el formulario
     * @var array
     */
    private $elementos = array();

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

    function __construct() {

    }

    /**
     * Recorre la estructura del archivo xml
     * @param SimpleXML $xml Archivo procesado por simplexml_load_file
     */
    private function estructuraArchivoXML($xml) {
        foreach ($xml as $padre => $hijos) {
            // $padre es el nombre de la hoja de calculo en el archivo
            $form[ZC_ID] = strtolower($padre);
            $this->elementos[0] = array();
            $this->atributosXPathXML($hijos, $form);
            $this->hijosXPathXML($hijos, $form);
            $this->elementos[0] = $form;
            if ($form[ZC_ID] != strtolower(ZC_LOGIN_PAGINA)) {
                // Agrega los botones a los formularios
                // Crea los campos dinamicamente
                $this->elementos[] = array(ZC_ID => 'ajax', ZC_ELEMENTO => ZC_ACCION_AJAX, ZC_ETIQUETA => 'ajax');
                // Permite precargar la informacion del regitros a modificar
                $this->elementos[] = array(ZC_ID => 'precargar', ZC_ELEMENTO => ZC_ACCION_PRECARGAR, ZC_ETIQUETA => 'Precargar');
                // Boton para crear un nuevo registro
                $this->elementos[] = array(ZC_ID => 'enviar', ZC_ELEMENTO => ZC_ACCION_AGREGAR, ZC_ETIQUETA => 'Agregar');
                // Necesario para crear el formulario de busqueda
                $this->elementos[] = array(ZC_ID => 'encontrar', ZC_ELEMENTO => ZC_ACCION_BUSCAR, ZC_ETIQUETA => 'Encontrar');
                // Permite actualizar el registro en la base de datos
                $this->elementos[] = array(ZC_ID => 'actualizar', ZC_ELEMENTO => ZC_ACCION_MODIFICAR, ZC_ETIQUETA => 'Actualizar');
                // Permite eleiminar el registro (desactivarlo)
                $this->elementos[] = array(ZC_ID => 'eliminar', ZC_ELEMENTO => ZC_ACCION_BORRAR, ZC_ETIQUETA => 'Eliminar');
                // Boton para cancelar la accion actual
                $this->elementos[] = array(ZC_ID => 'cancelar', ZC_ELEMENTO => ZC_ELEMENTO_CANCELAR, ZC_ETIQUETA => 'Cancelar');
                // Boton para limpiar el contenido del dormulario
                //$this->elementos[] = array(ZC_ID => 'limpiar', ZC_ELEMENTO => ZC_ELEMENTO_RESTABLECER, ZC_ETIQUETA => 'Limpiar');
            } else {
                // Crear pagina login
                // Boton para hacer login
                $this->elementos[] = array(ZC_ID => 'login', ZC_ELEMENTO => ZC_ACCION_LOGIN, ZC_ETIQUETA => 'Ingresar');
            }
        }
    }

    /**
     * Determina si el XPath tiene hijos, de ternerlos se vuelve una funcion
     * recursiva hasta hallarlos todos<br/>
     * Ejemplo:
     * <base>
     *      <padre>
     *          <hijo>
     *              <nieto>
     *              </nieto>
     *          </hijo>
     *      </padre>
     * </base>
     * @param xpath $hijos XPath
     * @param array $propiedades Conjunto de atributos del elemento
     */
    private function hijosXPathXML($hijos, &$propiedades) {
        foreach ($hijos as $propiedad => $valor) {
            $contadorHijos = 0;
            if (count($hijos->{$propiedad}[$contadorHijos]) == 0) {
                /**
                 * Solo agrega la propiedad sino fue definida en los atributos
                 */
                if (!isset($propiedades[strtolower((string) $propiedad)])) {
                    $propiedades = $this->agregarPropiedad($propiedades, $propiedad, $valor);
                }
            } else {
                /**
                 * Crea variable con el nombre del elemento
                 */
                $$propiedad = array(ZC_ID => $propiedad);
                $this->atributosXPathXML($valor, $$propiedad);
                $this->hijosXPathXML($hijos->{$propiedad}[$contadorHijos], $$propiedad);
                $this->elementos[] = $$propiedad;
                ++$contadorHijos;
            }
        }
    }

    /**
     * Crea un array con el conjunto de prioridades definidas en el XML, si una
     * propiedad esta definida mas de una vez para un mismo elemento tiene propiedad
     * la definida de inline
     * Llos valores son convertidos a (string) ya que son xPath
     * Ejemplo:
     * <atributo etiqueta="tienePrioridad">
     *  <etiqueta>noSeTieneEnCuentaYaExisteArriba</etiqueta>
     * </atributo>
     * @param array $propiedades Conjunto de atributos del elemento
     * @param string $atributo Nombre del atributo
     * @param string $valor Valor del atributo
     * @return type Nuevo conjunto de atributos (Se agrega el nuevo)
     */
    private function agregarPropiedad($propiedades, $atributo, $valor) {
        $propiedades[strtolower((string) $atributo)] = (string) $valor;
        return $propiedades;
    }

    /**
     * Determina si el elemento tiene atributos
     * Ejemplo
     * <base>
     *      <nombre atributo-1="soy" atributo-2="un" atributo-3="atributo">Valor</nombre>
     * </base>
     * @param type $xPath
     * @param array $propiedades Conjunto de atributos del elemento
     * @return boolean
     */
    private function atributosXPathXML($xPath, &$propiedades) {

        if (count($xPath) > 0) {
            foreach ($xPath->attributes() as $atributo => $valor) {
                $propiedades = $this->agregarPropiedad($propiedades, $atributo, $valor);
            }
        }
    }

    /**
     * Determina las extension valida de los archivos de configuracion. Extrae
     * la ultima parte del nombre y la devuelve, sino tiene extension devuelve vacio
     * Ejemplo:
     * archivo.de.prueba.xml
     * devuelve: xml
     * @param string $archivo
     * @return string
     */
    private function extensionArchivo($archivo) {
        $ext = explode('.', $archivo);
        return strtolower(end($ext));
    }

    /**
     * Proceso los archivos validos .xml dentro de la ruta entregada
     * @param string $rutaXML
     */
    public function cargarArchivosXML($rutaXML) {
        if (!is_dir($this->rutaArchivos)) {
            $this->rutaArchivos = $rutaXML;
            mostrarErrorZC(__FILE__, __FUNCTION__, ' No es una carpeta valida: ' . $rutaXML);
        }
        $leerDirectorio = opendir($this->rutaArchivos);
        // Crear e menu de navegacion
        $this->navegacion = new navegacion();
        while ($cadaArchivo = readdir($leerDirectorio)) {
//            if ($cadaArchivo != 'usuarios.xml') {
//                continue;
//            }
            if ($this->extensionArchivo($cadaArchivo) == 'xml') {
                $rutaXML = $this->rutaArchivos . "/" . $cadaArchivo;
                $xml = simplexml_load_file($rutaXML);
                $this->estructuraArchivoXML($xml);

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
            mostrarErrorZC(__FILE__, __FUNCTION__, ": Estructura no valida!: " . $archivoXML);
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
            if ('' == $this->bd[0][0][ZC_MOTOR]) {
                mostrarErrorZC(__FILE__, __FUNCTION__, 'Falta el motor a utilizar!');
            }
            $motor = $this->bd[0][0][ZC_MOTOR];
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
