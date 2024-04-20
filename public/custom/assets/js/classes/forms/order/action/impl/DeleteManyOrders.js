import ActionManyOrders from "../ActionManyOrders.js";

/**
 * Orders can be deleted if:
 *      1) it has status
 *          -1: Canceled
 *           1: Completed
 *
 */
class DeleteManyOrders extends ActionManyOrders
{
    constructor(
        requester, table, apiAction
    ) {
        super(requester, table);
        this.apiAction = apiAction;
        this.actionButtonId = 'delete-orders-btn';
        this.actionButtonClass = 'bg-danger';
        this.actionButtonText = 'Delete';
        this.listenerAttr = 'data-listener-set';

        this.init();
    }
    init() {
        this.appendButton(
            this.actionButtonId, this.actionButtonClass,
            this.actionButtonText, this.apiAction
        );
    }
}
export default DeleteManyOrders;