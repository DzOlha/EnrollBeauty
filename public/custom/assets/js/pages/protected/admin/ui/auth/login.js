import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";
import AdminLoginForm from "../../classes/forms/auth/AdminLoginForm.js";

$(function () {
    let requester = new Requester();
    let login = new AdminLoginForm(
        requester,
        API.AUTH.API.ADMIN.login,
        API.ADMIN.WEB.PROFILE.home
    );
    login.addListenerSubmitForm();
});