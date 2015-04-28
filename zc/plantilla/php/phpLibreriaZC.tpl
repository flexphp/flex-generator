<?php

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
        header('WWW-Authenticate: Basic realm="Autenticacion"');
        header('HTTP/1.1 401 Unauthorized');
        die('401: Acceso restringido');
    }

    public function __destruct() {

    }
}