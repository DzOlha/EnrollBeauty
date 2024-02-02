import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import DateRenderer from "../../../../common/pages/classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../common/pages/classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../common/pages/classes/modal/ConfirmationModal.js";
import WorkerScheduleRenderer from "../../../../common/pages/classes/renderer/extends/WorkerScheduleRenderer.js";
import WorkerScheduleHtmlBuilder from "../../../../common/pages/classes/builder/WorkerScheduleHtmlBuilder.js";
import WorkerSearchScheduleForm from "../../../../common/pages/worker/forms/WorkerSearchScheduleForm.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import AddScheduleForm from "../../../../common/pages/worker/forms/AddScheduleForm.js";
import CancelOrderWorker from "../../../../common/pages/worker/forms/order/CancelOrderWorker.js";
import API from "../../../../common/pages/api.js";
import CompleteOrderWorker from "../../../../common/pages/worker/forms/order/CompleteOrderWorker.js";
$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    /**
     * Fill the admin info
     */
    worker.getUserInfo();

    let dateRenderer = new DateRenderer();
    let timeRenderer = new TimeRenderer();

    let confirmationModal = new ConfirmationModal();

    // let appointmentsTable = new AppointmentsTable(
    //     requester, confirmationModal,
    //     dateRenderer, timeRenderer
    // );

    let scheduleRenderer = new WorkerScheduleRenderer(
        requester, null, confirmationModal,
        new WorkerScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );

    /**
     * Cancel order by worker
     * @type {CancelOrderWorker}
     */
    let cancelOrderWorker = new CancelOrderWorker(
        requester, confirmationModal, API.WORKER.API.ORDER.service.cancel
    );
    scheduleRenderer.setCancelOrderCallback(
        cancelOrderWorker.addListener, cancelOrderWorker
    );


    /**
     * Complete order by worker
     * @type {CompleteOrderWorker}
     */
    let completeOrderWorker = new CompleteOrderWorker(
        requester, confirmationModal, API.WORKER.API.ORDER.service.complete
    );
    scheduleRenderer.setCompleteOrderCallback(
        completeOrderWorker.addListener, completeOrderWorker
    );


    let searchScheduleForm = new WorkerSearchScheduleForm(
        requester, scheduleRenderer,
        new OptionBuilder(), dateRenderer
    );

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewScheduleForm = new AddScheduleForm(
        requester, modalForm, new OptionBuilder(), searchScheduleForm
    );

    /**
     * Get information for select elements of the form of searching
     * available schedules for appointments
     */
    searchScheduleForm.getServicesForTheWorker();
    searchScheduleForm.getAffiliates();

    /**
     * Add the listener to handle submission of the form of schedule searching
     */
    searchScheduleForm.addListenerSubmitForm(searchScheduleForm);

    /**
     * Make initial submission of the form to show the available schedules
     * in all services/departments for the current date
     */
    searchScheduleForm.handleFormSubmission();

    /**
     * Listen click on 'Add Schedule Item' button
     * to show the form in modal window
     */
    addNewScheduleForm.addListenerShowAddScheduleForm();
});