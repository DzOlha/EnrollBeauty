import Requester from "../../../../../classes/requester/Requester.js";
import DepartmentsTable from "../../../../../classes/table/extends/DepartmentsTable.js";
import Admin from "../../classes/Admin.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import AddDepartmentForm from "../../classes/forms/department/AddDepartmentForm.js";
import API from "../../../../../config/api/api.js";
import EditDepartmentForm from "../../classes/forms/department/EditDepartmentForm.js";
import DeleteDepartmentForm from "../../classes/forms/department/DeleteDepartmentForm.js";
import ModalBuilder from "../../classes/forms/department/view_workers/ModalBuilder.js";
import ViewWorkersModal from "../../classes/forms/department/view_workers/ViewWorkersModal.js";

$(function () {
    let requester = new Requester();
    let departmentsTable = new DepartmentsTable(requester);

    let admin = new Admin(requester);
    /**
     * Fill the admin info
     */
    admin.getUserInfo();

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);
    let optionBuilder = new OptionBuilder();

    /**
     * Add form
     */
    let addForm = new AddDepartmentForm(
        requester, modalForm, optionBuilder, departmentsTable,
        API.ADMIN.API.DEPARTMENT.add
    );
    addForm.addListenerShowAddForm();

    /**
     * Edit form
     */
    let editForm = new EditDepartmentForm(
        requester, modalForm, optionBuilder, departmentsTable,
        API.ADMIN.API.DEPARTMENT.edit, API.ADMIN.API.DEPARTMENT.get.one
    );
    departmentsTable.setManageCallback(
        editForm.addListenerManage, editForm
    );

    /**
     * Delete form
     */
    let deleteForm = new DeleteDepartmentForm(
        requester, API.ADMIN.API.DEPARTMENT.delete, formBuilder
    );
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );

    /**
     * Modals with Workers
     */
    let extraModalBuilder = new ModalBuilder();
    let viewWorkers = new ViewWorkersModal(
        requester, extraModalBuilder, API.ADMIN.API.WORKER.get["all-by-department"],
        'data-department-id', 'department_id'
    );
    departmentsTable.setShowWorkersCallback(
        viewWorkers.addListenerShow, viewWorkers
    );
    departmentsTable.POPULATE();

    editForm.setShowWorkersCallback(
        viewWorkers.addListenerShow, viewWorkers
    );
})