import Requester from "../../../../../../classes/requester/Requester.js";
import Worker from "../../../classes/Worker.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    worker.getUserInfo();

    let table = new OrdersTable(
        requester, API.WORKER.API.ORDER.service.get["all-limited"]
    );
    table.POPULATE();
})