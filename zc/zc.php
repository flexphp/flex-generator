<?php

// Se utiliza para calcular el tiempo de ejecucion
$time = microtime(true);
// Archivo de configuracion
require 'conf/conf.php';
// Configuracion inicial, crea plantillas y demas recursos utilizados
require RUTA_GENERADOR_CODIGO . '/includes/base.inc.php';
// Respuesta devuelta durante el proceso
$rpta = array();
// Ruta del archivo cargado
$rutaArchivo = '';
// Log del archivo cargado por el usuario
miLog($_FILES);

if (isset($_FILES['file'])) {
    // Ruta temporal del archivo
    $rutaTemporal = $_FILES['file']['tmp_name'];
    $rutaArchivo = RUTA_GENERADOR_CODIGO . '/archivos/' . $_FILES['file']['name'];
    // Mueve el archivo a la ruta definitica
    if (!move_uploaded_file($rutaTemporal, $rutaArchivo)) {
        $rpta['error'] = 'Error moviendo archivo: $rutaArchivo';
    }
    // Extraer la extension del nombre del archivo
    $extension = explode('.', $_FILES['file']['name']);
    // Extension del archivo, con este se determina que proceso aplicar
    $tipoArchivo = end($extension);
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
            $hojas = new SpreadsheetReader($rutaArchivo);
            // Extraer cada una de las hojas del libro
            $cadaHoja = $hojas->Sheets();
            // Para las hoja de calculo excel 97 el numero de la fila no inicia en la posicion 0, se deja en la posicion - 1
            $restar = ('xls' == strtolower($tipoArchivo)) ? 1 : 0;
            // Recorre cada una de las hojas
            foreach ($cadaHoja as $numeroHoja => $nombreHoja) {
                if ($nombreHoja == 'ZeroCode') {
                    // La hoja donde estan las listas desplegables no la tiene encuenta
                    continue;
                } elseif (strtolower($nombreHoja) == ZC_CONFIG_PAGINA) {
                    // Hoja de parametrizacion general de la aplicacion
                    foreach ($hojas as $numeroFila => $columnas) {
                        $numeroFila -= $restar; 
                        foreach ($columnas as $numeroColumna => $contenido) {
                            if ($contenido == '') {
                                // No tiene contenido, sigue con la siguiente columna
                                continue;
                            }
                            switch (true) {
                                // Los encabezados son las filas pares (0,2,4,6,...)
                                case $numeroFila%2 == 0:
                                    // Encabezados
                                    $tag[$numeroColumna] = reemplazarCaracteresEspeciales(strtolower($contenido));
                                    break;
                                default:
                                    // Valores
                                    $config[$tag[$numeroColumna]] = $contenido;
                                    break;
                            }
                        }
                    }
                    // Configuracion del conexion a base de datos, 
                    define('ZC_BD_SERVIDOR', $config[ZC_CONFIG_BD_SERVIDOR]);
                    define('ZC_BD_PUERTO', $config[ZC_CONFIG_BD_PUERTO]);
                    define('ZC_BD_ESQUEMA', $config[ZC_CONFIG_BD_ESQUEMA]);
                    define('ZC_BD_USUARIO', $config[ZC_CONFIG_BD_USUARIO]);
                    define('ZC_BD_CLAVE', $config[ZC_CONFIG_BD_CLAVE]);
                    // Numero de registros por pagina, para la funcion buscar
                    $registrosPorPagina = (isset($config[ZC_CONFIG_REGISTROS_POR_PAGINA]) && $config[ZC_CONFIG_REGISTROS_POR_PAGINA] != '') ? $config[ZC_CONFIG_REGISTROS_POR_PAGINA] : ZC_REGISTROS_POR_PAGINA_PREDETERMINADO;
                    define('ZC_REGISTROS_POR_PAGINA', $registrosPorPagina);
                    // Crea los archivos y estrucutura de directorios base
                    plantillas();
                    // Crea los archivos de configuracion segun los valores dados
                    config();
                } else {
                    // Identificador de la hoja, seusa como nombre del XML generado
                    $idHoja = reemplazarCaracteresEspeciales(strtolower($nombreHoja));
                    // Establece la hoja que se va a procesar
                    $hojas->ChangeSheet($numeroHoja);

                    // Crear un archivo XML por cada hoja
                    $xml = insertarEspacios(0) . '<?xml version="1.0" encoding="UTF-8"?>' . FIN_DE_LINEA;
                    $xml .= insertarEspacios(0) . '<crear>' . FIN_DE_LINEA;
                    $xml .= insertarEspacios(4) . '<' . $idHoja . '>' . FIN_DE_LINEA;

                    // Cada fila
                    foreach ($hojas as $numeroFila => $columnas) {
                        $numeroFila -= $restar; 
                        if ($numeroFila > 2 && $columnas[0] !== '') {
                            // Comienza a procesar cada uno de los campos que estan configurados 
                            // en la hoja de calculo
                            $idCampo = reemplazarCaracteresEspeciales(strtolower($columnas[1]));
                            $xml .= insertarEspacios(8) . '<' . $idCampo . '>' . FIN_DE_LINEA;
                        }
                        foreach ($columnas as $numeroColumna => $contenido) {
                            if ($contenido == '') {
                                // No tiene contenido, sigue con la siguiente columna
                                continue;
                            }
                            // Inicia Informacion de seguimiento, se puede eliminar
                            $info = $numeroFila . ':' . $numeroColumna . ' = ' . $contenido . "\n";
                            miLog($info);
                            // Fin Informacion de seguimiento
                            switch ($numeroFila) {
                                case 0:
                                    // Encabezados con caracteristicas del formulario
                                case 2:
                                    // Descripciones caracteristicas campos del formulario
                                    $tag[$numeroColumna] = reemplazarCaracteresEspeciales(strtolower($contenido));
                                    break;
                                case 1:
                                    // Detalles del encabezado
                                    $xml .= insertarEspacios(8) . '<' . $tag[$numeroColumna] . '>' . $contenido . '</' . $tag[$numeroColumna] . '>' . FIN_DE_LINEA;
                                    break;
                                default:
                                    // Detalles de los campos
                                    $xml .= insertarEspacios(12) . '<' . $tag[$numeroColumna] . '>' . $contenido . '</' . $tag[$numeroColumna] . '>' . FIN_DE_LINEA;
                                    break;
                            }
                        }
                        if ($numeroFila > 2 && $columnas[0] !== '') {
                            $xml .= insertarEspacios(8) . '</' . $idCampo . '>' . FIN_DE_LINEA;
                        }
                    }
                    $xml .= insertarEspacios(4) . '</' . $idHoja . '>' . FIN_DE_LINEA;
                    $xml .= insertarEspacios(0) . '</crear>' . FIN_DE_LINEA;
                    // Crear el archivo XML por cada hoja
                    file_put_contents('xml/' . $idHoja . '.xml', $xml);
                }
            }
            break;
        case 'txt':
            // Texto plano
        case 'zcs':
            // Sintaxis ZC
            require RUTA_GENERADOR_CODIGO . '/includes/sintaxis/zcs.inc.php';
            $zcl = new zcs($rutaArchivo);
            $rpta['error'] = $zcl->devolverError();
            break;
        default:
            $rpta['error'] = 'Formato (' . $tipoArchivo . ') no soportado.';
            break;
    }
    
    /**
     * Crea los formularios, depende de los archivos xml recien creados
     */
    $xml = new xml();
    $xml->cargarArchivosXML('xml');
} catch (Exception $e) {
    $rpta['error'] = 'Error: ' . $e->getMessage();
}

// Calcula el tiempo de ejecucion
$rpta['tiempo_ejecucion'] = microtime(true) - $time;
echo json_encode($rpta);