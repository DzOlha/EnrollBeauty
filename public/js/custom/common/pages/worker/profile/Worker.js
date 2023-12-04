
class Worker extends User {
    constructor(requesterObject) {
        super(requesterObject);
        this.apiUserInfoUrl = '/api/worker/getWorkerInfo';
    }
}