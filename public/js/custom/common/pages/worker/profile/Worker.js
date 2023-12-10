
import User from "../../user/profile/User.js";
import API from "../../../../common/pages/api.js";
class Worker extends User {
    constructor(requester) {
        super(requester);
        this.apiUserInfoUrl = API.WORKER.API.PROFILE.get;
        this.roleName = '[Worker]';
    }
}
export default Worker;