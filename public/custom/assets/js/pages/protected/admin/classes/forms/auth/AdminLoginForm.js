import LoginForm from "../../../../user/classes/forms/auth/LoginForm.js";

class AdminLoginForm extends LoginForm {
    constructor(requester, submitUrl, accountUrl) {
        super(requester, submitUrl, accountUrl);
    }
}
export default AdminLoginForm;