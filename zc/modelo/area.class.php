<?php

/**
 * Crea areas de texto
 */
class area extends Aelemento {

    /**
     * Constructor de la area de texto, define las caracteristicas que tendra el elemento
     * @param array $caracteristicas Valores seleccionados por el cliente
     * @throws Exception
     */
    function __construct($caracteristicas) {
        parent::__construct($caracteristicas);
    }

    /**
     * Crear y define el elemento HTML a devolver
     */
    function crear() {
        $this->_html = "<textarea" .
                " rows='3'" .
                " class='form-control'" .
                // Identificador
                " id='{$this->_id}'" .
                " name='{$this->_id}'" .
                // Autofoco
                " {$this->_autofoco}" .
                // Ayuda visual
                $this->ayuda() .
                $this->placeholder() .
                "/>" .
                " </textarea>";
        return $this;
    }
    
}
