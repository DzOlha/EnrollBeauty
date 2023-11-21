
class AdminLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = '/api/admin/login';
        this.accountUrl = '/web/admin/account'
    }
}