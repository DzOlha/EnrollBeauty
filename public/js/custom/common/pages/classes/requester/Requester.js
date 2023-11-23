class Requester {
    constructor() {
        this.defaultErrorMessage = 'Error getting info! Please, try again later!';
    }

    get(apiUrl, successCallback, errorCallback) {
        $.getJSON(apiUrl, (response) => {
            if (response.error) {
                errorCallback(response);
                return;
            }
            successCallback(response);
        })
            .fail((jqXHR, textStatus, errorThrown) => {
                errorCallback({'error': this.defaultErrorMessage});
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
            error: (error) => {
                errorCallback({'error': `${this.defaultErrorMessage}`});
            }
        });
    }
}