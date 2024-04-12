import Requester from "../../../../../classes/requester/Requester.js";
import Worker from "../../classes/Worker.js";
import PricingTable from "../../../../../classes/table/extends/PricingTable.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import AddPricingForm from "../../classes/forms/pricing/AddPricingForm.js";
import API from "../../../../../config/api/api.js";
import EditPricingForm from "../../classes/forms/pricing/EditPricingForm.js";
import DeletePricingForm from "../../classes/forms/pricing/DeletePricingForm.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    let pricingTable = new PricingTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let optionBuilder = new OptionBuilder();
    let addNewPricingForm = new AddPricingForm(
        requester,
        API.WORKER.API.PROFILE["service-pricing"].add,
        API.WORKER.API.SERVICE.get.all,
        API.WORKER.API.PROFILE["service-pricing"].get.all,
        modalForm, optionBuilder, pricingTable
    );

    let editPricingForm = new EditPricingForm(
        requester,
        API.WORKER.API.PROFILE["service-pricing"].edit,
        API.WORKER.API.SERVICE.get.all,
        API.WORKER.API.PROFILE["service-pricing"].get.all,
        modalForm, optionBuilder, pricingTable
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