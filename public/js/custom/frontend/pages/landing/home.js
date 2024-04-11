import Requester from "../../../common/pages/classes/requester/Requester.js";
import PriceListFiller from "../../../common/pages/landing/home/pricelist/classes/filler/PriceListFiller.js";
import API from "../../../common/pages/api.js";
import PricelistBuilder from "../../../common/pages/landing/home/pricelist/classes/builder/PricelistBuilder.js";
import DateRenderer from "../../../common/pages/classes/renderer/extends/DateRenderer.js";
import TimeRenderer from "../../../common/pages/classes/renderer/extends/TimeRenderer.js";
import ConfirmationModal from "../../../common/pages/classes/modal/ConfirmationModal.js";
import ScheduleRenderer from "../../../common/pages/classes/renderer/extends/ScheduleRenderer.js";
import ScheduleHtmlBuilder from "../../../common/pages/classes/builder/ScheduleHtmlBuilder.js";
import SearchScheduleForm from "../../../common/pages/user/forms/schedule/SearchScheduleForm.js";
import OptionBuilder from "../../../common/pages/classes/builder/OptionBuilder.js";
import MakeOrderUser from "../../../common/pages/user/forms/order/MakeOrderUser.js";
import DepartmentCardFiller from "../../../common/pages/landing/home/departments/classes/filler/DepartmentCardFiller.js";
import departmentCardBuilder
    from "../../../common/pages/landing/home/departments/classes/builder/DepartmentCardBuilder.js";
import WorkerCardFiller from "../../../common/pages/landing/home/workers/classes/filler/WorkerCardFiller.js";
import WorkerCardBuilder from "../../../common/pages/landing/home/workers/classes/builder/WorkerCardBuilder.js";

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
        requester, null, confirmationModal,
        new ScheduleHtmlBuilder(), dateRenderer, timeRenderer
    );
    /**
     * Initialize the form of search free schedule for services
     * @type {SearchScheduleForm}
     */
    let searchScheduleForm = new SearchScheduleForm(
        requester, scheduleRenderer,
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