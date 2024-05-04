import Requester from "../../../../classes/requester/Requester.js";
import User from "../classes/User.js";
import DateRenderer from "../../../../classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../classes/modal/ConfirmationModal.js";
import AppointmentsTable from "../../../../classes/table/extends/AppointmentsTable.js";
import CancelOrderUser from "../classes/forms/order/CancelOrderUser.js";
import API from "../../../../config/api/api.js";
import ScheduleRenderer from "../../../../classes/renderer/extends/ScheduleRenderer.js";
import ScheduleHtmlBuilder from "../../../../classes/builder/ScheduleHtmlBuilder.js";
import SearchScheduleForm from "../classes/forms/schedule/SearchScheduleForm.js";
import OptionBuilder from "../../../../classes/builder/OptionBuilder.js";
import MakeOrderUser from "../classes/forms/order/MakeOrderUser.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);

    let dateRenderer = new DateRenderer();
    let timeRenderer = new TimeRenderer();

    let confirmationModal = new ConfirmationModal();
    let appointmentsTable = new AppointmentsTable(
        requester, confirmationModal,
        dateRenderer, timeRenderer
    );

    /**
     * Create class for processing an appointment cancellation
     * @type {CancelOrderUser}
     */
    let cancelOrderUser = new CancelOrderUser(
        requester, confirmationModal, API.USER.API.ORDER.service.cancel.one
    );
    appointmentsTable.setCancelOrderCallback(
        cancelOrderUser.addListener, cancelOrderUser
    );

    /**
     * Initialize the renderer of the schedule
     * @type {ScheduleRenderer}
     */
    let scheduleRenderer = new ScheduleRenderer(
            requester, confirmationModal,
            new ScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );
    /**
     * Initialize the form of search free schedule for services
     * @type {SearchScheduleForm}
     */
    let searchScheduleForm = new SearchScheduleForm(
        requester, API.USER.API.SCHEDULE.search, scheduleRenderer,
        new OptionBuilder(), dateRenderer
    );
    searchScheduleForm.init();

    /**
     * Create class to process making new order(appointment)
     */
    let makeOrderUser = new MakeOrderUser(
        requester, confirmationModal, API.USER.API.ORDER.service.add,
        appointmentsTable
    );
    scheduleRenderer.setMakeOrderCallback(
        makeOrderUser.addListener, makeOrderUser
    );

    /**
     * Fill user info
     */
    user.getUserInfo();

    /**
     * Populate upcoming appointments table
     */
    appointmentsTable.POPULATE();
});