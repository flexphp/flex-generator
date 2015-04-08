// Configuracion de la carga del archivo
Dropzone.options.cargarArchivos = {
    dictDefaultMessage: '<h2>Arrastra los archivos aqui</h2> (o haz click)',
    addRemoveLinks: true,
    dictRemoveFile: 'Eliminar',
    acceptedFiles: '.xls,.xlsx,.ods',
    dictInvalidFileType: 'Solo se aceptan hojas de calculo (.xls,.xlsx,.ods)',
    dictCancelUpload: 'Cancelar',
    dictCancelUploadConfirmation: 'Esta seguro?',
    success: function(file, response){
        // Trasforma la respuesta json en un objeto
        console.log(response);
        var rpta = JSON.parse(response);
        if (rpta.error !== undefined && rpta.error !== '') {
            alert(rpta.error);
        } else {
            alert('Felicidades! Proyecto creado exitosamente');
        }
    }
}

