class Requester {
    constructor() {
        this.defaultErrorMessage = 'Error getting info! <br>Please, try again later!';
    }

    get(apiUrl, successCallback, errorCallback, dataToSend = null)
    {
        let queryString = dataToSend !== null ? `?${$.param(dataToSend)}` : '';

        $.ajax({
            url: apiUrl + queryString,
            method: 'GET',
            dataType: 'json',
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }

    post(apiUrl, dataToSend, successCallback, errorCallback) {
        $.ajax({
            url: apiUrl,
            method: 'POST',
            data: dataToSend,
            dataType: 'json',
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }

    postFiles(apiUrl, dataToSend, successCallback, errorCallback) {
        $.ajax({
            url: apiUrl,
            method: 'POST',
            data: dataToSend,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Set content type as false to let the server handle it
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }

    put(apiUrl, dataToSend, successCallback, errorCallback) {
        // Convert the data to URL-encoded format
        let formData = $.param(dataToSend);

        $.ajax({
            url: apiUrl,
            method: 'PUT',
            dataType: 'json',
            data: formData,
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }

    putFiles(apiUrl, dataToSend, successCallback, errorCallback) {
        $.ajax({
            url: apiUrl,
            method: 'PUT',
            data: dataToSend,
            dataType: 'json',
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Set content type as false to let the server handle it
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }

    delete(apiUrl, dataToSend, successCallback, errorCallback)
    {
        let queryString = $.param(dataToSend);

        $.ajax({
            url: apiUrl + `?${queryString}`,
            method: 'DELETE',
            dataType: 'json',
            success: (response) => {
                if (response.error) {
                    errorCallback(response);
                } else {
                    successCallback(response);
                }
            },
            error: (xhr) => {
                if(xhr.responseJSON) {
                    errorCallback(xhr.responseJSON)
                } else {
                    errorCallback({ 'error': `${this.defaultErrorMessage}` });
                }
            }
        });
    }
}
export default Requester;