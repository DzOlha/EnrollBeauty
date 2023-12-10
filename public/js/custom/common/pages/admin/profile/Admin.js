import User from "../../user/profile/User.js";
import API from "../../../../common/pages/api.js";

class Admin extends User {
    constructor(requester) {
        super(requester);
        this.apiUserInfoUrl = API.ADMIN.API.PROFILE.get;
        this.roleName = '[Admin]';
    }
}
export default Admin;