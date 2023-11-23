class AdminAccount extends Account {
    constructor(requestorObject, table, searchForm) {
        super(requestorObject, table, searchForm);
        this.apiUserInfoUrl = '/api/admin/getAdminInfo';
    }

    getWorkers() {
        this.table.manageAll();
    }
}