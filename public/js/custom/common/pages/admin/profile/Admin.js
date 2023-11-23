class Admin extends User {
    constructor(requestorObject) {
        super(requestorObject);
        this.apiUserInfoUrl = '/api/admin/getAdminInfo';
    }
}