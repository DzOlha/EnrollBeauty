import Admin from "../../../../common/pages/admin/profile/Admin.js";
import Requester from "../../../../common/pages/classes/requester/Requester.js";
import AffiliatesTable from "../../../../common/pages/classes/table/extends/AffiliatesTable.js";
import API from "../../../../common/pages/api.js";
import DateRenderer from "../../../../common/pages/classes/renderer/extends/DateRenderer.js";
import AddAffiliateForm from "../../../../common/pages/admin/forms/affiliate/AddAffiliateForm.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import EditAffiliateForm from "../../../../common/pages/admin/forms/affiliate/EditAffiliateForm.js";
import DeleteAffiliateForm from "../../../../common/pages/admin/forms/affiliate/DeleteAffiliateForm.js";

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
        requester, modalForm, table, API.ADMIN.API.WORKER.get.all,
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