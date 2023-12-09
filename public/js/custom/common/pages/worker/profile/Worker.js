
class Worker extends User {
    constructor(requesterObject) {
        super(requesterObject);
        this.apiUserInfoUrl = '/api/worker/profile/get/';
        this.roleName = '[Worker]';
    }
}