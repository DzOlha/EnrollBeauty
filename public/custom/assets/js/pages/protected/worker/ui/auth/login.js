import Requester from "../../../../../classes/requester/Requester.js";
import API from "../../../../../config/api/api.js";
import WorkerLoginForm from "../../classes/forms/auth/WorkerLoginForm.js";

$(function () {
    let requester = new Requester();
    let login = new WorkerLoginForm(
        requester,
        API.AUTH.API.WORKER.login,
        API.WORKER.WEB.PROFILE.home
    );
    login.addListenerSubmitForm();
});