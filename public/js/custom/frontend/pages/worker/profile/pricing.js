
import Requester from "../../../../common/pages/classes/requester/Requester.js";
import PricingTable from "../../../../common/pages/classes/table/extends/PricingTable.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import AddPricingForm from "../../../../common/pages/worker/forms/AddPricingForm.js";
import EditPricingForm from "../../../../common/pages/worker/forms/EditPricingForm.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";

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
     * Set update callback for the pricing item
     * and
     * Populate table of all workers
     */
    pricingTable.setUpdateCallback(
        editPricingForm.addListenerManagePricing, editPricingForm
    );
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

    /**
     * Set update callback in pricing table
     */
    addNewPricingForm.setUpdateCallback(
        editPricingForm.addListenerManagePricing, editPricingForm
    );
});