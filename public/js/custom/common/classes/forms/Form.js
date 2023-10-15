
class Form {
    constructor(formId, submitButtonId, submitActionUrl, requestorObject) {
        this.formId = formId;
        this.submitButtonId = submitButtonId;
        this.submitActionUrl = submitActionUrl;
        this.requestor = requestorObject;
    }
    addListenerSubmitForm() {
        let submit = document.getElementById(this.submitButtonId);
        submit.addEventListener('click', this.listenerSubmitForm);
    }
    listenerSubmitForm = () => {
        let formRules = this.getRules();
        let formMessages = this.getMessages();

        $(`#${this.formId}`).validate({
            rules: formRules,
            messages: formMessages,
            submitHandler: this.handleFormSubmission.bind(this)
        });
    }
    handleFormSubmission = () => {
        this.requestor.post(
            this.submitActionUrl,
            this.collectDataToSend(),
            this.successCallbackSubmit.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackSubmit(response) {}
    errorCallbackSubmit(message) {}
    collectDataToSend() {}
    getRules(){}
    getMessages(){}

    showErrorMessage(message) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.notify(message, 'error', 3);
    }
    showSuccessMessage(message) {
        alertify.set('notifier', 'position', 'top-center');
        alertify.notify(message, 'success', 3);
    }
}