import LoginForm from "../../../../common/pages/user/forms/auth/LoginForm.js";

$(function () {
    let login = new LoginForm();
    login.addListenerSubmitForm();
});