import Requester from "../../../../../../classes/requester/Requester.js";
import Worker from "../../../classes/Worker.js";
import OrdersTable from "../../../../../../classes/table/extends/OrdersTable.js";
import API from "../../../../../../config/api/api.js";
import SearchOrdersForm from "../../../../../../classes/forms/order/SearchOrdersForm.js";
import CompleteManyOrders from "../../../../../../classes/forms/order/action/impl/CompleteManyOrders.js";
import DeleteManyOrders from "../../../../../../classes/forms/order/action/impl/DeleteManyOrders.js";
import CancelManyOrders from "../../../../../../classes/forms/order/action/impl/CancelManyOrders.js";

$(function () {
    let requester = new Requester();
    let worker = new Worker(requester);
    worker.getUserInfo();

    let table = new OrdersTable(
        requester, API.WORKER.API.ORDER.service.get["all-limited"]
    );

    //table.setManageCallback(() => {}, this);

    /**
     * Search Orders
     */
    let form = new SearchOrdersForm(
        requester, API.WORKER.API.ORDER.service.get["all-limited"], table,
        API.WORKER.API.SERVICE.get.all,
        null,
        API.WORKER.API.DEPARTMENT.get.all,
        API.WORKER.API.AFFILIATE.get.all,
        null
    );
    form.init();

    /**
     * Mass action
     */
    let complete_ = new CompleteManyOrders(
        requester, table, API.WORKER.API.ORDER.service.complete.many
    );
    let delete_ = new DeleteManyOrders(
        requester, table, API.WORKER.API.ORDER.service.delete.many
    );
    let cancel_ = new CancelManyOrders(
        requester, table, API.WORKER.API.ORDER.service.cancel.many
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