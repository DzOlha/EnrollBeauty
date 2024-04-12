class Requester {
    constructor() {
        this.defaultErrorMessage = 'Error getting info! <br>Please, try again later!';
    }

    get(apiUrl, successCallback, errorCallback) {
        // Send the GET request using jQuery's AJAX function
        $.ajax({
            url: apiUrl,
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
        // Send the POST request using jQuery's AJAX function
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
        // Send the POST request using jQuery's AJAX function
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
}
export default Requester;