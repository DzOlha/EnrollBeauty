import User from "../../user/profile/User.js";
import API from "../../../../common/pages/api.js";
import CONST from "../../../../constants.js";

class Admin extends User {
    constructor(requester) {
        super(requester);
        this.apiUserInfoUrl = API.ADMIN.API.PROFILE.get;
        this.roleName = '[Admin]';

        this.imgPath = CONST.adminImgFolder;
    }
}
export default Admin;