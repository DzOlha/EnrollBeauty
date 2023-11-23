
$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let workersTable = new WorkersTable(requester);
    let addNewWorkerForm = new AddWorkerForm();

    /**
     * Fill the admin info
     */
    admin.getUserInfo();

    /**
     * Populate table of all workers
     */
    workersTable.POPULATE();
});