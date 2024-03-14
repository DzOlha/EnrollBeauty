import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import API from "../../../../common/pages/api.js";
import EditWorkerPersonalInfo
    from "../../../../common/pages/worker/forms/settings/personal_information/EditWorkerPersonalInfo.js";
import EditWorkerSocialNetworks
    from "../../../../common/pages/worker/forms/settings/social_networks/EditWorkerSocialNetworks.js";
import SettingsPublicProfile from "../../../../common/pages/worker/forms/settings/public_profile/SettingsPublicProfile.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    worker.getUserInfo();

    /**
     * --------------------------- Personal Information----------------------------------
     * @type {OptionBuilder}
     */
    let optionBuilder = new OptionBuilder();
    let editPersonalInfo = new EditWorkerPersonalInfo(
        requester, optionBuilder, API.WORKER.API.PROFILE['personal-info'].edit
    );
    editPersonalInfo.setUpForm();


    /**
     * --------------------------- Social Networks ---------------------------------------
     */
    let editSocial = new EditWorkerSocialNetworks(
        requester,
        API.WORKER.API.PROFILE.id,
        API.WORKER.API.PROFILE.social.get.all,
        API.WORKER.API.PROFILE.social.edit.all
    );
    editSocial.init();


    /**
     * ---------------------------- Public Profile ----------------------------------------
     */
    let publicProfile = new SettingsPublicProfile(requester);
    publicProfile.init();
})