import RegistrationForm from "../../../../common/pages/user/forms/auth/RegistrationForm.js";

$(function () {
    let reg = new RegistrationForm();
    reg.addListenerSubmitForm();
});