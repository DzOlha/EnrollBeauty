
class WorkerLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = '/api/worker/auth/login';
        this.accountUrl = '/web/worker/profile/home'
    }
}