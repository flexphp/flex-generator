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

    public function __construct($params) {
        // Asigna el super-objecto CodeIgniter
        $this->CI =& get_instance();
        $this->CI->load->model($this->modelo, 'modelo');
        $this->modelo = $params[0];
    }

    /**
     * Permite validar que el usuario tenga una session activa, de lo contrario termina la ejecucion
     * devolviendo un error de Acceso restringido (401)
     */
    function validarSesion() {
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
            $rptaWS = $ws['rptaWS'];
            if (isset($rptaWS[0]['error']) && $rptaWS[0]['error'] != '') {
                // Existe error durante el proceso
                $rpta['error'] = json_decode($rptaWS[0]['error'], true);
            } elseif (isset($rptaWS[0]['cta']) && $rptaWS[0]['cta'] > 0) {
                // Informacion devuelta
                $rpta['infoEncabezado'] = json_decode($rptaWS[0]['infoEncabezado'], true);
                // Cantidad de registros devueltos
                $rpta['cta'] = $rptaWS[0]['cta'];
                // Devuelve respuesta procesada
                return $rpta;
            } else {
                $rpta['error'] = 'No se encontraron datos.';
            }
        } else {
            $rpta['error'] = 'Error en servidor WS';
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
            $datos[$campo] = $valor;
            
            $rptaValidacion = $this->CI->modelo->validarDatos($datos);
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
        $error = $_CLI_WS->getError();
        if ($error) {
            $return['errorWS'] = $error;
        }
        // Define los datos de acceso al WS 
        $_CLI_WS->setCredentials($login, $clave, 'basic');
        // Llamado a la funcion  en el servidor
        $_rpta = $_CLI_WS->call(
            $metodoALlamar, // Funcion a llamar
            $parametros, // Parametros pasados a la funcion
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

    public function __destruct() {}

}