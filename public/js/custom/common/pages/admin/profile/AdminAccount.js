class AdminAccount extends Account {
    constructor(requestorObject, appointmentsTable, searchForm) {
        super(requestorObject, appointmentsTable, searchForm);
        this.apiUserInfoUrl = '/api/admin/getAdminInfo';
    }
}