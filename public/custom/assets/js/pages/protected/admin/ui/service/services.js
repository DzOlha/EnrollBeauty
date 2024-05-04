import Requester from "../../../../../classes/requester/Requester.js";
import Admin from "../../classes/Admin.js";
import ServicesTable from "../../../../../classes/table/extends/ServicesTable.js";
import API from "../../../../../config/api/api.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import AddServiceForm from "../../../worker/classes/forms/service/AddServiceForm.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import EditServiceForm from "../../../worker/classes/forms/service/EditServiceForm.js";
import DeleteServiceForm from "../../../worker/classes/forms/service/DeleteServiceForm.js";
import ModalBuilder from "../../classes/forms/department/view_workers/ModalBuilder.js";
import ViewWorkersModal from "../../classes/forms/department/view_workers/ViewWorkersModal.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let servicesTable = new ServicesTable(
        requester, API.ADMIN.API.SERVICE.get["all-with-departments"]
    );

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewServiceForm = new AddServiceForm(
        requester, modalForm, servicesTable,
        API.ADMIN.API.SERVICE.add, API.ADMIN.API.DEPARTMENT.get.all
    );

    let editForm = new EditServiceForm(
        requester,
        API.ADMIN.API.SERVICE.get.one,
        modalForm, servicesTable,
        API.ADMIN.API.SERVICE.edit,
        API.ADMIN.API.DEPARTMENT.get.all,
        true
    );

    let deleteForm = new DeleteServiceForm(
        requester, API.ADMIN.API.SERVICE.delete,
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
    admin.getUserInfo();

    /**
     * Populate table of all workers
     */
    servicesTable.setManageCallback(
        editForm.addListenerManage, editForm
    );

    /**
     * Listen click on 'Add Pricing Item' button
     * to show the form in modal window
     */
    addNewServiceForm.addListenerShowAddServiceForm();


    /**
     * Modals with Workers
     */
    let extraModalBuilder = new ModalBuilder();
    let viewWorkers = new ViewWorkersModal(
        requester, extraModalBuilder, API.ADMIN.API.WORKER.get["all-by-service"],
        'data-service-id', 'service_id', 'Workers who provide the Service'
    );
    servicesTable.setShowWorkersCallback(
        viewWorkers.addListenerShow, viewWorkers
    );
    servicesTable.POPULATE();

    editForm.setShowWorkersCallback(
        viewWorkers.addListenerShow, viewWorkers
    );
});