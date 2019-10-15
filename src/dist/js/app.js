var allowedExtensions = '.xls,.xlsx,.ods';

Dropzone.options.files = {
    dictDefaultMessage: '<h2>Drop files here</h2> (or upload)',
    addRemoveLinks: true,
    acceptedFiles: allowedExtensions,
    dictInvalidFileType: 'Allowed extensions file: (' + allowedExtensions + ')',
    success: function (file, response) {
        var response = JSON.parse(response);

        if (response.error !== undefined && response.error !== '') {
            alert(response.error);
        } else {
            alert('Ready! \n Spent Time: ' + response.executionTime + ' in seg');
        }
    }
}
