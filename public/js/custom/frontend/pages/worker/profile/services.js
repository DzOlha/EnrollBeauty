import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import ServicesTable from "../../../../common/pages/classes/table/extends/ServicesTable.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import AddServiceForm from "../../../../common/pages/worker/forms/service/AddServiceForm.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import API from "../../../../common/pages/api.js";
import EditServiceForm from "../../../../common/pages/worker/forms/service/EditServiceForm.js";
import DeleteServiceForm from "../../../../common/pages/worker/forms/service/DeleteServiceForm.js";
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    let servicesTable = new ServicesTable(
        requester, API.WORKER.API.SERVICE.get["all-with-departments"]
    );

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewServiceForm = new AddServiceForm(
        requester, modalForm, new OptionBuilder(), servicesTable,
        API.WORKER.API.SERVICE.add, API.WORKER.API.DEPARTMENT.get.all
    );

    let editForm = new EditServiceForm(
        requester, modalForm, new OptionBuilder(), servicesTable,
        API.WORKER.API.SERVICE.add, API.WORKER.API.DEPARTMENT.get.all,
        API.WORKER.API.SERVICE.edit, false
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