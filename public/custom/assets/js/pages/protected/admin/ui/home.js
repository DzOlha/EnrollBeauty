import Requester from "../../../../classes/requester/Requester.js";
import Admin from "../classes/Admin.js";
import API from "../../../../config/api/api.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(
        requester, API.ADMIN.API.PROFILE.get
    );
    /**
     * Fill the admin info
     */
    admin.getUserInfo();
});