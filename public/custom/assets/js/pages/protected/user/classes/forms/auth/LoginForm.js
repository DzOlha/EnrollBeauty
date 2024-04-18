
import Form from "./../Form.js";
import EmailRegex from "../../../../../../classes/regex/impl/EmailRegex.js";
import PasswordRegex from "../../../../../../classes/regex/impl/PasswordRegex.js";


class LoginForm extends Form {
    constructor(requester, submitUrl, accountUrl) {
        super(
            'login-form',
            'login-form-submit',
            submitUrl,
            requester
        );
        this.emailInputId = 'email-input';
        this.passwordInputId = 'password-input';

        this.accountUrl = accountUrl;
    }

    successCallbackSubmit(response) {
        if(response.data) {
            if(response.data.redirect_url) {
                window.location.href = response.data.redirect_url;
            } else {
                window.location.href = this.accountUrl;
            }
        } else {
            window.location.href = this.accountUrl;
        }
    }

    errorCallbackSubmit(response) {
        this.showErrorMessage(response.error);
    }

    collectDataToSend(idAssoc = false) {
        let email = document.getElementById(this.emailInputId);
        if (email === null) return;
        email = email.value.trim();

        let password = document.getElementById(this.passwordInputId);
        if (password === null) return;
        password = password.value.trim();


        if (idAssoc === true) {
            let result = {};
            result[this.emailInputId] = email;
            result[this.passwordInputId] = password;

            return result;
        }
        return {
            'email': email,
            'password': password,
        };
    }

    getRules() {
        const formRules = {};

        formRules[this.emailInputId] = {
            required: true,
            pattern: new EmailRegex()
        };
        formRules[this.passwordInputId] = {
            required: true,
            pattern: new PasswordRegex()
        };

        return formRules;
    }

    getMessages() {
        const formMessages = {};

        formMessages[this.emailInputId] = {
            required: 'Please enter your email address',
            pattern: 'Please enter an email address in the format myemail@mailservice.domain that not exceeds 100 characters'
        };
        formMessages[this.passwordInputId] = {
            required: 'Please enter your password',
            pattern: 'Password must contain at least one uppercase letter, one lowercase letter, one digit, ' +
                'one special character, and be between 8 to 30 characters long'
        };

        return formMessages;
    }
}
export default LoginForm;