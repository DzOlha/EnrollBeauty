class RegistrationForm extends Form {
    constructor() {
        super(
            'registration-form',
            'registration-form-submit',
            '/api/user/register',
            new Requestor()
        );
        this.nameInputId = 'name-input';
        this.surnameInputId = 'surname-input';
        this.emailInputId = 'email-input';
        this.passwordInputId = 'password-input';
        this.confirmPasswordInputId = 'confirm-password-input';
    }

    successCallbackSubmit(response) {
        this.showSuccessMessage('You successfully created account! PLease, log in!');
        setTimeout(() => {
            window.location.href = "/web/user/login";
        }, 3000)
    }

    errorCallbackSubmit(message) {
        this.showErrorMessage(message);
    }

    collectDataToSend() {
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

        return {
            'name': name,
            'surname': surname,
            'email': email,
            'password': password,
            'confirm_password': confirmPassword
        };
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
        formRules[this.passwordInputId] = {
            required: true,
            pattern: /^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,30}$/
        };
        formRules[this.confirmPasswordInputId] = {
            required: true,
            equalTo: `#${this.passwordInputId}`
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
        formMessages[this.passwordInputId] = {
            required: 'Please enter your password',
            pattern: 'Password must contain at least one uppercase letter, one lowercase letter, one digit, one special character, and be between 8 to 30 characters long'
        };
        formMessages[this.confirmPasswordInputId] = {
            required: 'Please confirm your password',
            equalTo: 'Passwords do not match'
        };
        return formMessages;
    }
}