<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class zc {
    public function __construct() {

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

    public function __destruct() {

    }
}