
class Requestor {
    constructor() {
        this.defaultErrorMessage = 'Error getting info! Please, try again later!';
    }

    get(apiUrl, successCallback, errorCallback, errorMessageCallback) {
        $.getJSON(apiUrl, (data) => {
            if (data.error) {
                errorCallback();
                return;
            }
            successCallback(data);
        })
        .fail((jqXHR, textStatus, errorThrown) => {
            errorMessageCallback(this.defaultErrorMessage);
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
                    errorCallback(response.error);
                } else {
                    successCallback(response);
                }
            },
            error: (error) => {
                errorCallback(`${this.defaultErrorMessage}`);
            }
        });
    }
}