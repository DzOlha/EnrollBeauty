import OrderConfirmationModal from "../../../classes/modal/OrderConfirmationModal.js";

class CancelOrderUser extends OrderConfirmationModal
{
    constructor(requester, confirmationModal, apiUrl) {
        super(requester, confirmationModal, apiUrl);
        this.cardBaseId = '';
        this.dataAttributeId = 'data-appointment-id';
    }

    getTriggerIcon(id) {
        return document.getElementById(
            `cancel-${id}`
        );
    }
    getDataAttributes(triggerIcon) {
        let id = triggerIcon.getAttribute(this.dataAttributeId);

        return {
            'order_id': id
        }
    }
    getDataToSend(data) {
        return data;
    }
    getConfirmationModalContent(card = null, triggerIcon = null) {
        let serviceName = triggerIcon.getAttribute('data-service-name');
        let day = triggerIcon.getAttribute('data-day');
        let startTime = triggerIcon.getAttribute('data-start-time');
        let endTime = triggerIcon.getAttribute('data-end-time');
        let price = triggerIcon.getAttribute('data-price');

        return {
            'headline': 'Confirmation!',
            'content': ``,
            'message': `Please confirm that you would like to <b>cancel</b> the appointment <b>"${serviceName}"</b> on ${day} at ${startTime} - ${endTime} with a total cost of ${price}`
        }
    }

    _successCallback(response) {
        super._successCallback(response);

        /**
         * Regenerate available schedules search result
         */
        $(`#${this.submitSearchButtonId}`).click();

        let row = document.querySelector(
            `tr[${this.dataAttributeId}='${response.data.id}']`
        );
        row.remove();
    }

}
export default CancelOrderUser;