import ActionManyOrders from "../ActionManyOrders.js";

/**
 * Orders can be canceled if:
 *      1) it has status
 *          0: Upcoming
 *
 */
class CancelManyOrders extends ActionManyOrders
{
    constructor(
        requester, table, apiAction
    ) {
        super(requester, table);
        this.apiAction = apiAction;
        this.actionButtonId = 'cancel-orders-btn';
        this.actionButtonClass = 'bg-danger';
        this.actionButtonText = 'Cancel';
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
export default CancelManyOrders;