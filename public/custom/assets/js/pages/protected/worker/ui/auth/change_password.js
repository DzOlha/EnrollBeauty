import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";
import ChangePasswordForm from "../../classes/forms/auth/ChangePasswordForm.js";

$(function () {
    let requester = new Requester();
    let change = new ChangePasswordForm(
        requester,
        API.AUTH.API.WORKER["change-password"],
        API.AUTH.WEB.WORKER.login
    );
    change.addListenerSubmitForm();
});