import LoginForm from "../../user/forms/LoginForm.js";
import API from "../../../../common/pages/api.js";

class AdminLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = API.AUTH.API.ADMIN.login;
        this.accountUrl = API.ADMIN.WEB.PROFILE.home;
    }
}
export default AdminLoginForm;