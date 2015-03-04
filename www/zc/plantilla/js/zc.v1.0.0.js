/**
 * Actualiza la barra de progreso segun se vayan diligenciando los datos
 * @param string formulario Id del formulario al que se la va validar el progreso
 * @returns {Boolean}
 */
function ZC_actualizarProgreso(formulario, formasValidar){
    // Cantidad de campos encontrados en el formulario
    var contadorCampos = 0;
    // Cantidad de campos validos
    var contadorCamposValidos = 0;
    // Valida que los cambpos checkbox y radio no se cuenten mas de una vez
    var nombres = [];
    // Todos campos del forumlario
    $('#'+formulario).find($(formasValidar)).each(function(){
        //Verifica que no se halla contado antes
        if(nombres.indexOf(this.name) == -1){
            contadorCampos++;
            if ($('#'+this.id).parsley().isValid('#'+this.id)){
                contadorCamposValidos++;
            }
        }
    });
    // Calcula un numero entero por encima
    var progreso = Math.ceil((contadorCamposValidos*100)/contadorCampos);
    $('#progreso-'+formulario).css('width', progreso + '%').attr('aria-valuenow', progreso);
    $('#msj-progreso-'+formulario).html(progreso + '%');
    if(progreso === 100){
        // Deja clases de exito
        $('#progreso-'+formulario).addClass('progress-bar-success').removeClass('progress-bar-striped').removeClass('active');
    }else{
        // Deja clases iniciales
        $('#progreso-'+formulario).removeClass('progress-bar-success').addClass('progress-bar-striped').addClass('active');
    }
}