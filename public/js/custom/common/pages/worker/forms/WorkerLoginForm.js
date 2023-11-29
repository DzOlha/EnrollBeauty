
class WorkerLoginForm extends LoginForm {
    constructor() {
        super();
        this.submitActionUrl = '/api/worker/login';
        this.accountUrl = '/web/worker/account'
    }
}