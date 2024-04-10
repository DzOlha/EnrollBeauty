
import API from "../../../../common/pages/api.js";
import LoginForm from "../../user/forms/auth/LoginForm.js";

class AdminLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = API.AUTH.API.ADMIN.login;
        this.accountUrl = API.ADMIN.WEB.PROFILE.home;
    }
}
export default AdminLoginForm;