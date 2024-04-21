import ActionManyOrders from "../ActionManyOrders.js";

/**
 * Orders can be completed if:
 *      1) it has status
 *          0: Upcoming AND start_datetime < current_datetime
 *
 */
class CompleteManyOrders extends ActionManyOrders
{
    constructor(
        requester, table, apiAction
    ) {
        super(requester, table);
        this.apiAction = apiAction;
        this.actionButtonId = 'complete-orders-btn';
        this.actionButtonClass = 'bg-success';
        this.actionButtonText = 'Complete';
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
export default CompleteManyOrders;