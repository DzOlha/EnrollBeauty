import Requester from "../../../../../../classes/requester/Requester.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";
import Admin from "../../../classes/Admin.js";
import SearchOrdersForm from "../../../../../../classes/forms/order/SearchOrdersForm.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    admin.getUserInfo();

    let table = new OrdersTable(
        requester, API.ADMIN.API.ORDER.service.get["all-limited"]
    );
    table.setManageCallback(() => {

    }, this);

    /**
     * Search Orders
     */
    let form = new SearchOrdersForm(
        requester, API.ADMIN.API.ORDER.service.get["all-limited"], table,
        API.ADMIN.API.SERVICE.get.all,
        API.ADMIN.API.WORKER.get.all,
        API.ADMIN.API.DEPARTMENT.get.all,
        API.ADMIN.API.AFFILIATE.get.all,
        API.ADMIN.API.USER.get["all-by-email"]
    );
    form.init();
})