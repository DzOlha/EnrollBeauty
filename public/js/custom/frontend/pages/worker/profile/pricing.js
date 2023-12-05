
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    let pricingTable = new PricingTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewPricingForm = new AddPricingForm(
        requester, modalForm, new OptionBuilder(), pricingTable
    );

    /**
     * Fill the worker info
     */
    worker.getUserInfo();

    /**
     * Populate table of all workers
     */
    pricingTable.POPULATE();

    /**
     * Listen click on 'Add Pricing Item' button
     * to show the form in modal window
     */
    addNewPricingForm.addListenerShowAddPricingForm();
});