
$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let workersTable = new WorkersTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewWorkerForm = new AddWorkerForm(
        requester, modalForm, new OptionBuilder(), workersTable
    );

    /**
     * Fill the admin info
     */
    admin.getUserInfo();

    /**
     * Populate table of all workers
     */
    workersTable.POPULATE();

    /**
     * Listen click on 'Add New Worker' button
     * to show the form in modal window
     */
    addNewWorkerForm.addListenerShowAddWorkerForm();
});