import Requester from "../../../../../../classes/requester/Requester.js";
import WorkerPublicProfile from "../../../classes/profile/public/WorkerPublicProfile.js";
import API from "../../../../../../config/api/api.js";

$(function () {
    let requester = new Requester();

    let publicProfile = new WorkerPublicProfile(
        requester, API.OPEN.API.WORKER.PROFILE.get.one
    );
    publicProfile.init();
})