<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class zc {

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
        $this->modelo = $params[0];
    }

    /**
     * Procesa la respuesta devuelta por el servidor WS, verifica si existen errores
     * @param array Respuesta del servidor de WS
     * @return array
     */ 
    function procesarRespuestaWS($ws) {
        if (isset($ws['errorWS'])) {
            /**
            * Error durante consulta webservice
            */
            $rpta['error'] = $ws['errorWS'];
        } else {
            $rptaWS = $ws['rptaWS'];
            if ($rptaWS) {
                if ($rptaWS[0]['error'] != '') {
                    $rpta['error'] = json_decode($rptaWS[0]['error'], true);
                } elseif ($rptaWS[0]['cta'] > 0) {
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
        }
        return $rpta;
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
     * Valida los filtros aplicados en el formulario de busqueda
     * @param string $campos Filtros de busqueda seleccionados por el cliente
     * @param string $accion Accion solicitado por parte del cliente
     * @return array Respuesta de la validacion de datos
     */
    function validarFiltros($campos, $accion){
        // Errores durante la validacion
        $rpta['error'] = '';
        // Accion que se esta ejecutando
        $datos['accion'] = $accion;
        // Determina si se deben validar los filtros
        $filtros = (is_string($campos))? explode('|??|', $campos) : $campos;

        foreach ($filtros as $llave => $cadaFiltro) {
            if($cadaFiltro == ''){
                //Sin filtros de busqueda
                continue;
            }
            if (strpos($cadaFiltro, '|?|') !== false) {
                list($tabla, $campo, $operador, $valor) = explode('|?|', $cadaFiltro);
            } else {
                $tabla = '';
                $operador = '=';
                $campo = $llave;
                $valor = $cadaFiltro;
            }
            $datos[$campo] = $valor;
            $rptaValidacion = call_user_func(array(&$this->CI->{$this->modelo}, 'validarDatos'), $datos);
            if (isset($rptaValidacion['error']) && '' != $rptaValidacion['error']) {
                $rpta['error'] .=  $rptaValidacion['error'];
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

    public function __destruct() {
    }
}