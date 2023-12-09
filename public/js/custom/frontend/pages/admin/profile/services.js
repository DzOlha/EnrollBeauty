
$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let servicesTable = new ServicesTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewServiceForm = new AddServiceForm(
        requester, modalForm, new OptionBuilder(), servicesTable,
        '/api/admin/service/add'
    );

    /**
     * Fill the worker info
     */
    admin.getUserInfo();

    /**
     * Populate table of all workers
     */
    servicesTable.POPULATE();

    /**
     * Listen click on 'Add Pricing Item' button
     * to show the form in modal window
     */
    addNewServiceForm.addListenerShowAddServiceForm();
});