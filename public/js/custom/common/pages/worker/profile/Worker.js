
import User from "../../user/profile/User.js";
import API from "../../../../common/pages/api.js";
import CONST from "../../../../constants.js";
class Worker extends User {
    constructor(requester) {
        super(requester);
        this.apiUserInfoUrl = API.WORKER.API.PROFILE.get;
        this.roleName = '[Worker]';

        this.imgPath = CONST.workerImgFolder;
    }
}
export default Worker;