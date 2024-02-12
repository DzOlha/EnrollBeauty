import DepartmentsTable from "../../../../common/pages/classes/table/extends/DepartmentsTable.js";
import Requester from "../../../../common/pages/classes/requester/Requester.js";
import AddDepartmentForm from "../../../../common/pages/admin/forms/department/AddDepartmentForm.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import API from "../../../../common/pages/api.js";
import EditDepartmentForm from "../../../../common/pages/admin/forms/department/EditDepartmentForm.js";
import DeleteDepartmentForm from "../../../../common/pages/admin/forms/department/DeleteDepartmentForm.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";

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
        API.ADMIN.API.DEPARTMENT.edit
    );
    departmentsTable.setManageCallback(
        editForm.addListenerManage, editForm
    );
    departmentsTable.POPULATE();

    /**
     * Delete form
     */
    let deleteForm = new DeleteDepartmentForm(
        requester, API.ADMIN.API.DEPARTMENT.delete, formBuilder
    );
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );
})