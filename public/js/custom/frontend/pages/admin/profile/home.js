import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    /**
     * Fill the admin info
     */
    admin.getUserInfo();
});