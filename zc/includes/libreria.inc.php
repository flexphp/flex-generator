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
 * @param string $archivo Archivo de log a utilizar, sino se pasa se utiliza el archivo por defecto
 * @return boolean
 */
function miLog($log, $archivo = '') {
    if ($archivo == '') {
        $archivo = 'logs/Log_' . date('Ymd') . '.log';
    }
    $h = fopen($archivo, 'a+');
    if ($h) {
        fwrite($h, date("Ymd H:i:s") . ' -> ' . print_r($log, 1) . "\n");
        fclose($h);
    } else {
        exit('Sin log');
    }
    return true;
}

/**
 * Extrae el nombre sin la extension de la ruta|nombre dado
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
        mostrarErrorZC(__FILE__, __FUNCTION__, ": Y el nombre del archivo!?");
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
    return str_replace('../www/', '<?php echo base_url(); ?>', $ruta);
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
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && filter_var(\$dato['{$id}'],  FILTER_VALIDATE_INT) === false && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
            break;
        case ZC_DATO_EMAIL:
            $validacion = insertarEspacios(8) . "if (\$validarDato && isset(\$dato['{$id}']) && filter_var(\$dato['{$id}'], FILTER_VALIDATE_EMAIL) === false && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
            break;
        case ZC_DATO_FECHA:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && !preg_match('/^[0-9]{4}-(((0[13578]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$/', \$dato['{$id}']) && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
            break;
        case ZC_DATO_FECHA_HORA:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && !preg_match('/(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})/', \$dato['{$id}']) && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
            break;
        case ZC_DATO_HORA:
            $validacion = insertarEspacios(8) . "if (isset(\$dato['{$id}']) && !preg_match('/^(?:(?:([01]?\d|2[0-3]):)?([0-5]?\d):)?([0-5]?\d)$/', \$dato['{$id}']) && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
            break;
        case ZC_DATO_URL:
            $validacion = insertarEspacios(8) . "if (\$validarDato && isset(\$dato['{$id}']) && filter_var(\$dato['{$id}'], FILTER_VALIDATE_URL) === false && '' != \$dato['{$id}']) {" . FIN_DE_LINEA;
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
 * Crea validacion para los datos obligatorios, segun sea definido por el usuario en la configuracion, 
 * por defecto ningun campo es obligatorio
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
        case ZC_OBLIGATORIO_SI:
            $validacion .= FIN_DE_LINEA;
            $validacion .= insertarEspacios(8) . "if (\$validarDato && isset(\$dato['{$id}']) && '' == \$dato['{$id}']) {" . FIN_DE_LINEA;
            $validacion .= insertarEspacios(12) . "\$rpta['error'] .= '{$msjValidacion}';" . FIN_DE_LINEA;
            $validacion .= insertarEspacios(8) . "}" . FIN_DE_LINEA;
            break;
        case ZC_OBLIGATORIO_NO:
        default :
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
                $validacion .= insertarEspacios(8) . "if (\$validarDato && isset(\$dato['{$id}']) && strlen(\$dato['$id']) >  {$longitudMaxima} && '' != \$dato['$id']) {" . FIN_DE_LINEA;
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
                $validacion .= insertarEspacios(8) . "if (\$validarDato && isset(\$dato['{$id}']) && strlen(\$dato['$id']) <  {$longitudMinima} && '' != \$dato['$id']) {" . FIN_DE_LINEA;
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
 * @param string $origen Ruta de la carpeta a eliminar
 * @param string $eliminarOrigen Elimina la carpeta de la ruta en true, de lo contrario solo elimina el contenido
 */
function eliminar($origen, $eliminarOrigen = true) {
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
            eliminar("$origen/$entry");
        }
        $dir->close();
        return ($eliminarOrigen) ? rmdir($origen) : true;
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
function mostrarErrorZC($archivo, $funcion, $error) {
    $ruta = explode(DIRECTORY_SEPARATOR, $archivo);
    $nombre = end($ruta);
    miLog("{$nombre} => {$funcion}: $error");
    throw new Exception($error);
}

/**
 * Asigna los nombres de cada uno de los campos
 * @param string $id Identificador del campo
 * @param string $etiqueta Nombre descriptivo del campo
 * @param string $tabla Nombre de la tabla a la que corresponde el campo
 * @return string
 */
function aliasCampos($id, $etiqueta, $tabla = '') {
    if ($tabla != '') {
        // Solo lo agrega si la tabla es diferente de vacio
        $tabla .= '.';
    }
    // Se incluye la tabla en el id, esto para evitar sobreescibir campos con el mismo nombre
    return FIN_DE_LINEA . insertarEspacios(8) . "'{$tabla}{$id}' => '{$tabla}{$id} \'{$etiqueta}\'',";
}

/**
 * Extrae cada una de las tablas relacionadas con el formulario, se usa para contruir los join
 * @param string $id Identificador del campo donde se establece la relacion
 * @param string $tablas Tabla relacionada
 * @return string
 */
function tablasRelacionadas($id, $tablas, $join = '') {
    return FIN_DE_LINEA . insertarEspacios(8) . "'{$tablas}' => array('campo' => '{$id}', 'join' => '{$join}'),";
}

/**
 * Crear las uniones entre las tablas para los campos
 * @param string Parametros para la creacion del join, ejemplo: tabla1:campo1:right|left|<vacio>
 * @return string relacion a crear
 */
function joinTablas($join) {
    $separador = (strpos($join, ZC_MOTOR_JOIN_SEPARADOR) === false) ? null : ZC_MOTOR_JOIN_SEPARADOR;
    if (isset($separador)) {
        $parametros = explode(ZC_MOTOR_JOIN_SEPARADOR, strtolower($join));
        $joinTabla = (isset($parametros[0])) ? reemplazarCaracteresEspeciales($parametros[0]) : mostrarErrorZC(__FILE__, __FUNCTION__, ": Y el nombre la tabla a relacionar!?");
        $joinCampos = (isset($parametros[1])) ? $parametros[1] : mostrarErrorZC(__FILE__, __FUNCTION__, ": Y el los campos de relacion!?");
        $joinTipo = (isset($parametros[2])) ? validarJoinTipo($parametros[2]) : '';

        return array('tabla' => $joinTabla, 'campo' => $joinCampos, 'join' => $joinTipo);
    }
    return null;
}

/**
 * Crear las uniones entre las tablas para los campos
 * @param string Parametros para la creacion del join, ejemplo: right|left
 * @return string relacion a crear
 */
function validarJoinTipo($tipo) {
    if (!in_array($tipo, array(ZC_MOTOR_JOIN_IZQUIERDA, ZC_MOTOR_JOIN_DERECHA))) {
        mostrarErrorZC(__FILE__, __FUNCTION__, ': Tipo (' . $tipo . ') de relacion no valida');
    }
    return $tipo;
}

/**
 * Reemplaza caracteres no validas en cadenas
 * @param string $texto Cadena a convertir
 * @return string
 */
function reemplazarCaracteresEspeciales($texto) {
    // Buscar, EN UTF e ISO 8859-1
    $buscar = array(' ', '?', 'Ã¡', 'Ã©', 'Ã­', 'Ã³', ' Ãº', 'Ã±', 'Ã‘', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'Ñ');
    // Reemplazar
    $reemplazar = array('_', '', 'a', 'e', 'i', 'o', 'u', 'n', 'n', 'a', 'e', 'i', 'o', 'u', 'n', 'n');
    // Devuelve la cadena transformada
    return str_replace($buscar, $reemplazar, strtolower($texto));
}

/**
 * Tabular el contenido de los archivos a crear
 * @param string $texto Linea de Texto a tabular
 * @param int $espacios Cantidad de espacios antes de colocar el texto
 * @return string
 */
function tabular($texto, $espacios = 0) {
    return insertarEspacios($espacios) . $texto . FIN_DE_LINEA;
}

/**
 * Determina las extension valida de los archivos de configuracion. Extrae
 * la ultima parte del nombre y la devuelve, sino tiene extension devuelve vacio
 * Ejemplo:
 * archivo.de.prueba.xml
 * devuelve: xml
 * @param string $archivo Nombre del archivo
 * @return string
 */
function extensionArchivo($archivo) {
    $ext = explode('.', $archivo);
    return strtolower(end($ext));
}