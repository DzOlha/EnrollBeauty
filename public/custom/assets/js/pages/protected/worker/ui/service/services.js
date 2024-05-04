import Requester from "../../../../../classes/requester/Requester.js";
import Worker from "../../classes/Worker.js";
import ServicesTable from "../../../../../classes/table/extends/ServicesTable.js";
import API from "../../../../../config/api/api.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import AddServiceForm from "../../classes/forms/service/AddServiceForm.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import EditServiceForm from "../../classes/forms/service/EditServiceForm.js";
import DeleteServiceForm from "../../classes/forms/service/DeleteServiceForm.js";


$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    let servicesTable = new ServicesTable(
        requester, API.WORKER.API.SERVICE.get["all-with-departments"], true
    );

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewServiceForm = new AddServiceForm(
        requester, modalForm, servicesTable,
        API.WORKER.API.SERVICE.add,
        API.WORKER.API.DEPARTMENT.get["all-by-worker"]
    );

    let editForm = new EditServiceForm(
        requester,
        API.WORKER.API.SERVICE.get.one,
        modalForm, servicesTable,
        API.WORKER.API.SERVICE.edit,
        API.WORKER.API.DEPARTMENT.get["all-by-worker"],
        false
    );

    let deleteForm = new DeleteServiceForm(
        requester, API.WORKER.API.SERVICE.delete,
        formBuilder
    )

    /**
     * Set the delete callback on the edit form
     */
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );

    /**
     * Fill the worker info
     */
    worker.getUserInfo();

    /**
     * Populate table of all workers
     */
    servicesTable.setManageCallback(
        editForm.addListenerManage, editForm
    );
    servicesTable.POPULATE();

    /**
     * Listen click on 'Add Pricing Item' button
     * to show the form in modal window
     */
    addNewServiceForm.addListenerShowAddServiceForm();
});