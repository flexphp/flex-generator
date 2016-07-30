<?php

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
    private $tablas = array();

    /**
     * Agrega los botones de accion al formulario
     * @param string $tipoFormulario Tipo de formulario a crear
     */
    private function agregarAcciones($tipoFormulario) {
        switch (strtolower($tipoFormulario)) {
            case ZC_FORMULARIO_TIPO_AUTENTICACION:
                // Crear pagina login, boton para hacer login
                $this->elementos[] = array(ZC_ID => 'loguear', ZC_ELEMENTO => ZC_ACCION_LOGUEAR, ZC_ETIQUETA => 'Ingresar');
                break;
            case ZC_FORMULARIO_TIPO_PERSONALIZADO:
                // Formulario personalizado, los botones deben ser agregados directamente en la hoja de calculo
                break;
            case ZC_FORMULARIO_TIPO_TABLA:
                // Creacion de las demas paginas con funciones CRUD
            default:
                // Boton para crear un nuevo registro
                $this->elementos[] = array(ZC_ID => 'enviar', ZC_ELEMENTO => ZC_ACCION_AGREGAR, ZC_ETIQUETA => 'Agregar');
                // Necesario para crear el formulario de busqueda
                $this->elementos[] = array(ZC_ID => 'encontrar', ZC_ELEMENTO => ZC_ACCION_BUSCAR, ZC_ETIQUETA => 'Encontrar');
                foreach ($this->elementos as $nro => $elemento) {
                    if (isset($elemento[ZC_ELEMENTO_OPCIONES]) && strpos($elemento[ZC_ELEMENTO_OPCIONES], ZC_MOTOR_JOIN_SEPARADOR)) {
                        // Para cargar listas desplegables
                        // Permite buscar la informacion de forma asincrona, se usa enconjunto con buscar
                        // Ajax solo se agrega si el formulario tiene join con otras tablas
                        $this->elementos[] = array(ZC_ID => 'ajax', ZC_ELEMENTO => ZC_ACCION_AJAX, ZC_ETIQUETA => 'Ajax');
                        break;
                    }
                }
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
                break;
        }
        // Carga las restricciones de los campos del formulario, esto es para TODOS los tipos de formularios
        $this->elementos[] = array(ZC_ID => 'init', ZC_ELEMENTO => ZC_ACCION_INIT, ZC_ETIQUETA => 'Inicializar');
        return $this;
    }

    public function cargarArchivosXML($rutaArchivosXML) {
        if (!is_dir($rutaArchivosXML)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ' No es una carpeta valida: ' . $rutaArchivosXML);
        }
        $leerDirectorio = opendir($rutaArchivosXML);
        // Crear el menu de navegacion
        $this->navegacion = new navegacion();
        while ($cadaArchivo = readdir($leerDirectorio)) {
            if (extensionArchivo($cadaArchivo) == 'xml') {
                $rutaXML = $rutaArchivosXML . "/" . $cadaArchivo;
                $this->estructuraArchivoXML($rutaXML);
                // Crea los campos dinamicamente
                // Determina si esta definido el tipo de formulaio a crear
                $this->elementos[0][ZC_FORMULARIO_TIPO] = (isset($this->elementos[0][ZC_FORMULARIO_TIPO])) ? $this->elementos[0][ZC_FORMULARIO_TIPO] : '';
                // Agrega los botones a los formularios segun el tipo de formulario
                $this->agregarAcciones($this->elementos[0][ZC_FORMULARIO_TIPO]);
                // Por lo menos se debio crear un elemento para tener una estructura valida
                $this->xml2form($rutaXML, $this->elementos);
                // Guarda los datos del formulario
                $this->tablas[] = $this->elementos;
                // Libera la variable para que pueda ser siendo utilizada
                $this->elementos = array();
            }
        }
        $opciones = array();
        $this->navegacion->fin('../www/application/views', 'html', $opciones);
    }

    /**
     * Construye el formulario con base en el archivo XML
     * @param type $elementos
     * @throws Exception
     */
    private function xml2form($archivoXML, $elementos) {
        if (!isset($elementos[0])) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ' Estructura XML no valida!: ' . $archivoXML);
        }

        foreach ($elementos as $key => $value) {
            if (!isset($value[ZC_ELEMENTO]) && $key == 0) {
                // Atributos Formulario, crear una variable con el id del formulario
                $formulario = new formulario($value);
            } elseif (isset($value[ZC_ELEMENTO]) && $key > 0) {
                // Otros atributos no definidos, esta funcion se encarga de validar el tipo de elemento a crear
                $formulario->agregarAtributo($value);
            } else {
                mostrarErrorZC(__FILE__, __FUNCTION__, ' No existe el elemento ' . preprint($value, 1));
            }

            if (!isset($elementos[($key + 1)]) && $key > 0) {
                $this->navegacion->crear($formulario->infoNavegacion());
                // En el ultimo elemento, finaliza el formulario
                $formulario->finFormulario();
                unset($formulario);
            }
        }
    }

    public function devolverTablas() {
        return $this->tablas;
    }

}
