
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    let pricingTable = new PricingTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let optionBuilder = new OptionBuilder();
    let addNewPricingForm = new AddPricingForm(
        requester, modalForm, optionBuilder, pricingTable
    );

    let editPricingForm = new EditPricingForm(
        requester, modalForm, optionBuilder, pricingTable
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

    /**
     * Listen click on 'Manage' button to edit or delete the specific pricing
     */
    editPricingForm.addListenerManagePricing();
});