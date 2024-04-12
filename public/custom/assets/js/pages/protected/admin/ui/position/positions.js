import Requester from "../../../../../classes/requester/Requester.js";
import Admin from "../../classes/Admin.js";
import PositionsTable from "../../../../../classes/table/extends/PositionsTable.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import AddPositionForm from "../../classes/forms/position/AddPositionForm.js";
import API from "../../../../../config/api/api.js";
import EditPositionForm from "../../classes/forms/position/EditPositionForm.js";
import DeletePositionForm from "../../classes/forms/position/DeletePositionForm.js";

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
        requester, API.ADMIN.API.POSITION.get.one,
        modalForm, table,
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