import RegistrationForm from "../../../../common/pages/user/forms/RegistrationForm.js";

$(function () {
    let reg = new RegistrationForm();
    reg.addListenerSubmitForm();
});