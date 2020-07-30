var allowedExtensions = '.xls,.xlsx,.ods';

Dropzone.options.files = {
    dictDefaultMessage: '<h2>Drop files here</h2> (or upload)',
    addRemoveLinks: true,
    acceptedFiles: allowedExtensions,
    dictInvalidFileType: 'Allowed extensions file: (' + allowedExtensions + ')',
    success: function (file, response) {
        if (response.message !== undefined && response.message !== '') {
            alert(response.message);
        } else {
            alert(file.name + ' ready!');
        }
    },
    error: function (file, response, xhr) {
        if (response.message !== undefined && response.message !== '') {
            alert(response.message);
        } else {
            alert(file.name + ' return error ' + xhr.status + ' (' + xhr.statusText + ')');
        }
    }
}

document.getElementById('year').innerHTML = new Date().getFullYear();
