import Requester from "../../../../../classes/requester/Requester.js";
import Worker from "../../classes/Worker.js";
import DateRenderer from "../../../../../classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../../classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../../classes/modal/ConfirmationModal.js";
import WorkerScheduleHtmlBuilder from "../../../../../classes/builder/WorkerScheduleHtmlBuilder.js";
import WorkerScheduleRenderer from "../../../../../classes/renderer/extends/WorkerScheduleRenderer.js";
import CancelOrderWorker from "../../classes/forms/order/CancelOrderWorker.js";
import API from "../../../../../config/api/api.js";
import CompleteOrderWorker from "../../classes/forms/order/CompleteOrderWorker.js";
import SearchWorkerScheduleForm from "../../classes/forms/schedule/SearchWorkerScheduleForm.js";
import OptionBuilder from "../../../../../classes/builder/OptionBuilder.js";
import FormBuilder from "../../../../../classes/builder/FormBuilder.js";
import FormModal from "../../../../../classes/modal/FormModal.js";
import AddScheduleForm from "../../classes/forms/schedule/AddScheduleForm.js";
import EditScheduleForm from "../../classes/forms/schedule/EditScheduleForm.js";
import DeleteScheduleForm from "../../classes/forms/schedule/DeleteScheduleForm.js";

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
        requester, confirmationModal, API.WORKER.API.ORDER.service.cancel.one,
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
        requester, confirmationModal, API.WORKER.API.ORDER.service.complete.one
    );
    scheduleRenderer.setCompleteOrderCallback(
        completeOrderWorker.addListener, completeOrderWorker
    );

    let searchScheduleForm = new SearchWorkerScheduleForm(
        requester,
        API.WORKER.API.SCHEDULE.search,
        API.WORKER.API.PROFILE.service.get.all,
        API.WORKER.API.AFFILIATE.get.all,
        scheduleRenderer,
        new OptionBuilder(), dateRenderer
    );
    searchScheduleForm.init();

    let formBuilder = new FormBuilder();
    let modalForm = new FormModal(formBuilder);

    let optionBuilder = new OptionBuilder();
    let addNewScheduleForm = new AddScheduleForm(
        requester,
        API.WORKER.API.SCHEDULE.add,
        API.WORKER.API.PROFILE.service.get.all,
        API.WORKER.API.AFFILIATE.get.all,
        API.WORKER.API.SCHEDULE.get["busy-time-intervals"],
        modalForm, optionBuilder, searchScheduleForm
    );

    /**
     * Listen click on 'Add Schedule Item' button
     * to show the form in modal window
     */
    addNewScheduleForm.addListenerShowAddScheduleForm();


    /**
     * Edit schedule form
     */
    let editForm = new EditScheduleForm(
        requester,
        API.WORKER.API.SCHEDULE.edit,
        API.WORKER.API.PROFILE.service.get.all,
        API.WORKER.API.AFFILIATE.get.all,
        API.WORKER.API.SCHEDULE.get["edit-busy-time-intervals"],
        modalForm, optionBuilder, searchScheduleForm,

        API.WORKER.API.SCHEDULE.get.one,
        scheduleBuilder, dateRenderer, timeRenderer
    );
    scheduleRenderer.setEditScheduleCallback(
        editForm.addListenerEdit, editForm
    );
    cancelOrderWorker.setEditScheduleCallback(
        editForm.addListenerEdit, editForm
    );

    /**
     * Delete schedule form
     */
    let deleteForm = new DeleteScheduleForm(
        requester, confirmationModal, API.WORKER.API.SCHEDULE.delete
    );
    scheduleRenderer.setDeleteScheduleCallback(
        deleteForm.addListener, deleteForm
    );
    cancelOrderWorker.setDeleteScheduleCallback(
        deleteForm.addListener, deleteForm
    );
    editForm.setDeleteCallback(
        deleteForm.addListener, deleteForm
    );
});