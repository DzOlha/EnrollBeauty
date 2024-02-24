import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import API from "../../../../common/pages/api.js";
import EditWorkerPersonalInfo
    from "../../../../common/pages/worker/forms/settings/personal_information/EditWorkerPersonalInfo.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    worker.getUserInfo();

    let optionBuilder = new OptionBuilder();
    let editPersonalInfo = new EditWorkerPersonalInfo(
        requester, optionBuilder, API.WORKER.API.PROFILE['personal-info'].edit
    );
    editPersonalInfo.setUpForm();

})