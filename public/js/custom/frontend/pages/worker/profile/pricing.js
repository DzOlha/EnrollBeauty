
import Requester from "../../../../common/pages/classes/requester/Requester.js";
import PricingTable from "../../../../common/pages/classes/table/extends/PricingTable.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import AddPricingForm from "../../../../common/pages/worker/forms/pricing/AddPricingForm.js";
import EditPricingForm from "../../../../common/pages/worker/forms/pricing/EditPricingForm.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import API from "../../../../common/pages/api.js";
import DeletePricingForm from "../../../../common/pages/worker/forms/pricing/DeletePricingForm.js";

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

    let deleteForm = new DeletePricingForm(
        requester, API.WORKER.API.PROFILE["service-pricing"].delete,
        formBuilder
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
    pricingTable.setManageCallback(
        editPricingForm.addListenerManagePricing, editPricingForm
    );
    pricingTable.POPULATE();

    /**
     * Listen click on 'Add Pricing Item' button
     * to show the form in modal window
     */
    addNewPricingForm.addListenerShowAddPricingForm();

    /**
     * Set delete callback for the edit form
     */
    editPricingForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );
});