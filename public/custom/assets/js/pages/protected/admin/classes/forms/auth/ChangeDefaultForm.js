import Form from "../../../../user/classes/forms/Form.js";
import NameRegex from "../../../../../../classes/regex/impl/NameRegex.js";
import EmailRegex from "../../../../../../classes/regex/impl/EmailRegex.js";
import PasswordRegex from "../../../../../../classes/regex/impl/PasswordRegex.js";

class ChangeDefaultForm extends Form {
    constructor(requester, submitUrl, loginWebPage) {
        super(
            'change-default-form',
            'change-default-form-submit',
            submitUrl,
            requester
        );

        this.nameInputId = 'name-input';
        this.surnameInputId = 'surname-input';
        this.emailInputId = 'email-input';
        this.oldPasswordInputId = 'old-password-input';
        this.newPasswordInputId = 'new-password-input';
        this.confirmPasswordInputId = 'confirm-password-input';

        $(`#${this.nameInputId}`).tooltip();
        $(`#${this.surnameInputId}`).tooltip();
        $(`#${this.emailInputId}`).tooltip();
        $(`#${this.oldPasswordInputId}`).tooltip();
        $(`#${this.newPasswordInputId}`).tooltip();
        $(`#${this.confirmPasswordInputId}`).tooltip();

        this.loginUrl = loginWebPage;
    }
    successCallbackSubmit(response) {
        this.showSuccessMessage(response.success);
        setTimeout(() => {
            window.location.href = this.loginUrl;
        }, 2000)
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

        let oldPassword = document.getElementById(this.oldPasswordInputId);
        if (oldPassword === null) return;
        oldPassword = oldPassword.value.trim();

        let newPassword = document.getElementById(this.newPasswordInputId);
        if (newPassword === null) return;
        newPassword = newPassword.value.trim();

        let confirmPassword = document.getElementById(this.confirmPasswordInputId);
        if (confirmPassword === null) return;
        confirmPassword = confirmPassword.value.trim();

        if (idAssoc === true) {
            let result = {};
            result[this.nameInputId] = name;
            result[this.surnameInputId] = surname;
            result[this.emailInputId] = email;
            result[this.oldPasswordInputId] = oldPassword;
            result[this.newPasswordInputId] = newPassword;
            result[this.confirmPasswordInputId] = confirmPassword;

            return result;
        }
        return {
            'name': name,
            'surname': surname,
            'email': email,
            'old-password': oldPassword,
            'new-password': newPassword,
            'confirm-new-password': confirmPassword
        };
    }

    errorCallbackSubmit(response) {
        this.showErrorMessage(response.error);
    }
    getRules() {
        const formRules = {};

        let passReg = new PasswordRegex();
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
        formRules[this.oldPasswordInputId] = {
            required: true,
            pattern: passReg
        };
        formRules[this.newPasswordInputId] = {
            required: true,
            pattern: passReg
        };
        formRules[this.confirmPasswordInputId] = {
            required: true,
            equalTo: `${this.newPasswordInputId}`
        };
        return formRules;
    }

    getMessages() {
        const formMessages = {};

        formMessages[this.nameInputId] = {
            required: 'Please enter your name',
            pattern: 'Name must be between 3-50 characters long and contain only letters with dashes'
        };
        formMessages[this.surnameInputId] = {
            required: 'Please enter your surname',
            pattern: 'Surname must be between 3-50 characters long and contain only letters with dashes'
        };
        formMessages[this.emailInputId] = {
            required: 'Please enter your email address',
            pattern: 'Please enter an email address in the format myemail@mailservice.domain that not exceeds 100 characters'
        };
        formMessages[this.oldPasswordInputId] = {
            required: 'Please enter your password',
            pattern: 'Password must contain at least one uppercase letter, one lowercase letter, ' +
                '     one digit, one special character, and be between 8 to 30 characters long'
        };
        formMessages[this.newPasswordInputId] = {
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
export default ChangeDefaultForm;