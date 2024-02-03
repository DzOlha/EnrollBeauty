import Requester from "../../../../common/pages/classes/requester/Requester.js";
import Worker from "../../../../common/pages/worker/profile/Worker.js";
import DateRenderer from "../../../../common/pages/classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../common/pages/classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../common/pages/classes/modal/ConfirmationModal.js";
import WorkerScheduleRenderer from "../../../../common/pages/classes/renderer/extends/WorkerScheduleRenderer.js";
import WorkerScheduleHtmlBuilder from "../../../../common/pages/classes/builder/WorkerScheduleHtmlBuilder.js";
import WorkerSearchScheduleForm from "../../../../common/pages/worker/forms/schedule/WorkerSearchScheduleForm.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import FormBuilder from "../../../../common/pages/classes/builder/FormBuilder.js";
import FormModal from "../../../../common/pages/classes/modal/FormModal.js";
import AddScheduleForm from "../../../../common/pages/worker/forms/schedule/AddScheduleForm.js";
import CancelOrderWorker from "../../../../common/pages/worker/forms/order/CancelOrderWorker.js";
import API from "../../../../common/pages/api.js";
import CompleteOrderWorker from "../../../../common/pages/worker/forms/order/CompleteOrderWorker.js";
import optionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
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

    let scheduleBuilder = new WorkerScheduleHtmlBuilder();
    let scheduleRenderer = new WorkerScheduleRenderer(
        requester, null, confirmationModal,
        scheduleBuilder, dateRenderer, timeRenderer
    );

    /**
     * Cancel order by worker
     * @type {CancelOrderWorker}
     */
    let cancelOrderWorker = new CancelOrderWorker(
        requester, confirmationModal, API.WORKER.API.ORDER.service.cancel,
        scheduleBuilder, dateRenderer, timeRenderer
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
    searchScheduleForm.init();

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let addNewScheduleForm = new AddScheduleForm(
        requester, modalForm, new OptionBuilder(), searchScheduleForm
    );

    /**
     * Listen click on 'Add Schedule Item' button
     * to show the form in modal window
     */
    addNewScheduleForm.addListenerShowAddScheduleForm();
});