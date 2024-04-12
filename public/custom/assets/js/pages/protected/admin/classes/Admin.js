import User from "../../user/classes/User.js";
import API from "../../../../config/api/api.js";
import CONST from "../../../../config/contants/constants.js";

class Admin extends User {
    constructor(requester, getProfileApi = API.ADMIN.API.PROFILE.get) {
        super(requester, getProfileApi);
        this.roleName = '[Admin]';
        this.imgPath = CONST.adminImgFolder;
    }
}
export default Admin;