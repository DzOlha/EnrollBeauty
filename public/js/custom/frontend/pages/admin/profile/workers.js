import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Admin from "../../../../common/pages/admin/profile/Admin.js";
import WorkersTable from "../../../../common/pages/classes/table/extends/WorkersTable.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import AddWorkerForm from "../../../../common/pages/admin/forms/AddWorkerForm.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    let workersTable = new WorkersTable(requester);

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewWorkerForm = new AddWorkerForm(
        requester, modalForm, new OptionBuilder(), workersTable
    );

    /**
     * Fill the admin info
     */
    admin.getUserInfo();

    /**
     * Populate table of all workers
     */
    workersTable.POPULATE();

    /**
     * Listen click on 'Add New Worker' button
     * to show the form in modal window
     */
    addNewWorkerForm.addListenerShowAddWorkerForm();
});