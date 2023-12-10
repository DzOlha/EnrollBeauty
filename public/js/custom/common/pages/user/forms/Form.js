
import Notifier from "../../classes/notifier/Notifier.js";
class Form {
    constructor(formClass, submitButtonId, submitActionUrl, requesterObject) {
        this.formClass = formClass;
        this.submitButtonId = submitButtonId;
        this.submitActionUrl = submitActionUrl;
        this.requester = requesterObject;
    }

    addListenerSubmitForm() {
        let submit = document.getElementById(this.submitButtonId);
        submit.addEventListener('click', this.listenerSubmitForm);
    }

    listenerSubmitForm = () => {
        let formRules = this.getRules();
        let formMessages = this.getMessages();
        let formCurrentValue = this.collectDataToSend(true);

        // Call the validation function and get the errors
        const validationErrors = this.validateFormData(formCurrentValue, formRules, formMessages);

        this.displayErrors(validationErrors);

        // Check if there are no div.error elements with text content
        const noErrorWithText = $("div.error").get().every(function (element) {
            return $(element).text().trim() === '';
        });
        //console.log(noErrorWithText);

        // Handle form submission if there are no errors
        if (noErrorWithText) {
            this.handleFormSubmission();
        }
    }

    // Function to validate the form data
    validateFormData(formData, rules, messages) {
        const errors = {};

        for (const key in formData) {
            if (rules.hasOwnProperty(key)) {
                const value = formData[key];
                const fieldRules = rules[key];

                for (const rule in fieldRules) {
                    if (rule === 'required' && fieldRules[rule]) {
                        if (!value) {
                            errors[key] = messages[key].required;
                        }
                    } else if (rule === 'pattern' && fieldRules[rule]) {
                        const pattern = fieldRules[rule];
                        if (!pattern.test(value)) {
                            errors[key] = messages[key].pattern;
                        }
                    } else if (rule === 'equalTo' && fieldRules[rule]) {
                        const targetField = fieldRules[rule];
                        if (value !== formData[targetField]) {
                            errors[key] = messages[key].equalTo;
                        }
                    }
                    if (!errors[key]) {
                        errors[key] = '';
                    }
                }
            }
        }
        return errors;
    }

    // Function to insert error messages into error containers
    displayErrors(validationErrors) {
        for (const field in validationErrors) {
            const errorContainer = $(`#${field}-error`);
            const input = $(`#${field}`);

            /**
             * Clear error highlighting and messages if there is no errors already
             */
            if (validationErrors[field] === '') {
                input.removeClass('border-danger');
                errorContainer.removeClass('text-danger');
                errorContainer.text('');
                continue;
            }
            /**
             * Add error messages
             */
            input.addClass('border-danger');
            errorContainer.addClass('text-danger');
            errorContainer.text(validationErrors[field]);
        }
    }

    handleFormSubmission = () => {
        //console.log('submission');
        this.requester.post(
            this.submitActionUrl,
            this.collectDataToSend(),
            this.successCallbackSubmit.bind(this),
            this.errorCallbackSubmit.bind(this)
        )
    }

    successCallbackSubmit(response) {
    }

    errorCallbackSubmit(response) {
    }

    collectDataToSend(idAssoc = false) {
    }

    getRules() {
    }

    getMessages() {
    }

    showErrorMessage(message) {
        Notifier.showErrorMessage(message);
    }

    showSuccessMessage(message) {
        Notifier.showSuccessMessage(message);
    }
}
export default Form;