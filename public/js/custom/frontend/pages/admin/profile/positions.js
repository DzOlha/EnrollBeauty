import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";
import PositionsTable from "../../../../common/pages/classes/table/extends/PositionsTable.js";
import AddPositionForm from "../../../../common/pages/admin/forms/position/AddPositionForm.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import API from "../../../../common/pages/api.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    admin.getUserInfo();

    let table = new PositionsTable(requester);
    table.POPULATE();

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);
    let optionBuilder = new OptionBuilder();

    let addForm = new AddPositionForm(
        requester, modalForm, optionBuilder, table,
        API.ADMIN.API.DEPARTMENT.get.all,
        API.ADMIN.API.POSITION.add
    );
    addForm.addListenerShowAddForm();


})