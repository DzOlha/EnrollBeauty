import OrderConfirmationModal from "../../../../../../classes/modal/OrderConfirmationModal.js";

class DeleteScheduleForm extends OrderConfirmationModal {
    constructor(requester, confirmationModal, apiUrl)
    {
        super(requester, confirmationModal, apiUrl);

        this.submitSearchButtonId = '';
        this.cardBaseId = 'schedule-card';
        this.deleteIconClass = 'fe-trash-2';
    }

    getTriggerIcon(id) {
        return document.querySelector(
            `#${this.cardBaseId}-${id} .${this.deleteIconClass}`
        );
    }
    getDataAttributes(triggerIcon) {
        let scheduleId = triggerIcon.getAttribute('data-schedule-id');

        return {
            'schedule_id': scheduleId
        }
    }
    getDataToSend(data) {
        return data;
    }
    getConfirmationModalContent(card = null, triggerIcon = null) {
        return {
            'headline': 'Confirmation!',
            'content': card ?? '',
            'message': 'Please, confirm that you would like to <b>delete</b> the selected schedule item.'
        }
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *          schedule_id:
     *     }
     * }
     * @protected
     */
    _successCallback(response) {
        super._successCallback(response);

        /**
         * Update the schedule card after cancelling
         */
        let oldCard = document.getElementById(
            `${this.cardBaseId}-${response.data.schedule_id}`
        );
        oldCard.remove();
    }
}
export default DeleteScheduleForm;