import LoginForm from "../../user/forms/LoginForm.js";
import API from "../../../../common/pages/api.js";
class WorkerLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = API.WORKER.API.AUTH.login;
        this.accountUrl = API.WORKER.WEB.PROFILE.home;
    }
}
export default WorkerLoginForm;