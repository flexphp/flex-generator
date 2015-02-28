    function procesarRespuestaWS($ws) {
        if (isset($ws['errorWS'])) {
            /**
            * Error durante consulta webservice
            */
            // $this->manejoError->crearError($ws['errorWS']);
            $rpta['error'] = $ws['errorWS'];
        } else {
            $rptaWS = $ws['rptaWS'];
            if ($rptaWS) {
                if ($rptaWS[0]['error1'] != '') {
                    // $this->manejoError->crearError(json_decode($rptaWS[0]['error1'], true));
                    $rpta['error'] = json_decode($rptaWS[0]['error1'], true);
                } elseif ($rptaWS[0]['cta'] > 0) {
                    // Informacion devuelta
                    $rpta['infoEncabezado'] = json_decode($rptaWS[0]['infoEncabezado'], true);
                    // Cantidad de registros devueltos
                    $rpta['cta'] = $rptaWS[0]['cta'];
                    /**
                    * Quita campos del array
                    */
                    return $rpta;
                } else {
                    // $this->manejoError->crearError('No existen datos.');
                    $rpta['error'] = 'No existen datos.';
                }
            } else {
                // $this->manejoError->crearError('Error en servidor WS');
                $rpta['error'] = 'Error en servidor WS';
            }
        }
        return $rpta;
    }