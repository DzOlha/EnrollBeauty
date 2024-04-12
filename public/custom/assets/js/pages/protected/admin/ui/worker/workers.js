import Requester from "../../../../../classes/requester/Requester.js";
import Admin from "../../classes/Admin.js";
import WorkersTable from "../../../../../classes/table/extends/WorkersTable.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import AddWorkerForm from "../../classes/forms/worker/AddWorkerForm.js";
import API from "../../../../../config/api/api.js";
import EditWorkerForm from "../../classes/forms/worker/EditWorkerForm.js";
import DeleteWorkerForm from "../../classes/forms/worker/DeleteWorkerForm.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let workersTable = new WorkersTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);
    let optionBuilder =  new OptionBuilder();

    let addNewWorkerForm = new AddWorkerForm(
        requester,
        API.ADMIN.API.WORKER.register,
        API.ADMIN.API.POSITION.get.all,
        API.ADMIN.API.ROLE.get.all,
        modalForm, optionBuilder, workersTable
    );

    /**
     * Fill the admin info
     */
    admin.getUserInfo();

    /**
     * Listen click on 'Add New Worker' button
     * to show the form in modal window
     */
    addNewWorkerForm.addListenerShowAddWorkerForm();

    /**
     * Manage the worker row
     */
    let editForm = new EditWorkerForm(
        requester,
        API.ADMIN.API.WORKER.edit,
        API.ADMIN.API.POSITION.get.all,
        API.ADMIN.API.ROLE.get.all,
        API.ADMIN.API.WORKER.get.one,
        modalForm, optionBuilder, workersTable
    );

    /**
     * Populate table of all workers
     */
    workersTable.setManageCallback(
        editForm.addListenerEdit, editForm
    );
    workersTable.POPULATE();

    /**
     * Delete worker form
     */
    let deleteForm = new DeleteWorkerForm(
        requester, API.ADMIN.API.WORKER.delete, formBuilder
    );
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );
});