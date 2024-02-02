import CancelOrderWorker from "../../../worker/forms/order/CancelOrderWorker.js";

class MakeOrderUser extends CancelOrderWorker
{
    constructor(requester, confirmationModal, apiUrl, table) {
        super(requester, confirmationModal, apiUrl);
        this.cardBaseId = '';
        this.dataAttributeId = 'data-schedule-id';
        this.table = table;
    }
    getTriggerIcon(id) {
        return document.querySelector(
            `#schedule-card-${id} .fe-shopping-cart`
        );
    }
    getDataAttributes(triggerIcon) {
        let id = triggerIcon.getAttribute(this.dataAttributeId);

        return {
            'schedule_id': id
        }
    }
    getDataToSend(data) {
        return data;
    }
    getConfirmationModalContent(card = null, triggerIcon = null) {
        return {
            'headline': 'Confirmation!',
            'content': card ?? '',
            'message': 'Please confirm that you would like to <b>order</b> the selected item from available schedules.'
        }
    }
    _successCallback(response) {
        super._successCallback(response);
        this.table.regenerate();
    }
}
export default MakeOrderUser;