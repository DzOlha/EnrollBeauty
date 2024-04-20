import Requester from "../../../../../../classes/requester/Requester.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";
import Admin from "../../../classes/Admin.js";
import SearchOrdersForm from "../../../../../../classes/forms/order/SearchOrdersForm.js";
import DeleteManyOrders from "../../../../../../classes/forms/order/action/impl/DeleteManyOrders.js";
import CompleteManyOrders from "../../../../../../classes/forms/order/action/impl/CompleteManyOrders.js";
import CancelManyOrders from "../../../../../../classes/forms/order/action/impl/CancelManyOrders.js";

$(function () {
    let requester = new Requester();
    let admin = new Admin(requester);
    admin.getUserInfo();

    let table = new OrdersTable(
        requester, API.ADMIN.API.ORDER.service.get["all-limited"]
    );
    table.setManageCallback(() => {}, this);

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

    /**
     * Mass action
     */
    let delete_ = new DeleteManyOrders(
        requester, table, API.ADMIN.API.ORDER.service.delete
    );
    let complete_ = new CompleteManyOrders(
        requester, table, API.ADMIN.API.ORDER.service.complete
    );
    let cancel_ = new CancelManyOrders(
        requester, table, API.ADMIN.API.ORDER.service.cancel
    );

    table.setMassActionCallback(() => {
        delete_.addListenerClickOnCellOfCheckbox(
            [
                delete_.actionButtonId,
                complete_.actionButtonId,
                cancel_.actionButtonId
            ]
        );
    })
})