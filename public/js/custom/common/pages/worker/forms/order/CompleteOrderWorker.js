import OrderConfirmationModal from "../../../classes/modal/OrderConfirmationModal.js";

class CompleteOrderWorker extends OrderConfirmationModal
{
    constructor(requester, confirmationModal, apiUrl) {
        super(requester, confirmationModal, apiUrl);

        this.scheduleCardBase = 'schedule-card';
    }
    getTriggerIcon(id) {
        return document.querySelector(
            `#schedule-card-${id} .fe-check`
        );
    }
    getConfirmationModalContent(card = null) {
        return {
            'headline': 'Confirmation!',
            'content': card ?? '',
            'message': 'Please, confirm that you would like to <b>mark as completed</b> the appointment for the selected schedule item.'
        }
    }
    getDataAttributes(triggerIcon) {
        let scheduleId = triggerIcon.getAttribute('data-schedule-id');
        let orderId = triggerIcon.getAttribute('data-order-id');

        return {
            'order_id': orderId,
            'schedule_id': scheduleId
        }
    }
    getDataToSend(data) {
        return data;
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

        /**
         * Remove from the schedule the completed one
         */
        $(`#${this.scheduleCardBase}-${response.data.schedule_id}`).remove();
    }
}
export default CompleteOrderWorker;