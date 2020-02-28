var allowedExtensions = '.xls,.xlsx,.ods';

Dropzone.options.files = {
    dictDefaultMessage: '<h2>Drop files here</h2> (or upload)',
    addRemoveLinks: true,
    acceptedFiles: allowedExtensions,
    dictInvalidFileType: 'Allowed extensions file: (' + allowedExtensions + ')',
    success: function (file, response) {
        if (response.message !== undefined && response.message !== '') {
            alert(response.error);
        } else {
            alert('Ready!');
        }
    }
}

document.getElementById('year').innerHTML = new Date().getFullYear();
