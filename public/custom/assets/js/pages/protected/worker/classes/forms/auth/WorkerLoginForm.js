import LoginForm from "../../../../user/classes/forms/auth/LoginForm.js";


class WorkerLoginForm extends LoginForm {
    constructor(requester, submitUrl, accountUrl) {
        super(requester, submitUrl, accountUrl);
    }
}
export default WorkerLoginForm;