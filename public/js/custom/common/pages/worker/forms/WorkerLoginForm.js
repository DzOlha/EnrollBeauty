
import API from "../../../../common/pages/api.js";
import LoginForm from "../../user/forms/auth/LoginForm.js";

class WorkerLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = API.AUTH.API.WORKER.login;
        this.accountUrl = API.WORKER.WEB.PROFILE.home;
    }
}
export default WorkerLoginForm;