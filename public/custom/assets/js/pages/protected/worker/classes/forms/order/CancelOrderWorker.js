import OrderConfirmationModal from "../../../../../../classes/modal/OrderConfirmationModal.js";

class CancelOrderWorker extends OrderConfirmationModal
{
    constructor(requester, confirmationModal, apiUrl,
                scheduleBuilder, dateRenderer, timeRenderer)
    {
        super(requester, confirmationModal, apiUrl);
        this.scheduleBuilder = scheduleBuilder;
        this.dateRenderer = dateRenderer;
        this.timeRenderer = timeRenderer;

        this.submitSearchButtonId = 'submit-search-button';
        this.cardBaseId = 'schedule-card';
        this.cancelIconClass = 'fe-x';
    }
    setEditScheduleCallback(callback, context) {
        this.editScheduleCallback = callback.bind(context);
    }
    setDeleteScheduleCallback(callback, context) {
        this.deleteScheduleCallback = callback.bind(context);
    }
    getTriggerIcon(id) {
        return document.querySelector(
            `#${this.cardBaseId}-${id} .${this.cancelIconClass}`
        );
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
    getConfirmationModalContent(card = null, triggerIcon = null) {
        return {
            'headline': 'Confirmation!',
            'content': card ?? '',
            'message': 'Please, confirm that you would like to <b>cancel</b> the appointment for the selected schedule item.'
        }
    }

    /**
     * @param response = {
     *     success:
     *     data: {
     *          schedule_id:
     *          order_id: nullable
     *          service_id:
     *          department_id:
     *          service_name:
     *          user_id: nullable
     *          user_email: nullable
     *          affiliate_id:
     *          city:
     *          address:
     *          day:
     *          start_time:
     *          end_time:
     *          price:
     *          currency:
     *     }
     * }
     * @protected
     */
    _successCallback(response) {
        super._successCallback(response);
        // /**
        //  * Regenerate available schedules search result
        //  */
        // $(`#${this.submitSearchButtonId}`).click();

        /**
         * Update the schedule card after cancelling
         */
        let oldCard = document.getElementById(
            `${this.cardBaseId}-${response.data.schedule_id}`
        );

        let newCard = this.scheduleBuilder.createScheduleCard(
            response.data.schedule_id, response.data.user_id, response.data.service_id,
            response.data.affiliate_id, response.data.service_name,
            response.data.price, response.data.currency,
            response.data.user_email,
            this.dateRenderer.render(response.data.day),
            this.timeRenderer.renderShortTime(response.data.start_time),
            this.timeRenderer.renderShortTime(response.data.end_time),
            `c. ${response.data.city}, ${response.data.address}`,
            response.data.order_id
        );
        let div = document.createElement('div');
        div.insertAdjacentHTML('afterbegin', newCard);

        oldCard.replaceWith(div.firstChild);

        this.editScheduleCallback(response.data.schedule_id);
        this.deleteScheduleCallback(response.data.schedule_id);
    }
}
export default CancelOrderWorker;