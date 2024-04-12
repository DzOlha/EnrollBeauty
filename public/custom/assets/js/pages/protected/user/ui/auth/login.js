import LoginForm from "../../classes/forms/auth/LoginForm.js";
import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";

$(function () {
    let requester = new Requester();
    let login = new LoginForm(
        requester,
        API.AUTH.API.USER.login,
        API.USER.WEB.PROFILE.home
    );
    login.addListenerSubmitForm();
});