import Form from "../../user/forms/Form.js";
import Requester from "../../classes/requester/Requester.js";
import API from "../../../../common/pages/api.js";

class ChangeDefaultForm extends Form {
    constructor() {
        super(
            'change-default-form',
            'change-default-form-submit',
            API.AUTH.API.ADMIN["change-default-admin-info"],
            new Requester()
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

        this.loginUrl = API.AUTH.WEB.ADMIN.login;
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

        formRules[this.nameInputId] = {
            required: true,
            pattern: /^[A-Za-zА-Яа-яіїІЇ]{3,}$/
        };
        formRules[this.surnameInputId] = {
            required: true,
            pattern: /^[A-Za-zА-Яа-яіїІЇ]{3,}$/
        };
        formRules[this.emailInputId] = {
            required: true,
            pattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/
        };
        formRules[this.oldPasswordInputId] = {
            required: true,
            pattern: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/
        };
        formRules[this.newPasswordInputId] = {
            required: true,
            pattern: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[#@$!%*?&])[A-Za-z\d#@$!%*?&]{8,30}$/
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
            pattern: 'Name must be at least 3 characters long and contain only letters'
        };
        formMessages[this.surnameInputId] = {
            required: 'Please enter your surname',
            pattern: 'Surname must be at least 3 characters long and contain only letters'
        };
        formMessages[this.emailInputId] = {
            required: 'Please enter your email address',
            pattern: 'Please enter an email address in the format myemail@mailservice.domain'
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