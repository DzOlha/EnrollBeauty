import Requester from "../../../../../classes/requester/Requester.js";
import User from "../../classes/User.js";
import EditUserPersonalInfo from "../../classes/forms/settings/personal_information/EditUserPersonalInfo.js";
import API from "../../../../../config/api/api.js";
import EditUserSocialNetworks from "../../classes/forms/settings/social_networks/EditUserSocialNetworks.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);

    user.getUserInfo();

    /**
     * --------------------------- Personal Information----------------------------------
     */
    let editInfo = new EditUserPersonalInfo(
        requester,
        API.USER.API.PROFILE.id,
        API.USER.API.PROFILE['personal-info'].get,
        API.USER.API.PROFILE['personal-info'].edit
    );
    editInfo.init();


    /**
     * --------------------------- Social Networks ---------------------------------------
     */
    let editSocial = new EditUserSocialNetworks(
        requester,
        API.USER.API.PROFILE.id,
        API.USER.API.PROFILE["social-networks"].get,
        API.USER.API.PROFILE["social-networks"].edit
    );
    editSocial.init();


    /**
     * ---------------------------- Public Profile ----------------------------------------
     */
})