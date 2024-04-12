import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";
import ChangeDefaultForm from "../../classes/forms/auth/ChangeDefaultForm.js";


$(function () {
    let requester = new Requester();
    let change = new ChangeDefaultForm(
        requester,
        API.AUTH.API.ADMIN["change-default-admin-info"],
        API.AUTH.WEB.ADMIN.login
    );
    change.addListenerSubmitForm();
});