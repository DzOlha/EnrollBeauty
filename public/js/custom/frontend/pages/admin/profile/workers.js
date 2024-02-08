import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";
import WorkersTable from "../../../../common/pages/classes/table/extends/WorkersTable.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import AddWorkerForm from "../../../../common/pages/admin/forms/worker/AddWorkerForm.js";
import EditWorkerForm from "../../../../common/pages/admin/forms/worker/EditWorkerForm.js";
import DeleteWorkerForm from "../../../../common/pages/admin/forms/worker/DeleteWorkerForm.js";
import API from "../../../../common/pages/api.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let workersTable = new WorkersTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);
    let optionBuilder =  new OptionBuilder();

    let addNewWorkerForm = new AddWorkerForm(
        requester, modalForm, optionBuilder, workersTable
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
        requester, modalForm, optionBuilder, workersTable
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