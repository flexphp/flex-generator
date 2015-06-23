<?php

/**
 * Procesas archivos XML de configuracion.
 * La estructura es como la siguiente:
 * <?xml version="1.0" encoding="UTF-8"?>
 * <crear>
 *     <pruebas> <-- Nombre de la hoja de calculo
 *         <tipoWS>SOAP</tipoWS>
 *         <crearWS>1</crearWS>
 *         <metodo>POST</metodo>
 *         <numero1 tipo = 'cajaTexto' etiqueta = 'Numero1' dato = 'numero1' obligatorio = 'true' numero1> <-- Atributos del elemento
 *         <numero2>
 *             <tipo>cajaTexto</tipo>           <-
 *             <etiqueta>Numero 1</etiqueta>    <--
 *             <dato>numero</dato>              <--- Otra forma de agregar atributos
 *             <por_defecto_es>2</dato>         <--
 *             <obligatorio>true</obligatorio>  <-
 *         </numero2>
 *         <resultado>
 *             <tipo>cajaTexto</tipo>
 *             <etiqueta>Numero 1</etiqueta>
 *             <dato>numero</dato>
 *         </resultado>
 *         <sumar>
 *             <tipo>Boton</tipo>
 *             <etiqueta>Sumar</etiqueta>
 *         </sumar>
 *     </pruebas>
 * </crear>
 */
class xml {

    /**
     * Nombre de la hoja de calculo
     * @var string
     */
    public $nombreHoja;

    /**
     * Almacena en un array cada uno de los elementos del XML, esta es la variable
     * usada para crear el formulario
     * @var array
     */
    protected $elementos = array();

    function __construct() {
    }

    /**
     * Recorre la estructura del archivo xml
     * @param string $rutaXML Ruta del archivo xml a procesar
     */
    protected function estructuraArchivoXML($rutaXML) {
        $xml = simplexml_load_file($rutaXML);
        foreach ($xml as $padre => $hijos) {
            // $padre es el nombre de la hoja de calculo en el archivo
            $form[ZC_ID] = $this->nombreHoja = strtolower($padre);
            // Reserva el elemento en posicion 0
            $this->elementos[0] = array();
            $this->atributosXPathXML($hijos, $form);
            $this->hijosXPathXML($hijos, $form);
            // Asigna al primer elemento las caracteristicas del formulario
            $this->elementos[0] = $form;
        }
    }

    /**
     * Determina si el XPath tiene hijos, de tenerlos se vuelve una funcion
     * recursiva hasta hallarlos todos<br/>
     * Ejemplo:
     * <base>
     *      <padre>
     *          <hijo>
     *              <nieto>
     *              </nieto>
     *          </hijo>
     *      </padre>
     * </base>
     * @param xpath $hijos XPath
     * @param array $propiedades Conjunto de atributos del elemento
     */
    protected function hijosXPathXML($hijos, &$propiedades) {
        foreach ($hijos as $propiedad => $valor) {
            $contadorHijos = 0;
            if (count($hijos->{$propiedad}[$contadorHijos]) == 0) {
                /**
                 * Solo agrega la propiedad sino fue definida en los atributos
                 */
                if (!isset($propiedades[strtolower((string) $propiedad)])) {
                    $propiedades = $this->agregarPropiedad($propiedades, $propiedad, $valor);
                }
            } else {
                /**
                 * Crea variable con el nombre del elemento
                 */
                $$propiedad = array(ZC_ID => $propiedad);
                $this->atributosXPathXML($valor, $$propiedad);
                $this->hijosXPathXML($hijos->{$propiedad}[$contadorHijos], $$propiedad);
                $this->elementos[] = $$propiedad;
                ++$contadorHijos;
            }
        }
    }

    /**
     * Crea un array con el conjunto de prioridades definidas en el XML, si una
     * propiedad esta definida mas de una vez para un mismo elemento tiene propiedad
     * la definida de inline
     * Llos valores son convertidos a (string) ya que son xPath
     * Ejemplo:
     * <atributo etiqueta="tienePrioridad">
     *  <etiqueta>noSeTieneEnCuentaYaExisteArriba</etiqueta>
     * </atributo>
     * @param array $propiedades Conjunto de atributos del elemento
     * @param string $atributo Nombre del atributo
     * @param string $valor Valor del atributo
     * @return type Nuevo conjunto de atributos (Se agrega el nuevo)
     */
    protected function agregarPropiedad($propiedades, $atributo, $valor) {
        $propiedades[strtolower((string) $atributo)] = (string) $valor;
        return $propiedades;
    }

    /**
     * Determina si el elemento tiene atributos
     * Ejemplo
     * <base>
     *      <nombre atributo-1="soy" atributo-2="un" atributo-3="atributo">Valor</nombre>
     * </base>
     * @param type $xPath
     * @param array $propiedades Conjunto de atributos del elemento
     * @return boolean
     */
    protected function atributosXPathXML($xPath, &$propiedades) {
        if (count($xPath) > 0) {
            foreach ($xPath->attributes() as $atributo => $valor) {
                $propiedades = $this->agregarPropiedad($propiedades, $atributo, $valor);
            }
        }
    }
}