import OrderConfirmationModal from "../../../classes/modal/OrderConfirmationModal.js";

class MakeOrderUser extends OrderConfirmationModal
{
    constructor(requester, confirmationModal, apiUrl, table) {
        super(requester, confirmationModal, apiUrl);
        this.cardBaseId = '';
        this.dataAttributeId = 'data-schedule-id';
        this.table = table;

        this.scheduleCardBase = 'schedule-card';
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

    /**
     * @param response = {
     *     success:
     *     data: {
     *         schedule_id:
     *     }
     * }
     * @protected
     */
    _successCallback(response) {
        super._successCallback(response);

        // /**
        //  * Remove the ordered schedule item from the result list
        //  */
        // $(`#${this.scheduleCardBase}-${response.data.schedule_id}`).remove();

        /**
         * Regenerate available schedules search result
         */
        $(`#${this.submitSearchButtonId}`).click();

        /**
         * Regenerate table of orders
         */
        this.table.regenerate();
    }
}
export default MakeOrderUser;