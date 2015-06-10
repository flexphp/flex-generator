<?php 

/**
* Procesa los archivos con la sintaxis de ZeroCodigo
*/
class zcs {
    
    /**
     * Contenido original del archivo cargado por el usuario
     * @var string
     */ 
    private $_contenidoArchivo = '';

    /**
     * Cada una de las palabras conetenidos en el archivo cargado por el usuario
     * @var array
     */
    private $_todasPalabras = array();

    /**
     * Error generado dentro del proceso
     * @var string
     */
    private $_error = '';

    /**
     * Resultado de salida despues del proceso
     * @var string
     */
    private $_xml = '';

    /**
     * Inicializador de la clase
     */
    function __construct($rutaArchivo) {
        // Extrae el contenido del archivo
        $this->_contenidoArchivo = file_get_contents($rutaArchivo);
        $this->extraerPalabras();
        $this->validarPalabras();
        $this->procesarPalabras();
    }

    /**
     * Pasa el contenido del archivo a un array de palabras
     */
    private function extraerPalabras() {
        // Reemplaza los dobles espacios
        $this->_todasPalabras = explode(' ', preg_replace('/\s+/', ' ', str_replace(array(':', ',', ';'), '', $this->_contenidoArchivo)));
        return $this;
    }

    /**
     * Pasa el contenido del archivo a un array de palabras
     */
    private function validarPalabras() {
        // Une las palabras que estan entre comillas simples
        $palabrasTemporales = $this->_todasPalabras;
        foreach ($palabrasTemporales as $numero => $palabra) {
            // Inicia con comilla sencilla
            if (substr($palabra, 0, 1) == '\'') {
                $this->_todasPalabras[$numero] = $palabra;
                if (substr($palabra, -1) != '\'') {
                    // Recorre hasta encontrar la comilla de cierre
                    $numeroTemporal = $numero;
                    while (true) {
                        ++$numeroTemporal;
                        // Concatena las palabra a la anterior hasta encontrar la ultima comilla
                        $this->_todasPalabras[$numero] .= ' ' . $palabrasTemporales[$numeroTemporal];
                        unset($this->_todasPalabras[$numeroTemporal]);
                        if (substr($palabrasTemporales[$numeroTemporal], -1) == '\'') {
                            break;
                        }
                    }
                }
            }
        }
        // Ordena los indices
        $this->_todasPalabras = array_values($this->_todasPalabras);
        return $this;
    }

    /**
     * Procesa cada una de las palabras para construir el XML necesario
     */
    private function procesarPalabras() {
        foreach ($this->_todasPalabras as $palabra) {
            $palabra = strtolower($palabra);
            switch ($palabra) {
                case '':
                case 'campo':
                case 'formulario':
                    // Pasa a la siguiente palabra
                    continue;
                    break;
                case 'favor':
                    // Inicia el encabezado del XML
                    $this->_xml .= insertarEspacios(0) . '<?xml version="1.0" encoding="UTF-8"?>' . FIN_DE_LINEA;;
                    break;
                case 'crear':
                    // Inicia el cuerpo de XML
                    $this->_xml .= insertarEspacios(0) . '<crear>' . FIN_DE_LINEA;
                    $tipo = 'tabla';
                    break;
                case 'gracias':
                    if(isset($idCampo)) {
                        // Se termina de cerrar el ultimo campo
                        $this->_xml .= insertarEspacios(8) . '</' . $idCampo . '>' . FIN_DE_LINEA;
                    }
                    // Cierra la hoja
                    $this->_xml .= insertarEspacios(4) . '</' . $idHoja . '>' . FIN_DE_LINEA;
                    // Cierrar el cuerpo de XML
                    $this->_xml .= insertarEspacios(0) . '</crear>' . FIN_DE_LINEA;
                    // Crear el archivo XML
                    file_put_contents('xml/' . $idHoja . '.xml', $this->_xml);
                    // Reinicia las variables para el nuevo XML
                    $this->_xml = '';
                    $idHoja = $idCampo = null;
                    break;
                case 'agregar':
                case 'con':
                    // Agrega el campo
                    $tipo = 'campo';
                    if(isset($idCampo)) {
                        // Cierra el campo anterior, solo si existe
                        $this->_xml .= insertarEspacios(8) . '</' . $idCampo . '>' . FIN_DE_LINEA;
                    }
                    break;
                case ZC_DATO_NUMERICO:
                case ZC_DATO_EMAIL:
                case ZC_DATO_FECHA:
                case ZC_DATO_FECHA_HORA:
                case ZC_DATO_HORA:
                case ZC_DATO_CONTRASENA:
                case ZC_DATO_URL:
                case ZC_DATO_TEXTO:
                case ZC_DATO_AREA_TEXTO:
                    // Agregar tipo de dato
                    $this->_xml .= insertarEspacios(12) . '<' . ZC_DATO . '>' . $palabra . '</' . ZC_DATO . '>' . FIN_DE_LINEA;
                    break;
                case ZC_ELEMENTO_CAJA:
                case ZC_ELEMENTO_RADIO:
                case ZC_ELEMENTO_CHECKBOX:
                case ZC_ELEMENTO_SELECT:
                    // Agregar el tipo de elemento a crear
                    $this->_xml .= insertarEspacios(12) . '<' . ZC_ELEMENTO . '>' . $palabra . '</' . ZC_ELEMENTO . '>' . FIN_DE_LINEA;
                    break;
                case 'obligatorio':
                    // Campo obligatorio
                    $this->_xml .= insertarEspacios(12) . '<' . ZC_OBLIGATORIO . '>' . ZC_OBLIGATORIO_SI . '</' . ZC_OBLIGATORIO . '>' . FIN_DE_LINEA;
                    break;
                default:
                    if (is_numeric($palabra)) {
                        // Agrega la longitud maxima si esta definida
                        $this->_xml .= insertarEspacios(12) . '<' . ZC_LONGITUD_MAXIMA . '>' . $palabra . '</' . ZC_LONGITUD_MAXIMA . '>' . FIN_DE_LINEA;
                        break;
                    }
                    if(!isset($tipo)){
                        continue;
                    }
                    switch ($tipo) {
                        case 'tabla':
                            // Agrega la creacion del formulario
                            $idHoja = reemplazarCaracteresEspeciales($palabra);
                            $this->_xml .= insertarEspacios(4) . '<' . $idHoja . '>' . FIN_DE_LINEA;
                            $this->_xml .= insertarEspacios(8) . '<' . ZC_FORMULARIO_NOMBRE . '>' . ucwords($palabra) . '</' . ZC_FORMULARIO_NOMBRE . '>' . FIN_DE_LINEA;
                            break;
                        case 'campo':
                            // Agrega la creacion del campo
                            $idCampo = str_replace('\'', '', reemplazarCaracteresEspeciales($palabra));
                            $this->_xml .= insertarEspacios(8) . '<' . $idCampo . '>' . FIN_DE_LINEA;
                            $this->_xml .= insertarEspacios(12) . '<' . ZC_ETIQUETA . '>' . ucfirst(str_replace('\'', '', $palabra)) . '</' . ZC_ETIQUETA . '>' . FIN_DE_LINEA;
                            break;
                        default:
                            $this->_error = 'Palabra inesperada: ' . $palabra;
                            break;
                    }
                    // Despues de usarlo lo borra
                    $tipo = null;
                    break;
            }
        }
    }

    /**
     * Devuelve el error generado
     */
    public function devolverError() {
        return $this->_error;
    }
}