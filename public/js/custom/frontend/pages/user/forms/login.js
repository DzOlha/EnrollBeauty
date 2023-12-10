import LoginForm from "../../../../common/pages/user/forms/LoginForm.js";

$(function () {
    let login = new LoginForm();
    login.addListenerSubmitForm();
});