import Requester from "../../../../../../classes/requester/Requester.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";
import User from "../../../classes/User.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);
    user.getUserInfo();

    let table = new OrdersTable(
        requester, API.USER.API.ORDER.service.get["all-limited"]
    );
    table.POPULATE();
})