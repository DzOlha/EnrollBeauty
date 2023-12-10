import WorkerLoginForm from "../../../../common/pages/worker/forms/WorkerLoginForm.js";

$(function () {
    let login = new WorkerLoginForm();
    login.addListenerSubmitForm();
});