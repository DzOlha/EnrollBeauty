import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";
import PositionsTable from "../../../../common/pages/classes/table/extends/PositionsTable.js";
import AddPositionForm from "../../../../common/pages/admin/forms/position/AddPositionForm.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import API from "../../../../common/pages/api.js";
import EditPositionForm from "../../../../common/pages/admin/forms/position/EditPositionForm.js";
import DeletePositionForm from "../../../../common/pages/admin/forms/position/DeletePositionForm.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    admin.getUserInfo();

    let table = new PositionsTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addForm = new AddPositionForm(
        requester, modalForm, table,
        API.ADMIN.API.DEPARTMENT.get.all,
        API.ADMIN.API.POSITION.add
    );
    addForm.init();


    let editForm = new EditPositionForm(
        requester, modalForm, table,
        API.ADMIN.API.DEPARTMENT.get.all,
        API.ADMIN.API.POSITION.edit,
        true
    );


    let deleteForm = new DeletePositionForm(
        requester, API.ADMIN.API.POSITION.delete, formBuilder
    );
    editForm.setDeleteCallback(
        deleteForm.addListenerDelete, deleteForm
    );

    table.setManageCallback(
        editForm.addListenerManage, editForm
    );
    table.POPULATE();



})