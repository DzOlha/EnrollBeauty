import Requester from "../../../../../../classes/requester/Requester.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";
import User from "../../../classes/User.js";
import SearchOrdersForm from "../../../../../../classes/forms/order/SearchOrdersForm.js";
import CancelManyOrders from "../../../../../../classes/forms/order/action/impl/CancelManyOrders.js";
import DeleteManyOrders from "../../../../../../classes/forms/order/action/impl/DeleteManyOrders.js";

$(function () {
    let requester = new Requester();
    let user = new User(requester);
    user.getUserInfo();

    let table = new OrdersTable(
        requester, API.USER.API.ORDER.service.get["all-limited"]
    );

    /**
     * Search Orders
     */
    let form = new SearchOrdersForm(
        requester, API.USER.API.ORDER.service.get["all-limited"], table,
        API.USER.API.SERVICE.get.all,
        API.USER.API.WORKER.get.all,
        null,
        null,
        null
    );
    form.init();

    /**
     * Mass action
     */
    let delete_ = new DeleteManyOrders(
        requester, table, API.USER.API.ORDER.service.delete.many, 'col-lg-4'
    );
    let cancel_ = new CancelManyOrders(
        requester, table, API.USER.API.ORDER.service.cancel.many, 'col-lg-4'
    );

    table.setMassActionCallback(() => {
        cancel_.addListenerClickOnCellOfCheckbox(
            [
                cancel_.actionButtonId,
                delete_.actionButtonId
            ]
        );
    })
})