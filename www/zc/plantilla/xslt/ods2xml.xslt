<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" xmlns:office="urn:oasis:names:tc:opendocument:xmlns:office:1.0" xmlns:table="urn:oasis:names:tc:opendocument:xmlns:table:1.0" xmlns:text="urn:oasis:names:tc:opendocument:xmlns:text:1.0" version="1.0" exclude-result-prefixes="office table text">
    <xsl:output method="xml" indent="yes" encoding="UTF-8" omit-xml-declaration="no"/>

    <!-- Fila en la que estan los encabezados del formulario -->
    <xsl:variable name="filaEncabezado" select="1" />
    <!-- Prefijo de los atributos de formulario -->
    <xsl:variable name="prefijoAtributo" select="''" />
    <!-- Fila en la que estan los encabezados de los detalles del formulario -->
    <xsl:variable name="filaDetalles" select="3" />
    <!-- numero de filas que tiene el encabezado  del formulario -->
    <xsl:variable name="totalColumnasEncabezado" select="4" />
    <!-- numero de filas que tiene el detalle del formulario -->
    <xsl:variable name="totalColumnasDetalle" select="10" />
    <!-- formatear etiquetas de nombres a minusculas  -->
    <xsl:variable name="lowercase" select="'abcdefghijklmnopqrstuvwxyz_'" />
    <xsl:variable name="uppercase" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ ?'" />

    <xsl:template match="/">
        <!-- Colocar base de todo el documento -->
        <crear>
            <!-- Colocar salto de linea y tabulacion -->
            <xsl:text disable-output-escaping="yes">&#10;    </xsl:text>
            <!-- Por cada hoja de calculo en el libro -->
            <xsl:for-each select="//table:table">
                <!-- Si la segunda fila de la hoja de calculo tiene datos -->
                <xsl:if test="./table:table-row[2]">
                    <!-- Omitimos la hoja de configuracion -->
                    <xsl:if test="./@table:name != 'ZeroCode'">
                        <!-- Limpia el nombre de la hoja actual: mi nombre a mi-nombre -->
                        <xsl:variable name="nombreHojaActual" select="translate(./@table:name, $uppercase, $lowercase)"/>
                        <!-- Crear elemento con el nombre de la hoja <nombre-hoja>-->
                        <xsl:element name="{$nombreHojaActual}">
                            <!-- Aplica la plantilla //table:table -->
                            <xsl:text disable-output-escaping="yes">&#10;</xsl:text>
                            <xsl:apply-templates select="."/>
                        </xsl:element>
                        <xsl:text disable-output-escaping="yes">&#10;</xsl:text>
                    </xsl:if>
                </xsl:if>
            </xsl:for-each>
        </crear>
    </xsl:template>

    <!-- Procesa cada hoja -->
    <xsl:template match="table:table">
        <!-- Por cada fila en la hoja actual -->
        <xsl:for-each select="table:table-row">
            <!-- Valida que hoja no esta vacia -->
            <xsl:if test="count(./table:table-cell/text:p)">
                <xsl:choose>
                    <!-- Cuando la fila actual es la numero (1) o la (2) -->
                    <xsl:when test="position() &lt; $filaDetalles">
                        <!-- Encabezados -->
                        <!-- Ubicacion de los titulos del encabezado -->
                        <xsl:variable name="titulos" select="$filaEncabezado"/>
                        <!-- Cantidad de columnas en el encabezado -->
                        <xsl:variable name="totalColumnas" select="$totalColumnasEncabezado"/>
                        <!-- La fila del encabezado no se procesa -->
                        <xsl:if test="position() != $titulos">
                            <xsl:call-template name="CellLoop">
                                <xsl:with-param name="totalCells" select="$totalColumnas"/>
                                <xsl:with-param name="currentColumn" select="1"/>
                                <xsl:with-param name="currentCell" select="1"/>
                                <xsl:with-param name="currentIteration" select="1"/>
                                <xsl:with-param name="filaTitulos" select="$titulos"/>
                            </xsl:call-template>
                        </xsl:if>
                    </xsl:when>
                    <xsl:otherwise>
                        <!-- Detalles, Cantidad de columnas de los detalles-->
                        <xsl:variable name="titulos" select="$filaDetalles"/>
                        <xsl:variable name="totalColumnas" select="$totalColumnasDetalle"/>

                        <!-- No lo hace para el encabezado -->
                        <xsl:if test="position() != $titulos">
                            <!-- coloca un hijo al formulario con el titulo del elemento-->
                            <xsl:variable name="id" select="translate(concat($prefijoAtributo, ./table:table-cell[2]/text:p), $uppercase, $lowercase)"/>

                            <xsl:text disable-output-escaping="yes">        </xsl:text>
                            <xsl:element name="{$id}">
                                <xsl:text disable-output-escaping="yes">&#10;</xsl:text>

                                <xsl:call-template name="CellLoop">
                                    <xsl:with-param name="totalCells" select="$totalColumnas"/>
                                    <xsl:with-param name="currentColumn" select="1"/>
                                    <xsl:with-param name="currentCell" select="1"/>
                                    <xsl:with-param name="currentIteration" select="1"/>
                                    <xsl:with-param name="filaTitulos" select="$titulos"/>
                                </xsl:call-template>

                                <xsl:text disable-output-escaping="yes">        </xsl:text>
                            </xsl:element>
                            <xsl:text disable-output-escaping="yes">&#10;</xsl:text>

                        </xsl:if>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:if>
        </xsl:for-each>
    </xsl:template>

    <xsl:template name="CellLoop">
        <!-- Total columnas + 1 llenas por fila -->
        <xsl:param name="totalCells"/>
        <!-- # de Columna de la fila actual -->
        <xsl:param name="currentColumn"/>
        <!-- # de celda actual de la fila -->
        <xsl:param name="currentCell"/>
        <xsl:param name="currentIteration"/>
        <!-- Numero de la fila donde estan los titulos (descripciones) -->
        <xsl:param name="filaTitulos"/>

        <!-- Si no ha llegado al final de las celdas llenas -->
        <xsl:if test="$currentCell &lt;= $totalCells">
            <!-- Si la celda no esta vacia -->
            <xsl:if test="./table:table-cell[position() = $currentCell] != ''">
                <!-- Crea la propiedad -->
                <xsl:call-template name="MakeTag">
                    <xsl:with-param name="currentColumn" select="$currentColumn"/>
                    <xsl:with-param name="tagContentPointer" select="./table:table-cell[position() = $currentCell]"/>
                    <xsl:with-param name="filaTitulos" select="$filaTitulos"/>
                </xsl:call-template>
            </xsl:if>
            <!-- Continua recorriendo la tabla -->
            <xsl:choose>
                <xsl:when test="$currentIteration &lt; ./table:table-cell[position() = $currentCell]/@table:number-columns-repeated">
                    <!-- On a 'repeating' cell - call the template again, incrementing the column count, but keep the cell count the same -->
                    <xsl:call-template name="CellLoop">
                        <xsl:with-param name="totalCells" select="$totalCells"/>
                        <xsl:with-param name="currentColumn" select="$currentColumn + 1"/>
                        <xsl:with-param name="currentCell" select="$currentCell"/>
                        <xsl:with-param name="currentIteration" select="$currentIteration + 1"/>
                        <xsl:with-param name="filaTitulos" select="$filaTitulos"/>
                    </xsl:call-template>
                </xsl:when>
                <xsl:otherwise>
                    <!-- On a 'normal' cell - call the template again, incrementing both the column count and the cell count -->
                    <xsl:call-template name="CellLoop">
                        <xsl:with-param name="totalCells" select="$totalCells"/>
                        <xsl:with-param name="currentColumn" select="$currentColumn + 1"/>
                        <xsl:with-param name="currentCell" select="$currentCell + 1"/>
                        <xsl:with-param name="currentIteration" select="1"/>
                        <xsl:with-param name="filaTitulos" select="$filaTitulos"/>
                    </xsl:call-template>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:if>
    </xsl:template>

    <!-- Agrega cada propiedad tanto del detalle como el encabezado -->
    <xsl:template name="MakeTag">
        <xsl:param name="currentColumn"/>
        <xsl:param name="tagContentPointer"/>
        <xsl:param name="filaTitulos"/>

        <xsl:if test=". != ''">
            <!-- coloca espacios para el encabezado y los detalles -->
            <xsl:if test="$filaTitulos = $filaEncabezado">
                <!-- para encabezados -->
                <xsl:text disable-output-escaping="yes">        </xsl:text>
            </xsl:if>
            <xsl:if test="$filaTitulos = $filaDetalles">
                <!-- para detalles -->
                <xsl:text disable-output-escaping="yes">            </xsl:text>
            </xsl:if>

            <xsl:variable name="etiqueta" select="translate(//table:table/table:table-row[$filaTitulos]/table:table-cell[$currentColumn]/text:p, $uppercase, $lowercase)"/>

            <xsl:element name="{$etiqueta}">

                <xsl:for-each select="$tagContentPointer/text:p">
                  
                    <xsl:if test="position() &gt; 1">
                        <xsl:call-template name="multilineSeparator" />
                    </xsl:if>
                    <!-- Agrega el valor de la celda -->
                    <xsl:value-of select="."/>

                </xsl:for-each>

            </xsl:element>
            <xsl:text disable-output-escaping="yes">&#10;</xsl:text>
        </xsl:if>
    </xsl:template>
    <!-- Cells that contain multiple lines (ctrl-enter) will translate the line endings to this variable in the output -->

    <!-- Default is a 'newline' (empty line), but can be substituted for other strings. -->

    <!-- For example: replace the <xsl:text>\n</xsl:text> segment with <xsl:element name="br"/> to use an XHTML-style break instead -->

    <xsl:template name="multilineSeparator">

        <xsl:text>

        </xsl:text>

    </xsl:template>
</xsl:stylesheet>