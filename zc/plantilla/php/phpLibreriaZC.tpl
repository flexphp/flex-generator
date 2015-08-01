<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Zc {

    /**
     * Instancia CodeIgniter
     * @var object
     */
    protected $CI;

    /**
     * Modelo a usar para cargar el modelo
     * @var string
     */
    protected $modelo;

    /**
     * Error encontrados durante la validacion de datos
     * @var array
     */
    protected $_error = array();

    /**
     * Descripcion tipo de datos utilizado
     * @var string
     */
    const NUMERICO = '{_datoNumerico_}';
    const EMAIL = '{_datoEmail_}';
    const FECHA = '{_datoFecha_}';
    const FECHA_HORA = '{_datoFechaHora_}';
    const HORA = '{_datoHora_}';
    const CONTRASENA = '{_datoContrasena_}';
    const URL = '{_datoUrl_}';
    const TEXTO = '{_datoTexto_}';
    const AREA_TEXTO = '{_datoAreaTexto_}';
    const OBLIGATORIO = '{_obligatorio_}';

    function __construct($params) {
        // Asigna el super-objecto CodeIgniter
        $this->modelo = $params[0];
        $this->CI =& get_instance();
        $this->CI->load->model($this->modelo, 'modelo');
    }

    /**
     * Permite validar que el usuario tenga una session activa, de lo contrario termina la ejecucion
     * devolviendo un error de Acceso restringido (401)
     */
    function validarAutenticacion() {
        $rpta = array();
        if (!isset($_SERVER['PHP_AUTH_USER']) && !isset($_SERVER['PHP_AUTH_PW'])) {
            $this->autenticacion();
        } else {
            $rpta = array (
                'accion' => 'login',
                'login' => $_SERVER['PHP_AUTH_USER'],
                'clave' => $_SERVER['PHP_AUTH_PW']
            );
        }
        return $rpta;
    }

    /**
     * Muestra el mensaje de dialogo para capturar nombre de usuario y contrasena
     */
    function autenticacion() {
        header('WWW-Authenticate: Basic realm="Por favor inicie sesion"');
        header('HTTP/1.1 401 Unauthorized');
        die('401: Acceso restringido');
    }

    /**
     * Valida si el llamado se hace desde un webservice
     */
    function esWebService() {
        return (strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'nusoap') !== false) ? true : false;
    }

    /**
     * Procesa la respuesta devuelta por el servidor WS, verifica si existen errores
     * @param array Respuesta del servidor de WS
     * @return array
     */
    function procesarRespuestaWS($ws) {
        $rpta = array();
        if (isset($ws['errorWS'])) {
            // Error durante consulta webservice
            $rpta['error'] = $ws['errorWS'];
        } elseif (isset($ws['rptaWS'])) {
            // Respuesta del WS
            $rptaWS = $ws['rptaWS'][0];
            $error = (isset($rptaWS['error']) && $rptaWS['error'] != '') ? json_decode($rptaWS['error'], true) : null;
            $infoEncabezado = (isset($rptaWS['infoEncabezado']) && $rptaWS['infoEncabezado'] != '') ? json_decode($rptaWS['infoEncabezado'], true) : null;
            $cta = (isset($rptaWS['cta']) && $rptaWS['cta'] > 0 && $infoEncabezado) ? $rptaWS['cta'] : 0;
            if ($error) {
                // Existe error durante el proceso
                $rpta['error'] = $error;
            } elseif ($cta > 0) {
                // Informacion devuelta
                $rpta['infoEncabezado'] = $infoEncabezado;
                // Cantidad de registros devueltos
                $rpta['cta'] = $cta;
                // Devuelve respuesta procesada
                return $rpta;
            } else {
                $rpta['error'] = 'No se encontraron datos.';
            }
        } else {
            $rpta['error'] = 'Error en servidor.';
        }
        return $rpta;
    }

    /**
     * Valida los filtros aplicados en el formulario de busqueda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $accion Accion solicitado por parte del cliente
     * @return array Respuesta de la validacion de datos
     */
    function validarFiltros($campos, $accion){
        // Errores durante la validacion
        $rpta['error'] = array();
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        // Determina si se deben validar los filtros
        $filtros = (is_string($campos))? explode('|??|', $campos) : $campos;

        foreach ($filtros as $llave => $cadaFiltro) {
            if($cadaFiltro == ''){
                //Sin filtros de busqueda
                continue;
            }
            // Valores por defecto de los campos
            $tabla = '';
            $campo = $llave;
            $operador = '=';
            $valor = $cadaFiltro;

            if (strpos($cadaFiltro, '|?|') !== false) {
                $info = explode('|?|', $cadaFiltro);
                $cta = count($info);
                if ($cta == 4) {
                    //Datos de la forma: tabla|?|campo|?|operador|?|valor
                    list($tabla, $campo, $operador, $valor) = $info;
                } elseif ($cta == 3) {
                    //Datos de la forma: campo|?|operador|?|valor
                    list($campo, $operador, $valor) = $info;
                } else {
                    // Termina ejecucion, opcion no comtemplada
                    $rpta['error'] = 'Filtros invalidos';
                    break;
                }
            }
            // Limpia los espacios para evitar en las validaciones posteriores
            // Al valor no se le aplica ya que puede tener espacios y estos se deben tener encuenta
            $tabla = trim($tabla);
            $campo = trim($campo);
            $operador = trim($operador);
            // Para la validaciones es necesario que se un array, de lo contrario daria error
            $datos[$campo] = $valor;

            $rptaValidacion = $this->CI->modelo->{_funcionValidacionDatos_}($datos);
            if (isset($rptaValidacion['error']) && count($rptaValidacion['error']) > 0) {
                foreach ($rptaValidacion['error'] as $id => $error) {
                    $rpta['error'][$id] = $error;
                }
            }
            // Concatena la tabla, si existe
            $campo = ($tabla != '') ? $tabla . '.' . $campo : $campo;
            // Agrega condiciones de busqueda segun los filtros
            if(strpos($operador, '%')){
                $this->CI->db->like($campo, $valor, str_replace('%', '', $operador));
            }else{
                $this->CI->db->where(array($campo.' '.$operador => $valor));
            }
        }
        return $rpta;
    }

    /**
     * Devuelve el numero total de registros que cumplen con los filtros aplicados 
     * en el formulario de busqueda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $tabla Nombre de la tabla
     * @return int Numero de registros encontrados
     */
    function totalRegistros($campos, $tabla){
        $this->CI->db->select('COUNT(1) cta');
        // Agrega los filtros de busqueda
        $this->validarFiltros($campos, 'buscar');
        $ressql = $this->CI->db->get($tabla);
        return ($ressql) ? $ressql->row()->cta : 0;
    }

    /**
     * Hace los llamados a los WS, se centraliza en este punto, esta es la funcion utilizada
     * por los controladores
     * @param string $login Usuario a usar para loguearse en el sistema
     * @param string $clave Clave a usar para loguearse en el sistema
     * @param string $serverURL URL donde esta ubicado el WS => http://localhost
     * @param string $serverScript Recurso donde esta la logica a utilizar => Clientes.php
     * @param string $metodoALlamar Metodo a llamar dentro del recurso => crearCliente
     * @param string $parametros Cada uno de los datos recibidos por el usuario
     * @return array
     */
    function llamarWS($login, $clave, $serverURL, $serverScript, $metodoALlamar, $parametros) {
        $tiempo_inicio = microtime(true);
        // Nueva instancia de NuSOAP
        $_CLI_WS = new nusoap_client($serverURL . $serverScript . '?wsdl', 'wsdl');
        // Manejo de caracteres especiales
        $_CLI_WS->soap_defencoding = 'UTF-8';
        $_CLI_WS->decode_utf8 = false;
        $error = $_CLI_WS->getError();
        if ($error) {
            $return['errorWS'] = $error;
        }
        // Define los datos de acceso al WS
        $_CLI_WS->setCredentials($login, $clave, 'basic');
        // Llamado a la funcion  en el servidor
        $_rpta = $_CLI_WS->call(
            $metodoALlamar, // Funcion a llamar
            $this->CI->security->xss_clean($parametros), // Parametros pasados a la funcion, aplica XSS
            "uri:{$serverURL}{$serverScript}", // namespace
            "uri:{$serverURL}{$serverScript}/$metodoALlamar" // SOAPAction
        );
        // Verificacion que los parametros estan bien, y si lo estan devolver respuesta.
        if ($_CLI_WS->fault) {
            $return['errorWS'] = $_rpta['faultstring'];
        } else {
            $error = $_CLI_WS->getError();
            if ($error) {
                $return['errorWS'] = $error;
            } else {
                $return['rptaWS'] = $_rpta;
            }
        }
        $tiempo_fin = microtime(true);
        $return['timeWS'] = ($tiempo_fin - $tiempo_inicio);
        $return['metodoWS'] = $metodoALlamar;
        return $this->procesarRespuestaWS($return);
    }

    /**
     * Validacion de campos obligatorios
     * @param string $id Identificador unico del campo
     * @param string $valor Valor del campo a validar
     * @param string $obligatorio Determina si es obligatio o no, valores si|no
     * @param string $textoError Error a devolver en caso de error en validacion
     * @return string
     */
    function obligatorio($id, $valor, $obligatorio = 'no', $textoError = '') {
        if ($obligatorio == self::OBLIGATORIO && '' == $valor) {
            $this->setearError($id, $textoError);
        }
        // Sin Error
        return $this->obtenerError($id);
    }

    /**
     * Validacion de longitud maxima para el campo
     * @param string $id Identificador unico del campo
     * @param string $valor Valor del campo a validar
     * @param string $tipo Determina el tipo de dato para saber si se debe validar o no
     * @param string $longitud Longitud maxima permitida para el campo, valores por encima generan error
     * @param string $textoError Error a devolver en caso de error en validacion
     * @return string
     */
    function longitudMaxima($id, $valor, $tipo, $longitud = 0, $textoError = '') {
        if ('' == $valor) {
            return $this->obtenerError($id);
        }
        switch ($tipo) {
            case self::NUMERICO:
                // Datos numericos no se les valida la longitud
            case self::CONTRASENA:
                // Datos de contrasena no se les valida la longitud, por defecto es 40 (SHA1)
                break;
            default :
                if ($longitud > 0 && strlen($valor) > $longitud) {
                    $this->setearError($id, str_replace('&[Longitud]&', $longitud, $textoError));
                }
            break;
        }
        return $this->obtenerError($id);
    }

    /**
     * Validacion de longitud minima para el campo
     * @param string $id Identificador unico del campo
     * @param string $valor Valor del campo a validar
     * @param string $tipo Determina el tipo de dato para saber si se debe validar o no
     * @param string $longitud Longitud minims permitida para el campo, valores por debajo generan error
     * @param string $textoError Error a devolver en caso de error en validacion
     * @return string
     */
    function longitudMinima($id, $valor, $tipo, $longitud = 0, $textoError = '') {
        if ('' == $valor) {
            return $this->obtenerError($id);
        }
        switch ($tipo) {
            case self::NUMERICO:
                // Datos numericos no se les valida la longitud
            case self::CONTRASENA:
                // Datos de contrasena no se les valida la longitud, por defecto es 40 (SHA1)
                break;
            default :
                if ($longitud > 0 && strlen($valor) < $longitud) {
                    $this->setearError($id, str_replace('&[Longitud]&', $longitud, $textoError));
                }
            break;
        }
        return $this->obtenerError($id);
    }

    /**
     * Validacion del tipo de dato para el campo
     * @param string $id Identificador unico del campo
     * @param string $valor Valor del campo a validar
     * @param string $tipo Determina el tipo de dato para saber si se debe validar o no
     * @param string $textoError Error a devolver en caso de error en validacion
     * @return string
     */
    function tipoDato($id, $valor, $tipo = 'texto', $textoError = '') {
        if('' == $valor) {
            // Campo vacio no se valida
            return $this->obtenerError($id);
        }
        // Determina si el tipo de dato tiene error
        $error = false;
        // Determina la validacion a hacer con base en el tipo de dato
        switch ($tipo) {
            case self::NUMERICO:
                if (filter_var($valor,  FILTER_VALIDATE_INT) === false) {
                    $error = true;
                }
                break;
            case self::EMAIL:
                if (filter_var($valor, FILTER_VALIDATE_EMAIL) === false) {
                    $error = true;
                }
                break;
            case self::URL:
                if (filter_var($valor, FILTER_VALIDATE_URL) === false) {
                    $error = true;
                }
                break;
            case self::FECHA:
                // Formato YYYY-MM-DD
                if (!preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', $valor)) {
                    $error = true;
                }
                break;
            case self::FECHA_HORA:
                // Formato YYYY-MM-DD HH:MM:SS
                if (!preg_match('/(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})/', $valor)) {
                    $error = true;
                }
                break;
            case self::HORA:
                // Formato HH:MM:SS
                if (!preg_match('/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', $valor)) {
                    $error = true;
                }
                break;
            default:
                $error = false;
                break;
        }
        if($error) {
            // Marca error si la validacion no fue exitosa
            $this->setearError($id, $textoError);
        }
        return $this->obtenerError($id);
    }

    /**
     * Establece el error para el campo, ya que un campo puede generar varios errore, solo se tiene encuenta
     * el primer error encontrado
     * @param string $id Nombre del campo con error
     * @param string $textoError Descripcion del error encontrado
     * @return void
     */
    function setearError($id, $textoError = '') {
        if (!isset($this->_error[$id])) {
            // Si no existe error para el campo lo configura
            $this->_error[$id] = $textoError;
        }
    }

    /**
     * Devuelve el error asociado al campo
     * @param string $id Nombre del campo con error
     * @return string
     */
    function obtenerError($id) {
        if (isset($this->_error[$id])) {
            // Si  existe el error lo devuele
            return $this->_error[$id];
        }
        return null;
    }

    /**
     * Devuelve la cantidad errores hasta el momento
     * @return int
     */
    function cantidadErrores() {
        return count($this->_error);
    }

    /**
     * Devuelve todo los errores encontrados durante la validacion en forma de arreglo
     * @return array
     */
    function devolverErrores() {
        return $this->_error;
    }

    public function __destruct() {}

}