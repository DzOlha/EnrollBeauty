import Requester from "../../../../common/pages/classes/requester/Requester.js";
import WorkerPublicProfile from "../../../../common/pages/worker/forms/settings/public_profile/WorkerPublicProfile.js";

$(function () {
    let requester = new Requester();

    let publicProfile = new WorkerPublicProfile(requester);
    publicProfile.init();
})