import AdminLoginForm from "../../../../common/pages/admin/forms/AdminLoginForm.js";

$(function () {
    let login = new AdminLoginForm();
    login.addListenerSubmitForm();
});