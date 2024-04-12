import Requester from "../../../../../classes/requester/Requester.js";
import Admin from "../../classes/Admin.js";
import DateRenderer from "../../../../../classes/renderer/extends/DateRenderer.js";
import AffiliatesTable from "../../../../../classes/table/extends/AffiliatesTable.js";
import API from "../../../../../config/api/api.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import AddAffiliateForm from "../../classes/forms/affiliate/AddAffiliateForm.js";
import DeleteAffiliateForm from "../../classes/forms/affiliate/DeleteAffiliateForm.js";
import EditAffiliateForm from "../../classes/forms/affiliate/EditAffiliateForm.js";

$(function () {
    let requester = new Requester();

    let admin = new Admin(requester);
    admin.getUserInfo();

    let dateRenderer = new DateRenderer();
    let table = new AffiliatesTable(
        requester, API.ADMIN.API.AFFILIATE.get["all-limited"], dateRenderer
    );

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    /**
     * Add Form
     * @type {AddAffiliateForm}
     */
    let addForm = new AddAffiliateForm(
        requester, modalForm, table, API.ADMIN.API.WORKER.get.all,
        API.ADMIN.API.AFFILIATE.add
    );
    addForm.init();

    /**
     * Delete Form
     * @type {DeleteAffiliateForm}
     */
    let deleteForm = new DeleteAffiliateForm(
        requester, API.ADMIN.API.AFFILIATE.delete, formBuilder
    );

    /**
     * Edit Form
     * @type {EditAffiliateForm}
     */
    let editForm = new EditAffiliateForm(
        requester, API.ADMIN.API.AFFILIATE.get.one,
        modalForm, table,
        API.ADMIN.API.WORKER.get.all,
        API.ADMIN.API.AFFILIATE.edit, true
    );
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );
    table.setManageCallback(
        editForm.addListenerManage, editForm
    );
    table.POPULATE();
})