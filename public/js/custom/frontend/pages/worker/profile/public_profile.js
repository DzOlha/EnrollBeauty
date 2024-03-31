import Requester from "../../../../common/pages/classes/requester/Requester.js";
import WorkerPublicProfile from "../../../../common/pages/worker/profile/WorkerPublicProfile.js";

$(function () {
    let requester = new Requester();

    let publicProfile = new WorkerPublicProfile(requester);
    publicProfile.init();
})