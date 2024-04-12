import RegistrationForm from "../../classes/forms/auth/RegistrationForm.js";
import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";

$(function () {
    let requester = new Requester();
    let reg = new RegistrationForm(
        requester,
        API.AUTH.API.USER.register,
        API.AUTH.WEB.USER.login
    );
    reg.addListenerSubmitForm();
});