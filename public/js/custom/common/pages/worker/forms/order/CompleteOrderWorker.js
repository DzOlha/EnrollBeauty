import CancelOrderWorker from "./CancelOrderWorker.js";

class CompleteOrderWorker extends CancelOrderWorker
{
    constructor(requester, confirmationModal, apiUrl) {
        super(requester, confirmationModal, apiUrl);
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
    getDataToSend(data) {
        return {
            'order_id': data.order_id
        };
    }
}
export default CompleteOrderWorker;