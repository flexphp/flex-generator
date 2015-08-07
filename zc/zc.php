<?php

// Se utiliza para calcular el tiempo de ejecucion
$time = microtime(true);
// Archivo de configuracion
require 'conf/conf.php';
// Configuracion inicial y demas recursos utilizados
require RUTA_GENERADOR_CODIGO . '/includes/base.inc.php';
// Respuesta devuelta durante el proceso
$rpta = array();
// Ruta del archivo cargado
$rutaArchivoDefinitiva = '';

if (isset($_FILES['file'])) {
    // Ruta temporal del archivo
    $rutaArchivoTemporal = $_FILES['file']['tmp_name'];
    $rutaArchivoDefinitiva = RUTA_GENERADOR_CODIGO . '/archivos/' . $_FILES['file']['name'];
    // Mueve el archivo a la ruta definitiva
    if (!move_uploaded_file($rutaArchivoTemporal, $rutaArchivoDefinitiva)) {
        $rpta['error'] = 'Error moviendo archivo: $rutaArchivoDefinitiva';
    }
    // Extension del archivo, con este se determina que proceso aplicar
    $tipoArchivo = extensionArchivo($_FILES['file']['name']);
} else {
    // No es un acceso valido al recurso
    header('Location: index.html');
}

try {
    switch ($tipoArchivo) {
        case 'ods':
            // Formatos abiertos de hojas de calculo
        case 'xls':
            // Excel 97-2003
        case 'xlsx':
            // Excel >= 2007
            // Librerias para la lectura de las hojas de calculo
            require RUTA_GENERADOR_CODIGO . '/includes/SpreadsheetReader/php-excel-reader/excel_reader2.php';
            require RUTA_GENERADOR_CODIGO . '/includes/SpreadsheetReader/SpreadsheetReader.php';

            // Inicializar libro desde la hoja
            $hojas = new SpreadsheetReader($rutaArchivoDefinitiva);
            // Extraer cada una de las hojas del libro
            $cadaHoja = $hojas->Sheets();
            // Para las hoja de calculo excel 97 el numero de la fila no inicia en la posicion 0, se deja en la posicion - 1
            $restar = ('xls' == strtolower($tipoArchivo)) ? 1 : 0;
            // Recorre cada una de las hojas
            foreach ($cadaHoja as $numeroHoja => $nombreHoja) {
                $nombreHoja = strtolower($nombreHoja);
                if (strtolower(ZC_ZC_PAGINA) == $nombreHoja) {
                    // La hoja donde estan las listas desplegables no la tiene encuenta
                    continue;
                } elseif (strtolower(ZC_CONFIG_PAGINA) == $nombreHoja) {
                    // Hoja de parametrizacion general de la aplicacion
                    foreach ($hojas as $numeroFila => $columnas) {
                        $numeroFila -= $restar; 
                        foreach ($columnas as $numeroColumna => $contenido) {
                            if ($contenido == '') {
                                // No tiene contenido, sigue con la siguiente columna
                                continue;
                            }
                            switch (true) {
                                case $numeroFila%2 == 0:
                                    // Los encabezados son las filas pares (0,2,4,6,...,n)
                                    // Encabezados
                                    $tag[$numeroColumna] = strtolower(reemplazarCaracteresEspeciales($contenido));
                                    break;
                                default:
                                    // Valores de los encabezados
                                    $config[$tag[$numeroColumna]] = $contenido;
                                    break;
                            }
                        }
                    }
                    // Configuracion del conexion a base de datos, 
                    define('ZC_BD_MOTOR', (isset($config[ZC_CONFIG_BD_MOTOR]) ? strtolower($config[ZC_CONFIG_BD_MOTOR]) : null));
                    define('ZC_BD_SERVIDOR', (isset($config[ZC_CONFIG_BD_SERVIDOR]) ? $config[ZC_CONFIG_BD_SERVIDOR] : null));
                    define('ZC_BD_PUERTO', (isset($config[ZC_CONFIG_BD_PUERTO]) ? $config[ZC_CONFIG_BD_PUERTO] : null));
                    define('ZC_BD_ESQUEMA', (isset($config[ZC_CONFIG_BD_ESQUEMA]) ? $config[ZC_CONFIG_BD_ESQUEMA] : null));
                    define('ZC_BD_USUARIO', (isset($config[ZC_CONFIG_BD_USUARIO]) ? $config[ZC_CONFIG_BD_USUARIO] : null));
                    define('ZC_BD_CLAVE', (isset($config[ZC_CONFIG_BD_CLAVE]) ? $config[ZC_CONFIG_BD_CLAVE] : null));
                    define('ZC_BD_CHARSET', (isset($config[ZC_CONFIG_BD_CHARSET]) ? $config[ZC_CONFIG_BD_CHARSET] : 'utf8'));
                    define('ZC_BD_COLLATION', (isset($config[ZC_CONFIG_BD_COLLATION]) ? $config[ZC_CONFIG_BD_COLLATION] : 'utf8_general_ci'));
                    // Numero de registros por pagina, para la funcion buscar
                    define('ZC_REGISTROS_POR_PAGINA', (isset($config[ZC_CONFIG_REGISTROS_POR_PAGINA]) ? $config[ZC_CONFIG_REGISTROS_POR_PAGINA] : 10));
                    define('ZC_CREAR_NAVBAR', (isset($config[ZC_CONFIG_INCLUIR_NAVBAR]) ? $config[ZC_CONFIG_INCLUIR_NAVBAR] : ''));
                    define('ZC_CREAR_LOGIN', (isset($config[ZC_CONFIG_INCLUIR_LOGIN]) ? $config[ZC_CONFIG_INCLUIR_LOGIN] : ''));
                    define('ZC_CREAR_PREGRESO', (isset($config[ZC_CONFIG_INCLUIR_PROGRESO]) ? $config[ZC_CONFIG_INCLUIR_PROGRESO] : ''));
                    // Crea los archivos y estructura de directorios base
                    plantillas();
                    // Crea los archivos de configuracion segun los valores dados
                    config();
                } else {
                    // Identificador de la hoja, se usa como nombre del XML generado
                    $idHoja = strtolower(reemplazarCaracteresEspeciales($nombreHoja));
                    // Establece la hoja que se va a procesar
                    $hojas->ChangeSheet($numeroHoja);

                    // Crear un archivo XML por cada hoja
                    $xml = tabular('<?xml version="1.0" encoding="UTF-8"?>', 0);
                    $xml .= tabular('<crear>', 0);
                    $xml .= tabular('<' . $idHoja . '>', 4);

                    // Cada fila
                    foreach ($hojas as $numeroFila => $columnas) {
                        $numeroFila -= $restar; 
                        if ($numeroFila > 2 && $columnas[0] !== '') {
                            // Comienza a procesar cada uno de los campos que estan configurados 
                            // en la hoja de calculo
                            $idCampo = strtolower(reemplazarCaracteresEspeciales($columnas[1]));
                            $xml .= tabular('<' . $idCampo . '>', 8);
                        }
                        foreach ($columnas as $numeroColumna => $contenido) {
                            if ($contenido == '') {
                                // No tiene contenido, pasa a la siguiente columna
                                continue;
                            }
                            // Inicia Informacion de seguimiento, se puede eliminar
                            // $info = $numeroFila . ':' . $numeroColumna . ' = ' . $contenido . "\n";
                            // miLog($info);
                            // Fin Informacion de seguimiento
                            switch ($numeroFila) {
                                case 0:
                                    // Encabezados con caracteristicas del formulario
                                case 2:
                                    // Descripciones caracteristicas campos del formulario
                                    $tag[$numeroColumna] = strtolower(reemplazarCaracteresEspeciales($contenido));
                                    break;
                                case 1:
                                    // Detalles del encabezado
                                    // Se reemplazan caracteres especiales para no afectar el XML
                                    $xml .= tabular('<' . $tag[$numeroColumna] . '>' . htmlspecialchars(htmlentities($contenido)) . '</' . $tag[$numeroColumna] . '>', 8);
                                    break;
                                default:
                                    // Detalles de los campos
                                    $xml .= tabular('<' . $tag[$numeroColumna] . '>' . htmlspecialchars(htmlentities($contenido)) . '</' . $tag[$numeroColumna] . '>', 12);
                                    break;
                            }
                        }
                        if ($numeroFila > 2 && $columnas[0] !== '') {
                            $xml .= tabular('</' . $idCampo . '>', 8);
                        }
                    }
                    $xml .= tabular('</' . $idHoja . '>', 4);
                    $xml .= tabular('</crear>', 0);
                    // Crear el archivo XML por cada hoja
                    crearArchivo('xml', $idHoja , 'xml', $xml);
                }
            }
            break;
        default:
            // Extensiones de archivo no soportado
            $rpta['error'] = 'Formato (' . $tipoArchivo . ') no soportado.';
            break;
    }

    // Crea los formularios, depende de los archivos xml existentes en 
    $xml = new hoja();
    $xml->cargarArchivosXML('xml');
    // Crea la base de datos 
    $bd = new bd(ZC_BD_MOTOR);
    $bd->crearModelo($xml->devolverTablas());
    // Pasar al proyecto el sql de creacion de base de datos
    copiar(RUTA_GENERADOR_CODIGO . '/sql/script_' . ZC_BD_MOTOR . '.sql', '../www/conf/scrip_bd.sql');
    copiar(RUTA_GENERADOR_CODIGO . '/plantilla/html/index.html', '../www/publico/conf/index.html');

} catch (Exception $e) {
    // Error encontrado durante el proceso
    $rpta['error'] = 'Error: ' . $e->getMessage();
}

// Calcula el tiempo de ejecucion
$rpta['tiempo_ejecucion'] = microtime(true) - $time;
miLog($rpta['tiempo_ejecucion'], 'logs/tiempoEjecucion.log');
// Devuelve la respuesta
echo json_encode($rpta);