import User from "../../user/classes/User.js";
import API from "../../../../config/api/api.js";
import CONST from "../../../../config/contants/constants.js";

class Worker extends User {
    constructor(requester) {
        super(requester);
        this.apiUserInfoUrl = API.WORKER.API.PROFILE.get;
        this.roleName = '[Worker]';

        this.imgPath = CONST.workerImgFolder;
    }
}
export default Worker;