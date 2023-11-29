
class ChangePasswordForm extends Form {
    constructor() {
        super(
            'registration-form',
            'change-password-form-submit',
            '/api/worker/changePassword',
            new Requester()
        );
        this.passwordInputId = 'password-input';
        this.confirmPasswordInputId = 'confirm-password-input';

        this.loginUrl = "/web/worker/login";

        $(`#${this.passwordInputId}`).tooltip();
        $(`#${this.confirmPasswordInputId}`).tooltip();
    }

    successCallbackSubmit(response) {
        this.showSuccessMessage(response.success);
        setTimeout(() => {
            window.location.href = this.loginUrl;
        }, 2000)
    }

    errorCallbackSubmit(response) {
        this.showErrorMessage(response.error);
    }

    collectDataToSend(idAssoc = false) {
        let password = document.getElementById(this.passwordInputId);
        if (password === null) return;
        password = password.value.trim();

        let confirmPassword = document.getElementById(this.confirmPasswordInputId);
        if (confirmPassword === null) return;
        confirmPassword = confirmPassword.value.trim();

        if (idAssoc === true) {
            let result = {};
            result[this.passwordInputId] = password;
            result[this.confirmPasswordInputId] = confirmPassword;

            return result;
        }
        return {
            'password': password,
            'confirm-password': confirmPassword
        };
    }

    getRules() {
        const formRules = {};

        formRules[this.passwordInputId] = {
            required: true,
            pattern: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/
        };
        formRules[this.confirmPasswordInputId] = {
            required: true,
            equalTo: `${this.passwordInputId}`
        };
        return formRules;
    }

    getMessages() {
        const formMessages = {};

        formMessages[this.passwordInputId] = {
            required: 'Please enter your password',
            pattern: 'Password must contain at least one uppercase letter, one lowercase letter, ' +
                '     one digit, one special character, and be between 8 to 30 characters long'
        };
        formMessages[this.confirmPasswordInputId] = {
            required: 'Please confirm your password',
            equalTo: 'Passwords do not match'
        };
        return formMessages;
    }
}