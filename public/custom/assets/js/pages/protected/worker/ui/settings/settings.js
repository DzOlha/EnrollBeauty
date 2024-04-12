import Requester from "../../../../../classes/requester/Requester.js";
import Worker from "../../classes/Worker.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import EditWorkerPersonalInfo from "../../classes/forms/settings/personal_information/EditWorkerPersonalInfo.js";
import API from "../../../../../config/api/api.js";
import EditWorkerSocialNetworks from "../../classes/forms/settings/social_networks/EditWorkerSocialNetworks.js";
import SettingsPublicProfile from "../../classes/forms/settings/public_profile/SettingsPublicProfile.js";


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
        requester, API.WORKER.API.PROFILE['personal-info'].edit,
        optionBuilder,
        API.WORKER.API.PROFILE['personal-info'].get,
        API.WORKER.API.PROFILE.id,
        API.WORKER.API.POSITION.get.one,
        API.WORKER.API.ROLE.get.one
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
    let publicProfile = new SettingsPublicProfile(
        requester, API.WORKER.API.PROFILE.get
    );
    publicProfile.init();
})