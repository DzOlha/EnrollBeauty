import Requester from "../../../../classes/requester/Requester.js";
import PriceListFiller from "../classes/pricelist/filler/PriceListFiller.js";
import PricelistBuilder from "../classes/pricelist/builder/PricelistBuilder.js";
import API from "../../../../config/api/api.js";
import DateRenderer from "../../../../classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../../classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../../classes/modal/ConfirmationModal.js";
import ScheduleRenderer from "../../../../classes/renderer/extends/ScheduleRenderer.js";
import ScheduleHtmlBuilder from "../../../../classes/builder/ScheduleHtmlBuilder.js";
import SearchScheduleForm from "../../../protected/user/classes/forms/schedule/SearchScheduleForm.js";
import OptionBuilder from "../../../../classes/builder/OptionBuilder.js";
import MakeOrderUser from "../../../protected/user/classes/forms/order/MakeOrderUser.js";
import DepartmentCardFiller from "../classes/departments/filler/DepartmentCardFiller.js";
import departmentCardBuilder from "../classes/departments/builder/DepartmentCardBuilder.js";
import WorkerCardFiller from "../classes/workers/filler/WorkerCardFiller.js";
import WorkerCardBuilder from "../classes/workers/builder/WorkerCardBuilder.js";

$(function () {
    let requester = new Requester();

    /**
     * Pricelist
     */
    let pricelist = new PriceListFiller(
        requester, new PricelistBuilder(),
        API.OPEN.API.SERVICE.PRICING.get.all
    );
    pricelist.init();

    /**
     * Schedule
     */
    let dateRenderer = new DateRenderer();
    let timeRenderer = new TimeRenderer();

    let confirmationModal = new ConfirmationModal();
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
        requester, API.OPEN.API.SCHEDULE.search, scheduleRenderer,
        new OptionBuilder(), dateRenderer, API.OPEN
    );
    searchScheduleForm.init();

    /**
     * Create class to process making new order(appointment)
     */
    let makeOrderUser = new MakeOrderUser(
        requester, confirmationModal, API.OPEN.API.ORDER.service.add,
        null
    );
    scheduleRenderer.setMakeOrderCallback(
        makeOrderUser.addListener, makeOrderUser
    );


    /**
     * Department Cards
     */
    let depFiller = new DepartmentCardFiller(
        requester, new departmentCardBuilder(),
        API.OPEN.API.DEPARTMENT.get["all-limited"]
    );
    depFiller.init();


    /**
     * Workers Card
     */
    let workersFiller = new WorkerCardFiller(
        requester, new WorkerCardBuilder(),
        API.OPEN.API.WORKER.get["all-limited"]
    );
    workersFiller.init();

})