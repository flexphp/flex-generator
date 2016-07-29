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
    // Valida que tenga el signo (/ y .)para hacer el explode
    // sino lo tiene asigna un array para el siguiente paso
    $info = (strpos($nombreArchivo, '/')) ? explode('/', $nombreArchivo) : array($nombreArchivo);
    $infoArchivo = (strpos(end($info), '.')) ? explode('.', end($info)) : $nombreArchivo;
    // Freddie 20140914 Para evitar errores con nombre que tiene varios puntos (ie: jquery.ui.js),
    // se toma todo el nombre, exceptuando la ultima parte, que se elimina y despues se vuelve a unir
    // Solo si tiene mas de un elemento lo quita
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
    $msjValidacion = (trim($msj) != '') ? $msj : ZC_DATO_ERROR_PREDETERMINADO;
    switch ($dato) {
        case ZC_DATO_NUMERICO:
            $validacion = tabular("'type' => 'digits',", 12);
            $validacion .= tabular("'type-message' => '{$msjValidacion}',", 12);
            break;
        case ZC_DATO_EMAIL:
            $validacion = tabular("'type' => 'email',", 12);
            $validacion .= tabular("'type-message' => '{$msjValidacion}',", 12);
            break;
        case ZC_DATO_URL:
            $validacion = tabular("'type' => 'url',", 12);
            $validacion .= tabular("'type-message' => '{$msjValidacion}',", 12);
            break;
        case ZC_DATO_FECHA:
        case ZC_DATO_FECHA_HORA:
        case ZC_DATO_HORA:
            $validacion = tabular("'pattern' => \$this->zc->formatos('{$dato}'),", 12);
            $validacion .= tabular("'pattern-message' => '{$msjValidacion}',", 12);
            break;
        case ZC_DATO_TEXTO:
        default:
            // No se valida el tipo
            break;
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
    $msjValidacion = (trim($msj) != '') ? $msj : ZC_OBLIGATORIO_ERROR_PREDETERMINADO;
    switch (trim(strtolower($obligatorio))) {
        case ZC_OBLIGATORIO_SI:
            $validacion = tabular("'required' => 'true',", 12);
            $validacion .= tabular("'required-message' => '{$msjValidacion}',", 12);
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
 * @param string $longitud Longitud maxima definida por el usuario para el campo
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoLongitudMaxima($id, $etiqueta, $tipo, $longitud = -1, $msj = '') {
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $msj : ZC_LONGITUD_MAXIMA_ERROR_PREDETERMINADO;
    if ($longitud > 0) {
        switch ($tipo) {
            case ZC_DATO_CONTRASENA:
                // Datos de contrasena no se les valida la longitud, por defecto es 40 (SHA1)
                break;
            case ZC_DATO_NUMERICO:
                // Datos numericos no se les valida la longitud, se les valida el valor maximo
                $validacion = tabular("'max' => $longitud,", 12);
                $validacion .= tabular("'max-message' => '{$msjValidacion}',", 12);
                break;
            default :
                $validacion = tabular("'maxlength' => $longitud,", 12);
                $validacion .= tabular("'maxlength-message' => '{$msjValidacion}',", 12);
                break;
        }
    }
    return $validacion;
}

/**
 * Crea validacion de longitud maxima del campo
 * @param string $id Identificador del elemento a validar
 * @param string $etiqueta Nombre mostrado al cliente en el formulario
 * @param string $tipo Tipo de dato a validar ver ZC_DATO
 * @param string $longitud Longitud minima definida por el usuario para el campo
 * @param string $msj Mensaje de error definido por el usuario en la plantilla
 * @return string
 */
function validarArgumentoLongitudMinima($id, $etiqueta, $tipo, $longitud = 0, $msj = '') {
    $validacion = '';
    $msjValidacion = (trim($msj) != '') ? $msj : ZC_LONGITUD_MINIMA_ERROR_PREDETERMINADO;
    if ($longitud > 0) {
        switch ($tipo) {
            case ZC_DATO_CONTRASENA:
                // Datos de contrasena no se les valida la longitud, por defecto es 40 (SHA1)
                break;
            case ZC_DATO_NUMERICO:
                $validacion = tabular("'min' => $longitud,", 12);
                $validacion .= tabular("'min-message' => '{$msjValidacion}',", 12);
                break;
            default :
                $validacion = tabular("'minlength' => $longitud,", 12);
                $validacion .= tabular("'minlength-message' => '{$msjValidacion}',", 12);
                break;
        }
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
        crearCarpeta($destino);
        return symlink(readlink($origen), $destino);
    }
    // Archivos
    if (is_file($origen)) {
        crearCarpeta($destino);
        return copy($origen, $destino);
    }
    // No es carpeta
    if (!is_dir($origen)) {
        return false;
    }
    // Verifica que el destino exista
    crearCarpeta($destino);

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
 * Asigna los nombres de cada uno de los campos, se maneja en minuscula para evitar errores en *unix
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
    $tabla = strtolower($tabla);
    // Se incluye la tabla en el id, esto para evitar sobreescibir campos con el mismo nombre
    return tabular("'{$tabla}{$id}' => '{$tabla}{$id} \'{$etiqueta}\'',", 8);
}

/**
 * Extrae cada una de las tablas relacionadas con el formulario, se usa para contruir los join
 * se maneja en minuscula para evitar errores en *unix
 * @param string $id Identificador del campo donde se establece la relacion
 * @param string $tablas Tabla relacionada
 * @return string
 */
function tablasRelacionadas($id, $tablas, $join = '') {
    $tablas = strtolower($tablas);
    return tabular("'{$tablas}' => array('campo' => '{$id}', 'join' => '{$join}'),", 8);
}

/**
 * Crear las uniones entre las tablas para los campos
 * @param string Parametros para la creacion del join, ejemplo: tabla1:campo1:right|left|<vacio>
 * @return string relacion a crear
 */
function joinTablas($join) {
    $separador = (strpos($join, ZC_MOTOR_JOIN_SEPARADOR) === false) ? null : ZC_MOTOR_JOIN_SEPARADOR;
    if (isset($separador)) {
        $parametros = explode(ZC_MOTOR_JOIN_SEPARADOR, $join);
        $joinTabla = (isset($parametros[0])) ? tagXML($parametros[0]) : mostrarErrorZC(__FILE__, __FUNCTION__, ": Y el nombre la tabla a relacionar!?");
        $joinCampos = (isset($parametros[1])) ? tagXML($parametros[1]) : mostrarErrorZC(__FILE__, __FUNCTION__, ": Y el los campos de relacion!?");
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
 * Reemplaza caracteres no validas en cadenas para tags XML
 * @param string $tag Cadena a validar sintaxis xorrexta para el XML
 * @return string tag XML valido
 */
function tagXML($tag) {
    // Buscar, EN UTF8 e ISO 8859-1
    // strtolower no deja en minuscula los caracteres especiales (letras con tildes, enies, etc)
    $buscar = array(' ', ':', '?', 'á', 'é', 'í', 'ó', 'ú', 'ñ', 'Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ');
    // Reemplazar
    $reemplazar = array('_', '', '', 'a', 'e', 'i', 'o', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'n');
    // Devuelve la cadena transformada, elimina cualquier caracter no alfanumerico
    return preg_replace('/\W/', '', str_replace($buscar, $reemplazar, utf8_decode(strtolower($tag))));
}

/**
 * Pasa el contenido a caracteres HTML para manejarlo en la aplicacion
 * Asi se evitan errores con la codificacion de la pagina
 * @param string $contenido Contenido a pasar a HTML
 * @return string
 */
function contenidoXML($contenido)
{
    return htmlspecialchars(htmlentities(utf8_decode($contenido)));
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
 * Determina las extension del archivov, sino tiene extension devuelve vacio
 * Ejemplo:
 * archivo.de.prueba.xml
 * devuelve: xml
 * @param string $archivo Nombre del archivo
 * @return string
 */
function extensionArchivo($archivo) {
    $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
    return $extension;
}

/**
 * Carga la clase para para ser usada por el proceso
 * @param string $archivo Nombre del archivo que hace la solicitud
 * @param string $funcion Nombre de la desde la cual se invoca
 * @param string $clase Nombre de la clase a verificar
 */
function cargarClase($archivo, $funcion, $clase) {
    if (!class_exists($clase)) {
        // Si la clase a un no se ha definido carga la definicion
        $rutaClase = RUTA_GENERADOR_CODIGO . '/modelo/' . $clase . '.class.php';
        if (file_exists($rutaClase)) {
            require $rutaClase;
        } else {
            mostrarErrorZC($archivo, $funcion, ': Clase no existe: ' . $clase . '->' . $rutaClase);
        }
    }
}

/**
 * Crea la carpeta si no existe la ruta
 * @param string $ruta Rutu de directorios a verificar
 */
function crearCarpeta($ruta) {
    // Verifica que la carpeta de destino exista
    $tmp = pathinfo($ruta, PATHINFO_DIRNAME);
    if (!is_dir($tmp)) {
        // Se crean las carpetas recursivamente
        return mkdir($tmp, 0770, true);
    }
    return true;
}

/**
 * Define la nomenclatura del nombre de los archivos tipo modelo usado por CodeIgniter
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreModelo($nombre) {
    return (ZC_PREFIJO_MODELO != '') ? ZC_PREFIJO_MODELO . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de los archivos tipo controlador usado por CodeIgniter
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreControlador($nombre) {
    return (ZC_PREFIJO_CONTROLADOR != '') ? ZC_PREFIJO_CONTROLADOR . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de los archivos tipo vista usado por CodeIgniter
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreVista($nombre) {
    return (ZC_PREFIJO_VISTA != '') ? ZC_PREFIJO_VISTA . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de los archivos tipo vista usado por CodeIgniter
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreLista($nombre) {
    return (ZC_PREFIJO_LISTA != '') ? ZC_PREFIJO_LISTA . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de los archivos tipo modelo usado por CodeIgniter
 * * del lado servidor
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreModeloServidor($nombre) {
    return (ZC_PREFIJO_MODELO_WS != '') ? ZC_PREFIJO_MODELO_WS . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de los archivos tipo controlador usado por CodeIgniter
 * del lado servidor
 * @param string $nombre Nombre del modelo
 * @return string
 */
function nombreControladorServidor($nombre) {
    return (ZC_PREFIJO_CONTROLADOR_WS != '') ? ZC_PREFIJO_CONTROLADOR_WS . strtolower($nombre) : ucfirst(strtolower($nombre));
}

/**
 * Define la nomenclatura del nombre de la funcion de validacion
 * @return string
 */
function nombreFuncionValidacionDatos() {
    return ZC_FUNCION_VALIDACION_DATOS;
}

/**
 * Devuelve llamado a la vista de navegacion
 * Se crea fuenra de la clase paginas porque se puede usar sin la clase, cer controlador inicio
 * @return string
 */
function devolverNavegacion() {
    $tpl = '';
    if (ZC_CREAR_NAVBAR == ZC_OBLIGATORIO_SI) {
        $tpl = tabular("\$this->_data['navegacion'] = \$this->load->view('" . nombreVista(ZC_NAVEGACION_PAGINA) . ".html', null, true);", 0);
    }
    return $tpl;
}