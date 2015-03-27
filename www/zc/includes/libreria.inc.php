<?php

/**
 * Formatea variable para mostrar el contenido de forma mas ordenada y leible
 * @param mixed $var Variable a mostrar
 * @param mixed $bool Defien si mostrar en pantalla o en archivo, por defecto en pantalla (false)
 */
function preprint($var, $bool = false) {

    $x = '<pre>';
    $x .= print_r($var, 1);
    $x .= '</pre>';

    if ($bool) {
        print $x;
    } else {
        echo $x;
    }
}

/**
 * Quita espacios recursivamente, tanto a string como a arrays
 * @param mixed $var Variable a limpiar los espacios de los extremos
 * @return mixed Variable sin espacios en los extremos
 */
function miTrim($var) {
    if (is_array($var)) {
        foreach ($var as $key => $value) {
            $var[$key] = trim($value);
        }
    } elseif (is_string($var)) {
        $var = trim($var);
    }
    return $var;
}

/**
 * Crea log
 * @param string $log Contenido que se quiere almacenar en el log
 * @return boolean
 */
function miLog($log) {
    $h = fopen('/var/www/dev.freddie.net/www/log/log_' . date('Ymd') . '.log', 'a+');
    if ($h) {
        fwrite($h, date("Ymd H:i:s") . ' -> ' . print_r($log, 1) . "\n");
        fclose($h);
    } else {
        exit('Sin log');
    }
    return true;
}

/**
 * Verifica que la sesion este activa
 * @return boolean
 */
function sesionActiva() {
    if (isset($_SESSION['ID_USUARIO_LOGUEADO']) && $_SESSION['ID_USUARIO_LOGUEADO'] > 0) {
        return true;
    }
    return false;
}

/**
 * Elimina las variables de sesion
 * @return boolean
 */
function sesionInactivar() {
    foreach ($_SESSION as $key => $value) {
        unset($_SESSION[$key]);
    }
    return true;
}

/**
 * Extra el nombre sin la extension de la ruta|nombre dado
 * @param string $nombreArchivo Ruta o nombre del archivo
 * @return string Unicamente nombre de archivo, sin extension
 */
function nombreArchivo($nombreArchivo) {
    /**
     * Valida que tenga el signo (/ y .)para hacer el explode
     * sino lo tiene asigna un array para el siguiente paso
     */
    $info = (strpos($nombreArchivo, '/')) ? explode('/', $nombreArchivo) : array($nombreArchivo);
    $infoArchivo = (strpos(end($info), '.')) ? explode('.', end($info)) : $nombreArchivo;
    /**
     * Freddie 20140914 Para evitar errores con nombre que tiene varios puntos (ie: jquery.ui.js),
     * se toma todo el nombre, exceptuando la ultima parte, que se elimina
     * y despues se vuelve a unir
     * Solo si tiene mas de un elemento lo quita
     */
    if (count($infoArchivo) > 1) {
        unset($infoArchivo[count($infoArchivo) - 1]);
    } else {
        $infoArchivo = array($infoArchivo);
    }
    return implode('.', $infoArchivo);
}

/**
 * Crear archivo en directorio fisico segun los argumentos dados
 * @param string $directorioArchivo Ruta donde se creara el archivo
 * @param string $nombreArchivo Nombre del archivo a crear, si es una ruta extrae el ultimo path
 * @param string $extensionArchivo Extension que tendra el archivo creado (php, html, etc)
 * @param string $contenidoArchivo Contenido del archivo a crear
 * @return string Nombre del archivo creado
 * @throws Exception
 */
function crearArchivo($directorioArchivo = '', $nombreArchivo = '', $extensionArchivo = '', $contenidoArchivo = '<vacio>') {
    if ($directorioArchivo == '') {
        mostrarErrorZC(__FILE__, __FUNCTION__, ": Y la el contenido del archivo!?");
    } else {

        $nombreArchivo = nombreArchivo($nombreArchivo) . '.' . $extensionArchivo;

        if (is_string($directorioArchivo) && $directorioArchivo != '' && !is_dir($directorioArchivo)) {
            mkdir($directorioArchivo, 0777, true);
        }

        $rutaArchivo = $directorioArchivo . '/';
        $salidaArchivo = $rutaArchivo . $nombreArchivo;

        eliminarArchivo($salidaArchivo);

        if (!file_put_contents($salidaArchivo, $contenidoArchivo)) {
            mostrarErrorZC(__FILE__, __FUNCTION__, ": No se pudo crear salida para : $salidaArchivo");
        }
    }
    return $nombreArchivo;
}

/**
 * Valida si el archivo existe en la ruta dada, si existe lo elimina
 * @param string $rutaArchivo Ruta del archivo
 */
function eliminarArchivo($rutaArchivo) {
    if (file_exists($rutaArchivo)) {
        unlink($rutaArchivo);
    }
    return true;
}

/**
 * Inserta espacios, esto para hacer las tabulaciones en los archivos creados
 * @param int $cantidad Numero de espacios a insertar
 * @return string
 */
function insertarEspacios($cantidad = 4) {
    return str_repeat(' ', $cantidad);
}

/**
 * Convierte la url dada a una URL relativa al proyecto, cambiando una ruta relativa en ruta absoluta
 * @param string $ruta Ruta de directorios donde esta el archivos
 * @return string
 */
function convertir2UrlLocal($ruta) {
    return str_replace('../', '<?php echo base_url(); ?>', $ruta);
}

/**
 * Crea validacion del tipo de dato dentro del proyecto
 * @param string $id Identificador del elemento a validar
 * @param string $etiqueta Nombre mostrado al cliente en el formulario
 * @param string $tipo Tipo de dato a validar ver ZC_DATO
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoTipoDato($id, $etiqueta, $elemento, $dato, $msj = '') {
    if ($elemento == ZC_ELEMENTO_CHECKBOX) {
        // Los elementos tipo checkbox pueden ser array, no se validan
        return '';
    }
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $etiqueta . ': ' . $msj : $etiqueta . ': ' . ZC_DATO_ERROR_PREDETERMINADO;
    switch ($dato) {
        case ZC_DATO_NUMERICO:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && filter_var(\$dato['{$id}'],  FILTER_VALIDATE_INT) === false && '' != \$dato['{$id}']){" . FIN_DE_LINEA;
            break;
        case ZC_DATO_EMAIL:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && \$validarDato && filter_var(\$dato['{$id}'], FILTER_VALIDATE_EMAIL) === false && '' != \$dato['{$id}']){" . FIN_DE_LINEA;
            break;
        case ZC_DATO_FECHA:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && !preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', \$dato['{$id}']) && '' != \$dato['{$id}']){" . FIN_DE_LINEA;
            break;
        case ZC_DATO_URL:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && \$validarDato && filter_var(\$dato['{$id}'], FILTER_VALIDATE_URL) === false && '' != \$dato['{$id}']){" . FIN_DE_LINEA;
            break;
        case ZC_DATO_TEXTO:
        default :
            break;
    }
    if ('' != $validacion) {
        // Determina se se debe agregar validacion
        $validacion = FIN_DE_LINEA . $validacion;
        $validacion .= insertarEspacios(12) . "\$rpta['error'] .= '{$msjValidacion}';" . FIN_DE_LINEA;
        $validacion .= insertarEspacios(8) . "}" . FIN_DE_LINEA;
    }
    return $validacion;
}

/**
 * Crea validacion para los datos obligatorios, segun sea definido por el usuario en la configuracion
 * @param string $id Identificador del elemento a validar
 * @param string $etiqueta Nombre mostrado al cliente en el formulario
 * @param string $obligatorio Bandera para identificar si el sato es obligatorio
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoObligatorio($id, $etiqueta, $obligatorio = 'no', $msj = '') {
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $etiqueta . ': ' . $msj : $etiqueta . ': ' . ZC_OBLIGATORIO_ERROR_PREDETERMINADO;

    switch (trim(strtolower($obligatorio))) {
        case ZC_OBLIGATORIO_NO:
            break;
        case ZC_OBLIGATORIO_SI:
        default :
            $validacion .= FIN_DE_LINEA;
            $validacion .= insertarEspacios(8) . "if (isset(\$dato['{$id}']) && \$validarDato && '' == \$dato['{$id}']){" . FIN_DE_LINEA;
            $validacion .= insertarEspacios(12) . "\$rpta['error'] .= '{$msjValidacion}';" . FIN_DE_LINEA;
            $validacion .= insertarEspacios(8) . "}" . FIN_DE_LINEA;
            break;
    }

    return $validacion;
}

/**
 * Crea validacion de longitud maxima del campo
 * @param string $id Identificador del elemento a validar
 * @param string $etiqueta Nombre mostrado al cliente en el formulario
 * @param string $tipo Tipo de dato a validar ver ZC_DATO
 * @param string $longitudMaxima Longitud maxima definida por el usuario para el campo
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoLongitudMaxima($id, $etiqueta, $tipo, $longitudMaxima = -1, $msj = '') {
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $etiqueta . ': ' . $msj : $etiqueta . ': ' . ZC_LONGITUD_MAXIMA_ERROR_PREDETERMINADO;

    switch ($tipo) {
        case ZC_DATO_NUMERICO:
        // Datos numericos no se les valida la longitud?
        case ZC_DATO_CONTRASENA:
            // Datos de contrasena no se les valida la longitud, por defecto es 40 (SHA1)
            break;
        default :
            if ($longitudMaxima > 0) {
                $validacion .= FIN_DE_LINEA;
                $validacion .= insertarEspacios(8) . "if (isset(\$dato['{$id}']) && \$validarDato && strlen(\$dato['$id']) >  {$longitudMaxima} && '' != \$dato['$id']){" . FIN_DE_LINEA;
                $validacion .= insertarEspacios(12) . "\$rpta['error'] .= '" . str_replace('&[Longitud]&', $longitudMaxima, $msjValidacion) . "';" . FIN_DE_LINEA;
                $validacion .= insertarEspacios(8) . "}" . FIN_DE_LINEA;
            }
            break;
    }

    return $validacion;
}

/**
 * Crea validacion de longitud maxima del campo
 * @param string $id Identificador del elemento a validar
 * @param string $etiqueta Nombre mostrado al cliente en el formulario
 * @param string $tipo Tipo de dato a validar ver ZC_DATO
 * @param string $longitudMinima Longitud minima definida por el usuario para el campo
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoLongitudMinima($id, $etiqueta, $tipo, $longitudMinima = 0, $msj = '') {
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $etiqueta . ': ' . $msj : $etiqueta . ': ' . ZC_LONGITUD_MINIMA_ERROR_PREDETERMINADO;

    switch ($tipo) {
        case ZC_DATO_NUMERICO:
            // Datos numericos no se les valida la longitud?
            break;
        default :
            if ($longitudMinima > 0) {
                $validacion .= FIN_DE_LINEA;
                $validacion .= insertarEspacios(8) . "if (isset(\$dato['{$id}']) && \$validarDato && strlen(\$dato['$id']) <  {$longitudMinima} && '' != \$dato['$id']){" . FIN_DE_LINEA;
                $validacion .= insertarEspacios(12) . "\$rpta['error'] .= '" . str_replace('&[Longitud]&', $longitudMinima, $msjValidacion) . "';" . FIN_DE_LINEA;
                $validacion .= insertarEspacios(8) . "}" . FIN_DE_LINEA;
            }
            break;
    }

    return $validacion;
}

/**
 * Copia los archivos masivamente, no colocar el ultimo slash en las rutas
 * @param string $origen Ruta origen de los archivos
 * @param string $destino Ruta destino de los archivos
 */
function copiar($origen, $destino) {
    // Enlace simbolicos
    if (is_link($origen)) {
        return symlink(readlink($origen), $destino);
    }
    // Archivos
    if (is_file($origen)) {
        return copy($origen, $destino);
    }
    // No es carpeta
    if (!is_dir($origen)) {
        return false;
    }
    //Destino no existe
    if (!is_dir($destino)) {
        mkdir($destino, 0770);
    }

    //Recorrer carpeta
    $dir = dir($origen);
    while (false !== $entry = $dir->read()) {
        // Saltar punteros
        if ($entry == '.' || $entry == '..') {
            continue;
        }
        copiar("$origen/$entry", "$destino/$entry");
    }
    $dir->close();
    return true;
}

/**
 * Elimina carpeta o archivos
 * @param type $origen
 */
function eliminar($origen) {
    // Enlace simbolicos
    if (is_link($origen)) {
        return unlink(readlink($origen));
    }
    // Archivos
    if (is_file($origen)) {
        return unlink($origen);
    }

    if (is_dir($origen)) {
        //Recorrer carpeta
        $dir = dir($origen);
        while (false !== $entry = $dir->read()) {
            // Saltar punteros
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            elminar("$origen/$entry");
        }
        $dir->close();
        return rmdir($origen);
    }
    return false;
}

/**
 * Mueve los archivos masivamente, no colocar el ultimo slash en las rutas
 * @param string $origen Ruta origen de los archivos
 * @param string $destino Ruta destino de los archivos
 */
function mover($origen, $destino) {
    if (copiar($origen, $destino)) {
        eliminar($origen, $destino);
    }
}

/**
 * Tiempo en milisegundos, utilizado para medir tiempo de ejecucion
 * @return type
 */
function tiempoMilisegundos() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float) $usec + (float) $sec);
}

/**
 * Muestra los errores encontrador durante el proceso de creacion de archivos
 * @param string $archivo Ruta de archivo donde se origino el error
 * @param string $funcion Nombre de la funcion donde se origino el error
 * @param string $error Descripcion del error encontrado
 * @throws Exception
 */
function mostrarErrorZC($archivo, $funcion, $error){
    $nombre = end(explode(DIRECTORY_SEPARATOR, $archivo));
    throw new Exception("<b>{$nombre}</b> => <i>{$funcion}:</i> " . FIN_DE_LINEA_HTML . "$error");
}