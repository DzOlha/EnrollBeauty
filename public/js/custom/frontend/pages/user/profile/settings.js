import Requester from "../../../../common/pages/classes/requester/Requester.js";
import User from "../../../../common/pages/user/profile/User.js";
import API from "../../../../common/pages/api.js";
import EditUserSocialNetworks
    from "../../../../common/pages/user/forms/settings/social_networks/EditUserSocialNetworks.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);

    user.getUserInfo();

    /**
     * --------------------------- Personal Information----------------------------------
     */


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