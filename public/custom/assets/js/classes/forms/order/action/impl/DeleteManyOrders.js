import ActionManyOrders from "../ActionManyOrders.js";

/**
 * Orders can be deleted if:
 *      1) it has status
 *          -1: Canceled
 *           1: Completed
 *      2) status = 0 [Upcoming] AND end_datetime  < current_datetime
 *
 */
class DeleteManyOrders extends ActionManyOrders
{
    constructor(
        requester, table, apiAction, width = ''
    ) {
        super(requester, table);
        this.apiAction = apiAction;
        this.actionButtonId = 'delete-orders-btn';
        this.actionButtonClass = `bg-warning ${width}`;
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