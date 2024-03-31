import Requester from "../../../../common/pages/classes/requester/Requester.js";
import User from "../../../../common/pages/user/profile/User.js";
import DateRenderer from "../../../../common/pages/classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../common/pages/classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../common/pages/classes/modal/ConfirmationModal.js";
import AppointmentsTable from "../../../../common/pages/classes/table/extends/AppointmentsTable.js";
import ScheduleRenderer from "../../../../common/pages/classes/renderer/extends/ScheduleRenderer.js";
import ScheduleHtmlBuilder from "../../../../common/pages/classes/builder/ScheduleHtmlBuilder.js";
import SearchScheduleForm from "../../../../common/pages/user/forms/schedule/SearchScheduleForm.js";
import OptionBuilder from "../../../../common/pages/classes/builder/OptionBuilder.js";
import CancelOrderUser from "../../../../common/pages/user/forms/order/CancelOrderUser.js";
import API from "../../../../common/pages/api.js";
import MakeOrderUser from "../../../../common/pages/user/forms/order/MakeOrderUser.js";

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
        requester, confirmationModal, API.USER.API.ORDER.service.cancel
    );
    appointmentsTable.setCancelOrderCallback(
        cancelOrderUser.addListener, cancelOrderUser
    );

    /**
     * Initialize the renderer of the schedule
     * @type {ScheduleRenderer}
     */
    let scheduleRenderer = new ScheduleRenderer(
            requester, appointmentsTable, confirmationModal,
            new ScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );
    /**
     * Initialize the form of search free schedule for services
     * @type {SearchScheduleForm}
     */
    let searchScheduleForm = new SearchScheduleForm(
        requester, scheduleRenderer,
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