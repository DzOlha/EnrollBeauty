
import Form from "./../Form.js";
import PasswordRegex from "../../../../../../classes/regex/impl/PasswordRegex.js";
import NameRegex from "../../../../../../classes/regex/impl/NameRegex.js";
import EmailRegex from "../../../../../../classes/regex/impl/EmailRegex.js";



class RegistrationForm extends Form {
    constructor(requester, submitUrl, loginWebPage) {
        super(
            'registration-form',
            'registration-form-submit',
            submitUrl,
            requester
        );
        this.nameInputId = 'name-input';
        this.surnameInputId = 'surname-input';
        this.emailInputId = 'email-input';
        this.passwordInputId = 'password-input';
        this.confirmPasswordInputId = 'confirm-password-input';

        this.loginUrl = loginWebPage;

        $(`#${this.nameInputId}`).tooltip();
        $(`#${this.surnameInputId}`).tooltip();
        $(`#${this.emailInputId}`).tooltip();
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
        let name = document.getElementById(this.nameInputId);
        if (name === null) return;
        name = name.value.trim();

        let surname = document.getElementById(this.surnameInputId);
        if (surname === null) return;
        surname = surname.value.trim();

        let email = document.getElementById(this.emailInputId);
        if (email === null) return;
        email = email.value.trim();

        let password = document.getElementById(this.passwordInputId);
        if (password === null) return;
        password = password.value.trim();

        let confirmPassword = document.getElementById(this.confirmPasswordInputId);
        if (confirmPassword === null) return;
        confirmPassword = confirmPassword.value.trim();

        if (idAssoc === true) {
            let result = {};
            result[this.nameInputId] = name;
            result[this.surnameInputId] = surname;
            result[this.emailInputId] = email;
            result[this.passwordInputId] = password;
            result[this.confirmPasswordInputId] = confirmPassword;

            return result;
        }
        return {
            'name': name,
            'surname': surname,
            'email': email,
            'password': password,
            'confirm-password': confirmPassword
        };
    }

    getRules() {
        const formRules = {};

        let nameReg = new NameRegex();

        formRules[this.nameInputId] = {
            required: true,
            pattern: nameReg
        };
        formRules[this.surnameInputId] = {
            required: true,
            pattern: nameReg
        };
        formRules[this.emailInputId] = {
            required: true,
            pattern: new EmailRegex()
        };
        formRules[this.passwordInputId] = {
            required: true,
            pattern: new PasswordRegex()
        };
        formRules[this.confirmPasswordInputId] = {
            required: true,
            equalTo: `${this.passwordInputId}`
        };
        return formRules;
    }

    getMessages() {
        const formMessages = {};

        formMessages[this.nameInputId] = {
            required: 'Please enter your name',
            pattern: 'Name must be between 3-50 characters long and contain only letters'
        };
        formMessages[this.surnameInputId] = {
            required: 'Please enter your surname',
            pattern: 'Surname must be between 3-50 characters long and contain only letters'
        };
        formMessages[this.emailInputId] = {
            required: 'Please enter your email address',
            pattern: 'Please enter an email address in the format myemail@mailservice.domain that not exceeds 100 characters'
        };
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
export default RegistrationForm;