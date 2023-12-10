import Requester from "../../../../common/pages/classes/requester/Requester.js";
import User from "../../../../common/pages/user/profile/User.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);


    /**
     * Get the user id for whom display the profile
     * @type {string}
     */
    let urlParams = new URLSearchParams(window.location.search);
    let userId = urlParams.get('user_id');
    if(userId !== null) {
        user.setApiUserInfoUrl(userId);
    }

    /**
     * Fill user info
     */
    user.getUserInfo();
});