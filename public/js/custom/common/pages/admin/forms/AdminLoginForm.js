
class AdminLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = '/api/admin/auth/login';
        this.accountUrl = '/web/admin/profile/home'
    }
}