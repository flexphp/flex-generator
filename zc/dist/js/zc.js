// Configuracion de la carga del archivo
var extensionesPermitidas = '.xls,.xlsx,.ods,.zcl,.txt';
Dropzone.options.cargarArchivos = {
    dictDefaultMessage: '<h2>Arrastra los archivos</h2> (o haz click)',
    addRemoveLinks: true,
    dictRemoveFile: 'Eliminar',
    acceptedFiles: extensionesPermitidas,
    dictInvalidFileType: 'Solo se aceptan archivos (' + extensionesPermitidas + ')',
    dictCancelUpload: 'Cancelar',
    dictCancelUploadConfirmation: 'Esta seguro?',
    success: function(file, response){
        var rpta = JSON.parse(response);
        if (rpta.error !== undefined && rpta.error !== '') {
            alert(rpta.error);
        } else {
            alert('Felicidades! Proyecto creado exitosamente');
        }
    }
}