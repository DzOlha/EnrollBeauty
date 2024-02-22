import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    worker.getUserInfo();

    $(`#photo-input`).dropify();
})